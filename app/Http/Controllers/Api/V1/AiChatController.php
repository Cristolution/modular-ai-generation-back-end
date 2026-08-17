<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\UserAiProvider;
use App\Services\AnthropicChat;
use App\Services\AnthropicKeyMissing;
use App\Services\AnthropicUpstreamError;
use App\Services\AnthropicUpstreamUnreachable;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class AiChatController extends Controller
{
    public function __construct(private readonly AnthropicChat $anthropic) {}

    /**
     * POST /api/v1/ai/chat
     *
     * Streams an Anthropic-SSE completion back to the client. The upstream
     * API key is the USER'S — pulled from their `user_ai_providers` row,
     * decrypted server-side, and discarded after the stream completes. The
     * browser never sees the key.
     *
     * Auth: Sanctum (Bearer token). `provider_id` must reference a row in
     * the authenticated user's provider set; foreign IDs return 404, not
     * 403, to avoid leaking which IDs exist.
     *
     * Each user can carry multiple providers (MiniMax, Anthropic, LM Studio,
     * …) — `provider_id` selects which one carries this request.
     */
    public function chat(Request $request): StreamedResponse|JsonResponse
    {
        // The full-project generation task can take 60–90 seconds to
        // stream end-to-end (style.css + layout.css + 5+ slide-NN.html +
        // data.json). PHP's default 30s `max_execution_time` kills the
        // stream mid-JSON, leaving the frontend with a truncated reply
        // and a "not parseable as a JSON object" error. Extend the
        // budget for this endpoint only — the regular editor modals
        // (single-file regeneration) still finish well inside 30s.
        set_time_limit(180);

        $validated = $request->validate([
            'provider_id' => ['required', 'string', 'uuid'],
            'model' => ['nullable', 'string', 'min:1', 'max:128'],
            'messages' => ['required', 'array', 'min:1'],
            'messages.*.role' => ['required', 'string', 'in:user,assistant'],
            'messages.*.content' => ['required', 'string'],
            'system' => ['nullable', 'string', 'max:100000'],
            // The "Generate full project" page needs the AI to emit
            // style.css + layout.css + every slide-NN.html + data.json +
            // _meta in a single reply — typically 25–35 KB of JSON
            // (~8–10 K tokens). The previous cap of 8192 cut the
            // stream mid-JSON after just style.css + part of the
            // layout, leaving the parser with "not parseable as a
            // JSON object". 16384 covers a 5–10 slide deck; single-file
            // regeneration tasks (editor modals) still default to 4096
            // on the frontend and never hit this ceiling.
            'max_tokens' => ['nullable', 'integer', 'min:1', 'max:16384'],
        ]);

        // Scoped lookup — `findOrFail` on the user's relation ensures user
        // A can never invoke chat against user B's provider. Laravel
        // returns 404 with the standard exception body.
        try {
            /** @var UserAiProvider $provider */
            $provider = $request->user()->aiProviders()->findOrFail($validated['provider_id']);
        } catch (ModelNotFoundException) {
            return response()->json([
                'error' => 'Provider not found for this account.',
                'code' => 'provider_not_found',
            ], 404);
        }

        // Decrypt the API key. If `api_key_encrypted` is null (e.g. LM
        // Studio with no key requirement) or fails to decrypt (corrupt
        // blob, APP_KEY rotation mismatch), surface a 502 with the typed
        // exception code — the frontend uses it to drive a "set your API
        // key" prompt.
        $apiKeyEncrypted = $provider->getRawOriginal('api_key_encrypted');
        $apiKey = '';
        if ($apiKeyEncrypted !== null) {
            try {
                $apiKey = decrypt($apiKeyEncrypted);
            } catch (DecryptException $e) {
                Log::warning('Failed to decrypt user AI provider key', [
                    'user_id' => $request->user()->id,
                    'provider_id' => $provider->id,
                    'error' => $e->getMessage(),
                ]);

                return response()->json([
                    'error' => 'Stored API key is unreadable. Please re-enter it in Settings → AI Providers.',
                    'code' => 'anthropic_key_missing',
                ], 502);
            }
        }

        if ($apiKey === '') {
            return response()->json([
                'error' => 'No API key configured for this provider. Add one in Settings → AI Providers.',
                'code' => 'anthropic_key_missing',
            ], 502);
        }

        // Default `model` to the provider's `default_model` if the caller
        // didn't supply one (or sent an empty string).
        $model = $validated['model'] ?? null;
        if (! \is_string($model) || $model === '') {
            $model = $provider->default_model;
        }
        if (! \is_string($model) || $model === '') {
            return response()->json([
                'error' => 'No model specified and the provider has no default_model set.',
                'code' => 'model_required',
            ], 422);
        }

        $payload = [
            'model' => $model,
            'messages' => $validated['messages'],
        ];
        if (!empty($validated['system'])) {
            $payload['system'] = $validated['system'];
        }
        if (isset($validated['max_tokens'])) {
            $payload['max_tokens'] = $validated['max_tokens'];
        }

        $userId = $request->user()->id;
        $providerId = $provider->id;
        $baseUrl = $provider->base_url;

        try {
            $stream = $this->anthropic->stream(
                $payload,
                $apiKey,
                $baseUrl,
            );
        } catch (AnthropicKeyMissing|AnthropicUpstreamUnreachable|AnthropicUpstreamError $e) {
            return $this->errorResponse($e);
        } catch (Throwable $e) {
            return $this->errorResponse($e);
        }

        return new StreamedResponse(function () use ($stream, $userId, $providerId) {
            // Disable nginx output buffering if present.
            if (function_exists('apache_setenv')) {
                @apache_setenv('no-gzip', '1');
            }
            @ini_set('output_buffering', '0');
            @ini_set('zlib.output_compression', '0');

            // No SSE preamble needed — Anthropic's stream starts with
            // `event: message_start` immediately. Just flush headers.
            @ob_flush();
            @flush();

            try {
                foreach ($stream as $chunk) {
                    // Each yielded value is a raw byte chunk from the
                    // upstream SSE stream. Forward it verbatim so the
                    // frontend's SSE parser can splice it into its event
                    // buffer.
                    echo $chunk;
                    @ob_flush();
                    @flush();
                }
            } catch (Throwable $e) {
                Log::warning('AI chat stream interrupted', [
                    'user_id' => $userId,
                    'provider_id' => $providerId,
                    'error' => $e->getMessage(),
                ]);
                // Surface the error as a final SSE event so the client can log it.
                echo 'event: error' . "\n";
                echo 'data: ' . json_encode(['error' => 'Stream error: ' . $e->getMessage()]) . "\n\n";
                @ob_flush();
                @flush();
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Connection' => 'keep-alive',
        ]);
    }

    private function errorResponse(Throwable $e): JsonResponse
    {
        $code = match (true) {
            $e instanceof AnthropicKeyMissing => 'anthropic_key_missing',
            $e instanceof AnthropicUpstreamUnreachable => 'upstream_unreachable',
            $e instanceof AnthropicUpstreamError => 'upstream_error',
            default => 'stream_error',
        };

        return response()->json([
            'error' => $e->getMessage(),
            'code' => $code,
        ], 502);
    }
}
