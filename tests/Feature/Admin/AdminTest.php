<?php

namespace Tests\Feature\Admin;

use App\Models\Resource;
use App\Models\Template;
use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Coverage for the four /admin/* endpoints introduced alongside the
 * `Admin` tag in openapi_api_contract_working.yaml. Every endpoint must:
 *   - 401 when unauthenticated (sanctum short-circuits before admin guard)
 *   - 403 when authenticated but role != 'admin'
 *   - 200 + correct payload when role == 'admin'
 */
class AdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $regular;
    private string $adminToken;
    private string $userToken;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a real admin and a real user. Use the model's update()
        // so we go through the cast layer cleanly without mutating the
        // factory (the factory always sets role=user).
        $this->admin = User::factory()->create();
        $this->admin->update(['role' => 'admin']);
        $this->adminToken = $this->admin->createToken('auth_token')->plainTextToken;

        $this->regular = User::factory()->create();
        $this->userToken = $this->regular->createToken('auth_token')->plainTextToken;
    }

    // ─────────────────────────────────────────────────────────────────
    // GET /admin/users
    // ─────────────────────────────────────────────────────────────────

    public function test_admin_users_index_requires_authentication(): void
    {
        $this->getJson('/api/v1/admin/users')
            ->assertStatus(401);
    }

    public function test_admin_users_index_forbidden_for_non_admin(): void
    {
        $this->withHeader('Authorization', 'Bearer '.$this->userToken)
            ->getJson('/api/v1/admin/users')
            ->assertStatus(403)
            ->assertJson(['code' => 'forbidden']);
    }

    public function test_admin_users_index_returns_paginated_list(): void
    {
        User::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
            ->getJson('/api/v1/admin/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'email', 'role', 'created_at'],
                ],
                'links',
                'meta' => ['current_page', 'per_page', 'total'],
            ]);

        // 1 admin + 3 fresh + 1 regular = 5 total
        $this->assertSame(5, $response->json('meta.total'));
    }

    public function test_admin_users_index_supports_q_keyword_search(): void
    {
        User::factory()->create(['name' => 'Alice Wonder']);
        User::factory()->create(['name' => 'Bob Builder', 'email' => 'bob.builder@example.com']);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
            ->getJson('/api/v1/admin/users?q=Bob');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertSame('Bob Builder', $response->json('data.0.name'));
    }

    // ─────────────────────────────────────────────────────────────────
    // PUT /admin/users/{user_id}
    // ─────────────────────────────────────────────────────────────────

    public function test_admin_can_promote_user_to_admin(): void
    {
        $target = User::factory()->create(['role' => 'user']);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
            ->putJson("/api/v1/admin/users/{$target->id}", ['role' => 'admin']);

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $target->id)
            ->assertJsonPath('data.role', 'admin');

        $this->assertDatabaseHas('users', ['id' => $target->id, 'role' => 'admin']);
    }

    public function test_admin_can_demote_admin_back_to_user(): void
    {
        $target = User::factory()->create();
        $target->update(['role' => 'admin']);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
            ->putJson("/api/v1/admin/users/{$target->id}", ['role' => 'user']);

        $response->assertStatus(200)
            ->assertJsonPath('data.role', 'user');

        $this->assertDatabaseHas('users', ['id' => $target->id, 'role' => 'user']);
    }

    public function test_admin_role_update_rejects_invalid_role_value(): void
    {
        $target = User::factory()->create();

        $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
            ->putJson("/api/v1/admin/users/{$target->id}", ['role' => 'superuser'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['role']);
    }

    public function test_admin_role_update_requires_role_field(): void
    {
        $target = User::factory()->create();

        $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
            ->putJson("/api/v1/admin/users/{$target->id}", [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['role']);
    }

    public function test_admin_role_update_404_for_missing_user(): void
    {
        $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
            ->putJson('/api/v1/admin/users/00000000-0000-0000-0000-000000000000', ['role' => 'admin'])
            ->assertStatus(404);
    }

    public function test_non_admin_cannot_update_user_role(): void
    {
        $target = User::factory()->create(['role' => 'user']);

        $this->withHeader('Authorization', 'Bearer '.$this->userToken)
            ->putJson("/api/v1/admin/users/{$target->id}", ['role' => 'admin'])
            ->assertStatus(403);

        // Role should NOT have changed.
        $this->assertDatabaseHas('users', ['id' => $target->id, 'role' => 'user']);
    }

    // ─────────────────────────────────────────────────────────────────
    // GET /admin/templates
    // ─────────────────────────────────────────────────────────────────

    public function test_admin_templates_index_includes_private_and_unlisted(): void
    {
        $type = Type::firstOrCreate(['name' => 'presentation'], [
            'icon' => 'slides',
        ]);

        Template::factory()->create(['visibility' => 'public']);
        Template::factory()->create(['visibility' => 'private']);
        Template::factory()->create(['visibility' => 'unlisted']);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
            ->getJson('/api/v1/admin/templates');

        $response->assertStatus(200);
        $this->assertSame(3, $response->json('meta.total'));
    }

    public function test_admin_templates_index_requires_authentication(): void
    {
        $this->getJson('/api/v1/admin/templates')->assertStatus(401);
    }

    public function test_admin_templates_index_forbidden_for_non_admin(): void
    {
        $this->withHeader('Authorization', 'Bearer '.$this->userToken)
            ->getJson('/api/v1/admin/templates')
            ->assertStatus(403);
    }

    // ─────────────────────────────────────────────────────────────────
    // GET /admin/resources
    // ─────────────────────────────────────────────────────────────────

    public function test_admin_resources_index_includes_private_and_unlisted(): void
    {
        Resource::factory()->create(['visibility' => 'public', 'kind' => 'prompt']);
        Resource::factory()->create(['visibility' => 'private', 'kind' => 'prompt']);
        Resource::factory()->create(['visibility' => 'unlisted', 'kind' => 'prompt']);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
            ->getJson('/api/v1/admin/resources');

        $response->assertStatus(200);
        $this->assertSame(3, $response->json('meta.total'));
    }

    public function test_admin_resources_index_requires_authentication(): void
    {
        $this->getJson('/api/v1/admin/resources')->assertStatus(401);
    }

    public function test_admin_resources_index_forbidden_for_non_admin(): void
    {
        $this->withHeader('Authorization', 'Bearer '.$this->userToken)
            ->getJson('/api/v1/admin/resources')
            ->assertStatus(403);
    }
}