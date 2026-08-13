<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAiProviderRequest;
use App\Http\Requests\UpdateAiProviderRequest;
use App\Http\Resources\AiProviderResource;
use App\Models\UserAiProvider;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Throwable;

class AiProviderController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $providers = $request->user()->aiProviders()->get();

        return AiProviderResource::collection($providers);
    }

    public function store(CreateAiProviderRequest $request): JsonResponse
    {
        $provider = UserAiProvider::create([
            'user_id' => $request->user()->id,
            'provider' => $request->validated('provider'),
            'display_name' => $request->validated('display_name'),
            'api_key_encrypted' => $request->filled('api_key') ? encrypt($request->validated('api_key')) : null,
            'base_url' => $request->validated('base_url'),
            'default_model' => $request->validated('default_model'),
            'is_active' => true,
            'created_at' => now(),
        ]);

        return response()->json(new AiProviderResource($provider), 201);
    }

    public function show(Request $request, string $providerId): JsonResponse
    {
        $provider = $request->user()->aiProviders()->findOrFail($providerId);

        return response()->json(new AiProviderResource($provider));
    }

    public function update(UpdateAiProviderRequest $request, string $providerId): JsonResponse
    {
        $provider = $request->user()->aiProviders()->findOrFail($providerId);

        $data = $request->validated();

        if (isset($data['api_key'])) {
            $data['api_key_encrypted'] = encrypt($data['api_key']);
            unset($data['api_key']);
        }

        $provider->update($data);

        return response()->json(new AiProviderResource($provider));
    }

    public function destroy(Request $request, string $providerId): JsonResponse
    {
        $provider = $request->user()->aiProviders()->findOrFail($providerId);

        $provider->delete();

        return response()->json(null, 204);
    }

    /**
     * POST /api/v1/me/ai-providers/{id}/test
     *
     * Always returns 200 with a structured body — even on failure — so the
     * frontend can display the actual diagnostic info instead of a generic
     * HTTP error. Failure modes (auth, missing-key, unreachable) are reported
     * via the `ok` flag and `code` field.
     */
    public function test(Request $request, string $providerId): JsonResponse
    {
        try {
            $provider = $request->user()->aiProviders()->findOrFail($providerId);
        } catch (ModelNotFoundException) {
            return response()->json([
                'ok' => false,
                'reachable' => false,
                'code' => 'provider_not_found',
                'message' => 'Provider not found.',
            ], 404);
        }

        $start = microtime(true);

        try {
            // Decrypt outside the HTTP call so a key-rotation / APP_KEY
            // mismatch shows up as a clean diagnostic instead of failing
            // inside `withHeaders()`.
            $apiKeyEncrypted = $provider->getRawOriginal('api_key_encrypted');
            $apiKey = $apiKeyEncrypted ? decrypt($apiKeyEncrypted) : null;

            $headers = ['Accept' => 'application/json'];
            if ($apiKey) {
                $headers['Authorization'] = 'Bearer '.$apiKey;
            }

            $url = $this->testUrlFor($provider);

            $response = \Illuminate\Support\Facades\Http::withHeaders($headers)
                ->timeout(10)
                ->connectTimeout(5)
                ->get($url);

            $latencyMs = (int) ((microtime(true) - $start) * 1000);
            $status = $response->status();

            // "Reachable" means we got any HTTP response at all. 401/404 still
            // prove the server is there and answering; only 5xx or no-response
            // (timeout/DNS) means the provider is actually down.
            $reachable = $status > 0 && $status < 500;
            $ok = $response->successful();

            return response()->json([
                'ok' => $ok,
                'reachable' => $reachable,
                'status' => $status,
                'code' => $ok ? 'ok' : ($reachable ? 'upstream_error' : 'unreachable'),
                'message' => $ok
                    ? 'Connection successful.'
                    : "Provider returned HTTP {$status}.",
                'latency_ms' => $latencyMs,
            ]);
        } catch (Throwable $e) {
            $latencyMs = (int) ((microtime(true) - $start) * 1000);

            Log::warning('AI provider test failed', [
                'provider_id' => $providerId,
                'class' => get_class($e),
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'ok' => false,
                'reachable' => false,
                'code' => 'connection_failed',
                'message' => 'Connection failed: '.$e->getMessage(),
                'class' => get_class($e),
                'latency_ms' => $latencyMs,
            ], 200); // ← diagnostic body, not 5xx
        }
    }

    /**
     * Choose a probe URL appropriate to the provider family.
     *
     * Anthropic-compatible APIs (Anthropic, MiniMax) don't expose /models —
     * hitting that path returns HTML/garbage. Use the base URL itself: any
     * HTTP response (even a 401) proves the server is reachable.
     *
     * OpenAI-compatible APIs (OpenAI, LM Studio, custom) expose /v1/models.
     */
    private function testUrlFor(UserAiProvider $provider): string
    {
        $base = rtrim($provider->base_url, '/');
        $family = strtolower((string) $provider->provider);

        $isAnthropicFamily = $family === 'anthropic'
            || str_contains($base, '/anthropic');

        return $isAnthropicFamily ? $base : $base.'/models';
    }
}