<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\Template;
use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TemplateTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_public_templates(): void
    {
        Template::factory()->public()->create();
        Template::factory()->public()->create();
        Template::factory()->private()->create();

        $response = $this->getJson('/api/v1/templates');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'user_id', 'name', 'description', 'visibility', 'tags', 'locale', 'direction', 'fork_count', 'upvote_count'],
                ],
            ]);
    }

    public function test_templates_list_is_paginated(): void
    {
        $this->seed(['TypeSeeder']);
        Template::factory()->public()->count(25)->create();

        $response = $this->getJson('/api/v1/templates?per_page=10');

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure(['meta' => ['current_page', 'per_page', 'total', 'last_page']]);
    }

    public function test_can_filter_templates_by_type(): void
    {
        $type = Type::factory()->create(['name' => 'presentation']);
        Template::factory()->public()->create(['type_id' => $type->id]);

        $response = $this->getJson('/api/v1/templates?type_id=' . $type->id);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_search_templates_by_keyword(): void
    {
        Template::factory()->public()->create(['name' => 'Business Pitch']);
        Template::factory()->public()->create(['name' => 'Creative Portfolio']);

        $response = $this->getJson('/api/v1/templates?q=Business');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_view_single_template(): void
    {
        $template = Template::factory()->public()->create(['name' => 'Test Template']);

        $response = $this->getJson('/api/v1/templates/' . $template->id);

        $response->assertStatus(200)
            ->assertJsonPath('name', 'Test Template');
    }

    public function test_cannot_view_private_template_without_auth(): void
    {
        $template = Template::factory()->private()->create();

        $response = $this->getJson('/api/v1/templates/' . $template->id);

        $response->assertStatus(403);
    }

    public function test_owner_can_view_own_private_template(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->private()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/templates/' . $template->id);

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_create_template(): void
    {
        $user = User::factory()->create();
        $type = Type::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/templates', [
            'type_id' => $type->id,
            'name' => 'My Template',
            'visibility' => 'public',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('name', 'My Template');

        $this->assertDatabaseHas('templates', ['name' => 'My Template']);
    }

    public function test_guest_cannot_create_template(): void
    {
        $type = Type::factory()->create();

        $response = $this->postJson('/api/v1/templates', [
            'type_id' => $type->id,
            'name' => 'My Template',
        ]);

        $response->assertStatus(401);
    }

    public function test_owner_can_update_own_template(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create(['user_id' => $user->id, 'name' => 'Original Name']);
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/v1/templates/' . $template->id, [
            'name' => 'Updated Name',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('name', 'Updated Name');
    }

    public function test_cannot_update_others_template(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $template = Template::factory()->create(['user_id' => $owner->id]);
        Sanctum::actingAs($other);

        $response = $this->putJson('/api/v1/templates/' . $template->id, [
            'name' => 'Hacked',
        ]);

        $response->assertStatus(403);
    }

    public function test_owner_can_delete_own_template(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->deleteJson('/api/v1/templates/' . $template->id);

        $response->assertStatus(204);
        $this->assertSoftDeleted('templates', ['id' => $template->id]);
    }

    public function test_can_fork_public_template(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->public()->create();
        File::factory()->create(['template_id' => $template->id, 'user_id' => $template->user_id]);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/templates/' . $template->id . '/fork', [
            'name' => 'My Fork',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('name', 'My Fork')
            ->assertJsonPath('template_id', $template->id);

        $this->assertDatabaseHas('projects', ['name' => 'My Fork']);
    }

    public function test_cannot_fork_private_template_without_access(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->private()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/templates/' . $template->id . '/fork', [
            'name' => 'My Fork',
        ]);

        $response->assertStatus(403);
    }

    public function test_can_list_template_files(): void
    {
        $template = Template::factory()->public()->create();
        File::factory()->count(3)->create(['template_id' => $template->id]);

        $response = $this->getJson('/api/v1/templates/' . $template->id . '/files');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_add_file_to_template(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/templates/' . $template->id . '/files', [
            'layer' => 'slide',
            'name' => 'slide-01.html',
            'extension' => 'html',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('name', 'slide-01.html');
    }

    public function test_can_upvote_template(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->public()->create(['upvote_count' => 5]);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/templates/' . $template->id . '/upvote');

        $response->assertStatus(200)
            ->assertJson(['upvoted' => true, 'upvote_count' => 6]);

        $template->refresh();
        $this->assertEquals(6, $template->upvote_count);
    }

    public function test_can_toggle_upvote_off(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->public()->create(['upvote_count' => 0]);
        Sanctum::actingAs($user);

        $this->postJson('/api/v1/templates/' . $template->id . '/upvote');

        $response = $this->postJson('/api/v1/templates/' . $template->id . '/upvote');

        $response->assertStatus(200)
            ->assertJson(['upvoted' => false, 'upvote_count' => 0]);
    }

    public function test_can_bookmark_template(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->public()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/templates/' . $template->id . '/bookmark');

        $response->assertStatus(200)
            ->assertJson(['bookmarked' => true]);
    }
}
