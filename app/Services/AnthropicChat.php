<?php

namespace App\Services;

use Generator;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Typed exceptions for Anthropic-specific failures.
 * Caught by AiChatController and mapped to error codes.
 */
class AnthropicKeyMissing extends RuntimeException {}
class AnthropicUpstreamUnreachable extends RuntimeException {}
class AnthropicUpstreamError extends RuntimeException {}

/**
 * Thin streaming client for Anthropic's /v1/messages endpoint.
 *
 * The API key and base URL are passed in by the caller (typically
 * AiChatController, which decrypts the user's UserAiProvider row). The
 * service intentionally does NOT read them from config — keeping this
 * pure means per-user keys never leak across requests, and rotating a
 * user's key requires nothing more than updating their row.
 *
 * `api_version` and `timeout` are still global config because they don't
 * vary per-user and rarely change at runtime.
 */
class AnthropicChat
{
    /**
     * Stream a chat completion from an Anthropic-compatible API.
     *
     * Yields raw bytes from the upstream response as they arrive. The caller
     * is responsible for forwarding them to the client. Using
     * `withOptions(['stream' => true])` means the upstream body is not
     * buffered — the browser sees tokens as the upstream emits them.
     *
     * @param  array{model:string, system?:string, messages:array<int,array{role:string,content:string}>, max_tokens?:int}  $payload
     * @param  string  $apiKey  The decrypted API key for the upstream (never server-held config).
     * @param  string  $baseUrl The upstream base URL (Anthropic, MiniMax, or any compatible proxy).
     * @return Generator<int,string>
     *
     * @throws AnthropicKeyMissing          when the API key is empty
     * @throws AnthropicUpstreamUnreachable when the upstream connection fails
     * @throws AnthropicUpstreamError       when the upstream returns a non-2xx status
     */
    public function stream(array $payload, string $apiKey, string $baseUrl): Generator
    {
        if ($apiKey === '') {
            throw new AnthropicKeyMissing('Anthropic API key is not configured for this provider');
        }

        $baseUrl = rtrim($baseUrl, '/');
        if ($baseUrl === '') {
            throw new AnthropicUpstreamUnreachable('Provider base URL is empty');
        }

        $apiVersion = (string) config('services.anthropic.api_version', '2023-06-01');
        $timeout = (int) config('services.anthropic.timeout', 60);

        $body = [
            'model' => $payload['model'],
            'messages' => $payload['messages'],
            'max_tokens' => $payload['max_tokens'] ?? 4096,
            'stream' => true,
        ];
        if (!empty($payload['system'])) {
            $body['system'] = $payload['system'];
        }

        try {
            /** @var Response $response */
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => $apiVersion,
                'content-type' => 'application/json',
                'accept' => 'text/event-stream',
            ])
                ->timeout($timeout)
                ->connectTimeout(10)
                ->withOptions(['stream' => true])
                ->withBody(json_encode($body), 'application/json')
                ->post($baseUrl . '/v1/messages');
        } catch (ConnectionException $e) {
            throw new AnthropicUpstreamUnreachable('Anthropic upstream unreachable', 0, $e);
        }

        if (!$response->successful()) {
            throw new AnthropicUpstreamError('Anthropic returned an error: ' . $response->status());
        }

        // Stream the body as raw bytes — preserve Anthropic's SSE framing
        // (event:/data: lines with the standard \n\n separators) so the
        // frontend's SSE parser can read it verbatim.
        $streamBody = $response->getBody();
        $stream = is_object($streamBody) && method_exists($streamBody, 'detach')
            ? $streamBody->detach()
            : $streamBody;

        if (!is_resource($stream)) {
            return;
        }

        try {
            while (!feof($stream)) {
                $chunk = fread($stream, 1024);
                if ($chunk === false || $chunk === '') {
                    break;
                }
                yield $chunk;
            }
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }
    }
}
