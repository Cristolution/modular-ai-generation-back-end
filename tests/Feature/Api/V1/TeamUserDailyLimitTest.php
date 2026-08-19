<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Tests for the TeamUserDailyLimit middleware that caps the six
 * TeamSeeder accounts (crist/issa/aya/abdallah/joudy/sally@example.com)
 * at 10 AI requests per day.
 *
 * Strategy: hit /api/v1/ai/chat with a body that the controller will
 * reject with 422 (missing provider_id). The middleware still counts
 * the attempt because it runs before validation, so the test exercises
 * the limit without any upstream mocking.
 */
class TeamUserDailyLimitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // The rate limiter is cache-backed. RefreshDatabase only resets
        // the DB — without a cache flush, counters bleed between tests.
        Cache::flush();
    }

    public function test_team_user_is_blocked_after_10_requests_in_a_day(): void
    {
        $user = User::factory()->create([
            'name'  => 'Crist',
            'email' => 'crist@example.com',
        ]);
        Sanctum::actingAs($user);

        // Body intentionally missing provider_id — the controller returns
        // 422 for each, but the middleware still increments the counter
        // because it runs before validation.
        $payload = [
            'model'    => 'MiniMax-M3',
            'messages' => [['role' => 'user', 'content' => 'hi']],
        ];

        // First 10 requests pass the middleware (the controller decides
        // their final status; we don't care about that here).
        for ($i = 1; $i <= 10; $i++) {
            $this->postJson('/api/v1/ai/chat', $payload);
        }

        // 11th request is short-circuited by the middleware at 429.
        $response = $this->postJson('/api/v1/ai/chat', $payload);

        $response->assertStatus(429)
            ->assertJson(['code' => 'daily_limit_exceeded'])
            ->assertHeader('Retry-After');

        $this->assertGreaterThan(0, (int) $response->headers->get('Retry-After'));
    }

    public function test_non_team_user_is_not_rate_limited(): void
    {
        $user = User::factory()->create([
            'email' => 'someone-else@example.com',
        ]);
        Sanctum::actingAs($user);

        $payload = [
            'model'    => 'MiniMax-M3',
            'messages' => [['role' => 'user', 'content' => 'hi']],
        ];

        // 15 in a row — none of these should ever be 429.
        for ($i = 1; $i <= 15; $i++) {
            $response = $this->postJson('/api/v1/ai/chat', $payload);
            $this->assertNotEquals(
                429,
                $response->status(),
                "Request {$i} should not be rate-limited for a non-team user."
            );
        }
    }

    public function test_each_team_user_has_their_own_counter(): void
    {
        $crist = User::factory()->create(['email' => 'crist@example.com']);
        $issa  = User::factory()->create(['email' => 'issa@example.com']);

        $payload = [
            'model'    => 'MiniMax-M3',
            'messages' => [['role' => 'user', 'content' => 'hi']],
        ];

        // Crist exhausts their daily budget.
        Sanctum::actingAs($crist);
        for ($i = 1; $i <= 10; $i++) {
            $this->postJson('/api/v1/ai/chat', $payload);
        }
        $this->postJson('/api/v1/ai/chat', $payload)->assertStatus(429);

        // Issa's counter is independent — first request must not be 429.
        Sanctum::actingAs($issa);
        $response = $this->postJson('/api/v1/ai/chat', $payload);
        $this->assertNotEquals(
            429,
            $response->status(),
            'Issa should not be blocked by Crist exhausting his own counter.'
        );
    }

    public function test_limit_also_applies_to_the_generate_routes(): void
    {
        $user = User::factory()->create([
            'email' => 'crist@example.com',
        ]);
        Sanctum::actingAs($user);

        $payload = [
            'model'    => 'MiniMax-M3',
            'messages' => [['role' => 'user', 'content' => 'hi']],
        ];

        // 5 to /ai/chat, 5 to /generate — the same user, same day, the
        // counter is per-user so it should be shared across endpoints.
        for ($i = 1; $i <= 5; $i++) {
            $this->postJson('/api/v1/ai/chat', $payload);
        }
        for ($i = 1; $i <= 5; $i++) {
            $this->postJson('/api/v1/projects/00000000-0000-0000-0000-000000000000/generate', $payload);
        }

        // 11th request on either endpoint must be 429.
        $this->postJson('/api/v1/ai/chat', $payload)->assertStatus(429);
        $this->postJson('/api/v1/projects/00000000-0000-0000-0000-000000000000/generate', $payload)
            ->assertStatus(429);
    }
}
