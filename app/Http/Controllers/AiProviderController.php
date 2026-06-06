<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAiProviderRequest;
use App\Http\Requests\UpdateAiProviderRequest;
use App\Http\Resources\AiProviderResource;
use App\Models\UserAiProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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

    public function test(Request $request, string $providerId): JsonResponse
    {
        $provider = $request->user()->aiProviders()->findOrFail($providerId);

        $start = microtime(true);

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . decrypt($provider->getRawOriginal('api_key_encrypted')),
            ])->timeout(10)->get(rtrim($provider->base_url, '/') . '/models');

            $latencyMs = (int) ((microtime(true) - $start) * 1000);

            return response()->json([
                'ok' => $response->successful(),
                'message' => $response->successful() ? 'Connection successful' : 'Provider returned error',
                'latency_ms' => $latencyMs,
            ]);
        } catch (\Exception $e) {
            $latencyMs = (int) ((microtime(true) - $start) * 1000);

            return response()->json([
                'ok' => false,
                'message' => 'Connection failed: ' . $e->getMessage(),
                'latency_ms' => $latencyMs,
            ], 422);
        }
    }
}