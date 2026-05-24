<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_public_resources(): void
    {
        Resource::factory()->public()->create();
        Resource::factory()->public()->create();
        Resource::factory()->private()->create();

        $response = $this->getJson('/api/v1/resources');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'user_id', 'kind', 'name', 'description', 'visibility', 'tags'],
                ],
            ]);
    }

    public function test_resources_list_is_paginated(): void
    {
        Resource::factory()->public()->count(25)->create();

        $response = $this->getJson('/api/v1/resources?per_page=10');

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure(['meta' => ['current_page', 'per_page', 'total', 'last_page']]);
    }

    public function test_can_filter_resources_by_kind(): void
    {
        Resource::factory()->public()->create(['kind' => 'prompt']);
        Resource::factory()->public()->create(['kind' => 'skill']);

        $response = $this->getJson('/api/v1/resources?kind=prompt');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_search_resources_by_keyword(): void
    {
        Resource::factory()->public()->create(['name' => 'Business Prompt']);
        Resource::factory()->public()->create(['name' => 'Creative Skill']);

        $response = $this->getJson('/api/v1/resources?q=Business');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_view_single_public_resource(): void
    {
        $resource = Resource::factory()->public()->create(['name' => 'Test Resource']);

        $response = $this->getJson('/api/v1/resources/' . $resource->id);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Test Resource');
    }

    public function test_cannot_view_private_resource_without_auth(): void
    {
        $resource = Resource::factory()->private()->create();

        $response = $this->getJson('/api/v1/resources/' . $resource->id);

        $response->assertStatus(403);
    }

    public function test_owner_can_view_own_private_resource(): void
    {
        $user = User::factory()->create();
        $resource = Resource::factory()->private()->for($user)->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/resources/' . $resource->id);

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_create_resource(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/resources', [
            'kind' => 'prompt',
            'name' => 'My Prompt',
            'content' => 'You are a helpful assistant. {{task}}',
            'visibility' => 'public',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'My Prompt')
            ->assertJsonPath('data.kind', 'prompt');

        $this->assertDatabaseHas('resources', ['name' => 'My Prompt']);
    }

    public function test_cannot_create_resource_with_invalid_kind(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/resources', [
            'kind' => 'invalid_kind',
            'name' => 'Test',
            'content' => 'Content',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['kind']);
    }

    public function test_owner_can_update_own_resource(): void
    {
        $user = User::factory()->create();
        $resource = Resource::factory()->for($user)->create(['name' => 'Original']);
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/v1/resources/' . $resource->id, [
            'name' => 'Updated',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Updated');
    }

    public function test_cannot_update_others_resource(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $resource = Resource::factory()->for($owner)->create();
        Sanctum::actingAs($other);

        $response = $this->putJson('/api/v1/resources/' . $resource->id, [
            'name' => 'Hacked',
        ]);

        $response->assertStatus(403);
    }

    public function test_owner_can_delete_own_resource(): void
    {
        $user = User::factory()->create();
        $resource = Resource::factory()->for($user)->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson('/api/v1/resources/' . $resource->id);

        $response->assertStatus(204);
        $this->assertSoftDeleted('resources', ['id' => $resource->id]);
    }

    public function test_can_fork_public_resource(): void
    {
        $user = User::factory()->create();
        $original = Resource::factory()->public()->create(['name' => 'Original Resource']);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/resources/' . $original->id . '/fork', [
            'name' => 'My Fork',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'My Fork')
            ->assertJsonPath('data.forked_from_id', $original->id);
    }

    public function test_cannot_fork_private_resource_without_access(): void
    {
        $user = User::factory()->create();
        $original = Resource::factory()->private()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/resources/' . $original->id . '/fork');

        $response->assertStatus(403);
    }

    public function test_can_list_forks_of_resource(): void
    {
        $original = Resource::factory()->public()->create();
        $fork1 = Resource::factory()->public()->for($original, 'forkedFrom')->create();
        $fork2 = Resource::factory()->public()->for($original, 'forkedFrom')->create();

        $response = $this->getJson('/api/v1/resources/' . $original->id . '/forks');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_can_upvote_resource(): void
    {
        $user = User::factory()->create();
        $resource = Resource::factory()->public()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/resources/' . $resource->id . '/upvote');

        $response->assertStatus(200)
            ->assertJson(['upvoted' => true]);
    }

    public function test_can_toggle_upvote_off(): void
    {
        $user = User::factory()->create();
        $resource = Resource::factory()->public()->create();
        Sanctum::actingAs($user);

        $this->postJson('/api/v1/resources/' . $resource->id . '/upvote');

        $response = $this->postJson('/api/v1/resources/' . $resource->id . '/upvote');

        $response->assertStatus(200)
            ->assertJson(['upvoted' => false]);
    }

    public function test_can_bookmark_resource(): void
    {
        $user = User::factory()->create();
        $resource = Resource::factory()->public()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/resources/' . $resource->id . '/bookmark');

        $response->assertStatus(200)
            ->assertJson(['bookmarked' => true]);
    }

    public function test_authenticated_user_can_comment_on_resource(): void
    {
        $user = User::factory()->create();
        $resource = Resource::factory()->public()->for($user)->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/resources/' . $resource->id . '/comments', [
            'body' => 'This is a great resource!',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.body', 'This is a great resource!');

        $this->assertDatabaseHas('comments', ['body' => 'This is a great resource!']);
    }

    public function test_can_list_comments_on_resource(): void
    {
        $resource = Resource::factory()->public()->create();
        $user = User::factory()->create();

        Comment::factory()->forResource($resource)->create(['user_id' => $user->id]);
        Comment::factory()->forResource($resource)->create(['user_id' => $user->id]);
        Comment::factory()->forResource($resource)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/v1/resources/' . $resource->id . '/comments');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_guest_cannot_create_resource(): void
    {
        $response = $this->postJson('/api/v1/resources', [
            'kind' => 'prompt',
            'name' => 'Test',
            'content' => 'Content',
        ]);

        $response->assertStatus(401);
    }

    public function test_guest_cannot_upvote_resource(): void
    {
        $resource = Resource::factory()->public()->create();

        $response = $this->postJson('/api/v1/resources/' . $resource->id . '/upvote');

        $response->assertStatus(401);
    }

    public function test_resource_with_placeholders(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/resources', [
            'kind' => 'prompt',
            'name' => 'Task Prompt',
            'content' => 'Complete the following task: {{task}}',
            'placeholders' => [
                ['key' => 'task', 'label' => 'Task Description', 'default' => '', 'type' => 'textarea'],
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.placeholders.0.key', 'task');
    }
}