<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\UserAiProvider;
use App\Services\AnthropicChat;
use App\Services\AnthropicKeyMissing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\Sanctum;
use Mockery;
use Tests\TestCase;

class AiChatTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_unauthenticated_request_returns_401(): void
    {
        // No actingAs — request is anonymous. Sanctum rejects before any
        // validation runs.
        $response = $this->postJson('/api/v1/ai/chat', [
            'provider_id' => '00000000-0000-0000-0000-000000000000',
            'model' => 'MiniMax-M3',
            'messages' => [['role' => 'user', 'content' => 'hi']],
        ]);

        $response->assertStatus(401)
            ->assertJson(['error' => 'Unauthenticated.']);
    }

    public function test_missing_provider_id_returns_422(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson('/api/v1/ai/chat', [
            'model' => 'MiniMax-M3',
            'messages' => [['role' => 'user', 'content' => 'hi']],
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['provider_id']);
    }

    public function test_missing_messages_returns_422(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $provider = UserAiProvider::factory()->openai()->for($user)->create();

        $this->postJson('/api/v1/ai/chat', [
            'provider_id' => $provider->id,
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['messages']);
    }

    public function test_invalid_message_role_returns_422(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $provider = UserAiProvider::factory()->openai()->for($user)->create();

        $this->postJson('/api/v1/ai/chat', [
            'provider_id' => $provider->id,
            'model' => 'MiniMax-M3',
            'messages' => [['role' => 'system', 'content' => 'hi']],
        ])->assertStatus(422)->assertJsonValidationErrors(['messages.0.role']);
    }

    public function test_invalid_provider_id_format_returns_422(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson('/api/v1/ai/chat', [
            'provider_id' => 'not-a-uuid',
            'model' => 'MiniMax-M3',
            'messages' => [['role' => 'user', 'content' => 'hi']],
        ])->assertStatus(422)->assertJsonValidationErrors(['provider_id']);
    }

    public function test_other_users_provider_returns_404(): void
    {
        $owner = User::factory()->create();
        $attacker = User::factory()->create();
        $provider = UserAiProvider::factory()->openai()->for($owner)->create();

        Sanctum::actingAs($attacker);

        // Attacker tries to chat on a provider they don't own. Must 404,
        // not 200/403 — leaking existence would let anyone enumerate IDs.
        $this->postJson('/api/v1/ai/chat', [
            'provider_id' => $provider->id,
            'model' => 'MiniMax-M3',
            'messages' => [['role' => 'user', 'content' => 'hi']],
        ])
            ->assertStatus(404)
            ->assertJsonPath('code', 'provider_not_found');
    }

    public function test_nonexistent_provider_id_returns_404(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson('/api/v1/ai/chat', [
            'provider_id' => '00000000-0000-0000-0000-000000000000',
            'model' => 'MiniMax-M3',
            'messages' => [['role' => 'user', 'content' => 'hi']],
        ])
            ->assertStatus(404)
            ->assertJsonPath('code', 'provider_not_found');
    }

    public function test_provider_without_api_key_returns_502_with_anthropic_key_missing_code(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Local LM Studio doesn't need a key — exercise that path. The
        // empty key surfaces as a `anthropic_key_missing` 502.
        $provider = UserAiProvider::factory()->local()->for($user)->create();
        $this->assertNull($provider->getRawOriginal('api_key_encrypted'));

        $this->postJson('/api/v1/ai/chat', [
            'provider_id' => $provider->id,
            'model' => 'llama3',
            'messages' => [['role' => 'user', 'content' => 'hi']],
        ])
            ->assertStatus(502)
            ->assertJsonPath('code', 'anthropic_key_missing');
    }

    public function test_decryption_failure_returns_502(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $provider = UserAiProvider::factory()->openai()->for($user)->create();

        // Tamper the encrypted blob so `decrypt()` throws — verifies the
        // controller doesn't crash with a 500 from the decrypt() failure.
        $provider->forceFill([
            'api_key_encrypted' => 'tampered-not-encrypted-data',
        ])->save();

        $this->postJson('/api/v1/ai/chat', [
            'provider_id' => $provider->id,
            'model' => 'MiniMax-M3',
            'messages' => [['role' => 'user', 'content' => 'hi']],
        ])
            ->assertStatus(502)
            ->assertJsonPath('code', 'anthropic_key_missing');
    }

    public function test_no_model_specified_uses_provider_default_model(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $provider = UserAiProvider::factory()->openai()->for($user)->create([
            'default_model' => 'gpt-4o-mini',
        ]);

        $captured = null;
        $mock = Mockery::mock(AnthropicChat::class);
        // Service receives the provider's default_model when the request
        // omitted `model`. We don't pin the matcher with `with()` here
        // because that would force a Mockery-style constraint; we capture
        // and check inside the closure instead.
        $mock->shouldReceive('stream')
            ->once()
            ->andReturnUsing(function ($payload) use (&$captured) {
                $captured = $payload;
                yield '';
            });
        $this->app->instance(AnthropicChat::class, $mock);

        $response = $this->post('/api/v1/ai/chat', [
            'provider_id' => $provider->id,
            'messages' => [['role' => 'user', 'content' => 'hi']],
        ]);
        $response->assertStatus(200);
        // StreamedResponse is lazy — drain it so the mock's generator
        // runs (this is where the `$captured` assignment happens).
        $response->streamedContent();

        $this->assertNotNull($captured);
        $this->assertSame('gpt-4o-mini', $captured['model']);
    }

    public function test_no_model_and_no_provider_default_returns_422(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $provider = UserAiProvider::factory()->openai()->for($user)->create([
            'default_model' => null,
        ]);

        $this->postJson('/api/v1/ai/chat', [
            'provider_id' => $provider->id,
            'messages' => [['role' => 'user', 'content' => 'hi']],
        ])
            ->assertStatus(422)
            ->assertJsonPath('code', 'model_required');
    }

    public function test_uses_decrypted_user_provider_key_and_base_url(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Real encrypt()/decrypt() round-trip — proves the controller is
        // reading the encrypted blob, decrypting with APP_KEY, and passing
        // the plaintext to the service.
        $expectedKey = 'sk-real-key-' . uniqid();
        $expectedBase = 'https://api.minimax.io/anthropic';

        $provider = UserAiProvider::factory()->for($user)->create([
            'provider' => 'anthropic',
            'display_name' => 'MiniMax',
            'api_key_encrypted' => encrypt($expectedKey),
            'base_url' => $expectedBase,
            'default_model' => 'MiniMax-M3',
        ]);

        $captured = null;
        $mock = Mockery::mock(AnthropicChat::class);
        $mock->shouldReceive('stream')
            ->once()
            ->andReturnUsing(function ($payload, $key, $baseUrl) use (&$captured, $expectedKey, $expectedBase) {
                $captured = ['payload' => $payload, 'key' => $key, 'baseUrl' => $baseUrl];
                yield '';
            });
        $this->app->instance(AnthropicChat::class, $mock);

        $response = $this->post('/api/v1/ai/chat', [
            'provider_id' => $provider->id,
            'model' => 'MiniMax-M3',
            'messages' => [['role' => 'user', 'content' => 'hi']],
        ]);
        $response->assertStatus(200);
        // StreamedResponse is lazy — drain it so the mock generator runs.
        $response->streamedContent();

        // The service received the user's decrypted key + base URL — NOT
        // anything from server-held config.
        $this->assertSame($expectedKey, $captured['key']);
        $this->assertSame($expectedBase, $captured['baseUrl']);
        $this->assertSame('MiniMax-M3', $captured['payload']['model']);
    }

    public function test_uses_user_provider_over_server_held_key(): void
    {
        // Even if `config('services.anthropic.key')` is set to *some*
        // string, the controller must NOT use it — only the user's
        // provider matters. This guards against accidental re-introduction
        // of the shared-key leak path.
        config()->set('services.anthropic.key', 'sk-shared-test-key');
        config()->set('services.anthropic.base_url', 'https://wrong.example.com');

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $provider = UserAiProvider::factory()->for($user)->create([
            'provider' => 'anthropic',
            'api_key_encrypted' => encrypt('sk-user-specific'),
            'base_url' => 'https://api.minimax.io/anthropic',
            'default_model' => 'MiniMax-M3',
        ]);

        $captured = [];
        $mock = Mockery::mock(AnthropicChat::class);
        $mock->shouldReceive('stream')
            ->once()
            ->andReturnUsing(function ($payload, $key, $baseUrl) use (&$captured) {
                $captured = compact('key', 'baseUrl');
                yield '';
            });
        $this->app->instance(AnthropicChat::class, $mock);

        $response = $this->post('/api/v1/ai/chat', [
            'provider_id' => $provider->id,
            'model' => 'MiniMax-M3',
            'messages' => [['role' => 'user', 'content' => 'hi']],
        ]);
        $response->assertStatus(200);
        // StreamedResponse is lazy — drain it so the mock generator runs.
        $response->streamedContent();

        $this->assertSame('sk-user-specific', $captured['key']);
        $this->assertSame('https://api.minimax.io/anthropic', $captured['baseUrl']);
        $this->assertNotSame('sk-shared-test-key', $captured['key']);
    }

    public function test_successful_request_streams_sse(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $provider = UserAiProvider::factory()->openai()->for($user)->create();

        $deltaEvent = json_encode([
            'type' => 'content_block_delta',
            'index' => 0,
            'delta' => ['type' => 'text_delta', 'text' => 'Hello'],
        ]);
        $stopEvent = json_encode(['type' => 'message_stop']);

        $mock = Mockery::mock(AnthropicChat::class);
        $mock->shouldReceive('stream')
            ->once()
            ->andReturnUsing(function () use ($deltaEvent, $stopEvent) {
                yield "event: content_block_delta\ndata: {$deltaEvent}\n\n";
                yield "event: message_stop\ndata: {$stopEvent}\n\n";
            });
        $this->app->instance(AnthropicChat::class, $mock);

        $response = $this->post('/api/v1/ai/chat', [
            'provider_id' => $provider->id,
            'model' => 'MiniMax-M3',
            'messages' => [['role' => 'user', 'content' => 'hi']],
        ]);

        $response->assertStatus(200);
        $this->assertStringStartsWith('text/event-stream', $response->headers->get('Content-Type') ?? '');
        $this->assertStringContainsString('content_block_delta', $response->streamedContent());
        $this->assertStringContainsString('Hello', $response->streamedContent());
        $this->assertStringContainsString('message_stop', $response->streamedContent());
    }

    public function test_optional_fields_are_passed_through_to_the_service(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $provider = UserAiProvider::factory()->openai()->for($user)->create();

        $mock = Mockery::mock(AnthropicChat::class);
        // Controller strips keys the validator dropped (e.g. max_tokens
        // when the request omitted it) — verify with the absence matcher.
        $mock->shouldReceive('stream')
            ->once()
            ->with(
                Mockery::on(function ($payload) {
                    return $payload['model'] === 'MiniMax-M3'
                        && $payload['system'] === 'You are helpful.'
                        && !array_key_exists('max_tokens', $payload);
                }),
                Mockery::any(),
                Mockery::any(),
            )
            ->andReturnUsing(function () {
                yield '';
            });
        $this->app->instance(AnthropicChat::class, $mock);

        $this->post('/api/v1/ai/chat', [
            'provider_id' => $provider->id,
            'model' => 'MiniMax-M3',
            'system' => 'You are helpful.',
            'messages' => [['role' => 'user', 'content' => 'hi']],
        ])->assertStatus(200);
    }
}
