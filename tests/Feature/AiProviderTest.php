<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserAiProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AiProviderTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_list_own_ai_providers(): void
    {
        $user = User::factory()->create();
        UserAiProvider::factory()->openai()->for($user)->create();
        UserAiProvider::factory()->anthropic()->for($user)->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/me/ai-providers');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'provider', 'display_name', 'base_url', 'default_model', 'has_key', 'is_active', 'created_at'],
                ],
            ]);
    }

    public function test_guest_cannot_list_ai_providers(): void
    {
        $response = $this->getJson('/api/v1/me/ai-providers');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_ai_provider(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/me/ai-providers', [
            'provider' => 'openai',
            'display_name' => 'My OpenAI',
            'api_key' => 'sk-test-1234567890',
            'base_url' => 'https://api.openai.com/v1',
            'default_model' => 'gpt-4o',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('provider', 'openai')
            ->assertJsonPath('display_name', 'My OpenAI')
            ->assertJsonPath('has_key', true)
            ->assertJsonPath('is_active', true);

        $this->assertDatabaseHas('user_ai_providers', [
            'user_id' => $user->id,
            'provider' => 'openai',
            'display_name' => 'My OpenAI',
        ]);
    }

    public function test_ai_provider_without_api_key_for_local_provider(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/me/ai-providers', [
            'provider' => 'local',
            'display_name' => 'LM Studio',
            'base_url' => 'http://localhost:1234/v1',
            'default_model' => 'llama3',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('provider', 'local')
            ->assertJsonPath('has_key', false);
    }

    public function test_cannot_create_ai_provider_with_invalid_provider(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/me/ai-providers', [
            'provider' => 'invalid_provider',
            'base_url' => 'https://api.example.com/v1',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['provider']);
    }

    public function test_cannot_create_ai_provider_with_invalid_url(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/me/ai-providers', [
            'provider' => 'openai',
            'base_url' => 'not-a-valid-url',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['base_url']);
    }

    public function test_authenticated_user_can_view_single_ai_provider(): void
    {
        $user = User::factory()->create();
        $provider = UserAiProvider::factory()->openai()->for($user)->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/me/ai-providers/' . $provider->id);

        $response->assertStatus(200)
            ->assertJsonPath('id', $provider->id)
            ->assertJsonPath('provider', 'openai');
    }

    public function test_cannot_view_others_ai_provider(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $provider = UserAiProvider::factory()->openai()->for($owner)->create();
        Sanctum::actingAs($other);

        $response = $this->getJson('/api/v1/me/ai-providers/' . $provider->id);

        $response->assertStatus(404);
    }

    public function test_authenticated_user_can_update_ai_provider(): void
    {
        $user = User::factory()->create();
        $provider = UserAiProvider::factory()->openai()->for($user)->create();
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/v1/me/ai-providers/' . $provider->id, [
            'display_name' => 'Updated OpenAI',
            'is_active' => false,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('display_name', 'Updated OpenAI')
            ->assertJsonPath('is_active', false);
    }

    public function test_can_update_api_key(): void
    {
        $user = User::factory()->create();
        $provider = UserAiProvider::factory()->openai()->for($user)->create();
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/v1/me/ai-providers/' . $provider->id, [
            'api_key' => 'sk-new-key-123456',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('has_key', true);

        $provider->refresh();
        $this->assertNotEquals($provider->getRawOriginal('api_key_encrypted'), encrypt('sk-new-key-123456'));
    }

    public function test_cannot_update_others_ai_provider(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $provider = UserAiProvider::factory()->openai()->for($owner)->create();
        Sanctum::actingAs($other);

        $response = $this->putJson('/api/v1/me/ai-providers/' . $provider->id, [
            'display_name' => 'Hacked',
        ]);

        $response->assertStatus(404);
    }

    public function test_authenticated_user_can_delete_ai_provider(): void
    {
        $user = User::factory()->create();
        $provider = UserAiProvider::factory()->openai()->for($user)->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson('/api/v1/me/ai-providers/' . $provider->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('user_ai_providers', ['id' => $provider->id]);
    }

    public function test_cannot_delete_others_ai_provider(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $provider = UserAiProvider::factory()->openai()->for($owner)->create();
        Sanctum::actingAs($other);

        $response = $this->deleteJson('/api/v1/me/ai-providers/' . $provider->id);

        $response->assertStatus(404);
    }

    public function test_authenticated_user_can_test_ai_provider_connection(): void
    {
        $user = User::factory()->create();
        $provider = UserAiProvider::factory()->openai()->for($user)->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/me/ai-providers/' . $provider->id . '/test');

        $response->assertStatus(200)
            ->assertJsonStructure(['ok', 'message', 'latency_ms']);
    }

    public function test_cannot_test_others_ai_provider(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $provider = UserAiProvider::factory()->openai()->for($owner)->create();
        Sanctum::actingAs($other);

        $response = $this->postJson('/api/v1/me/ai-providers/' . $provider->id . '/test');

        $response->assertStatus(404);
    }

    public function test_unauthenticated_user_cannot_test_ai_provider(): void
    {
        $user = User::factory()->create();
        $provider = UserAiProvider::factory()->openai()->for($user)->create();

        $response = $this->postJson('/api/v1/me/ai-providers/' . $provider->id . '/test');

        $response->assertStatus(401);
    }

    public function test_cannot_create_duplicate_provider_for_same_user(): void
    {
        $user = User::factory()->create();
        UserAiProvider::factory()->openai()->for($user)->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/me/ai-providers', [
            'provider' => 'openai',
            'base_url' => 'https://api.openai.com/v1',
        ]);

        $response->assertStatus(422);
    }

    public function test_user_can_have_multiple_different_providers(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson('/api/v1/me/ai-providers', [
            'provider' => 'openai',
            'base_url' => 'https://api.openai.com/v1',
        ])->assertStatus(201);

        $this->postJson('/api/v1/me/ai-providers', [
            'provider' => 'anthropic',
            'base_url' => 'https://api.anthropic.com',
        ])->assertStatus(201);

        $this->postJson('/api/v1/me/ai-providers', [
            'provider' => 'gemini',
            'base_url' => 'https://generativelanguage.googleapis.com/v1',
        ])->assertStatus(201);

        $response = $this->getJson('/api/v1/me/ai-providers');

        $response->assertJsonCount(3, 'data');
    }

    public function test_api_key_is_never_returned_in_response(): void
    {
        $user = User::factory()->create();
        $provider = UserAiProvider::factory()->openai()->for($user)->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/me/ai-providers/' . $provider->id);

        $response->assertStatus(200);
        $this->assertArrayNotHasKey('api_key', $response->json());
        $this->assertArrayNotHasKey('api_key_encrypted', $response->json());
    }
}