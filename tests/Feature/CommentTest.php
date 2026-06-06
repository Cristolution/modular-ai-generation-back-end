<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Resource;
use App\Models\Template;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_comment_on_template(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->public()->for($user)->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/templates/' . $template->id . '/comments', [
            'body' => 'Great template!',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('body', 'Great template!');

        $this->assertDatabaseHas('comments', ['body' => 'Great template!']);
    }

    public function test_can_list_comments_on_template(): void
    {
        $template = Template::factory()->public()->create();
        $user = User::factory()->create();

        Comment::factory()->forTemplate($template)->create(['user_id' => $user->id]);
        Comment::factory()->forTemplate($template)->create(['user_id' => $user->id]);
        Comment::factory()->forTemplate($template)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/v1/templates/' . $template->id . '/comments');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_reply_to_comment(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->public()->for($user)->create();
        $parentComment = Comment::factory()->forTemplate($template)->for($user)->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/templates/' . $template->id . '/comments', [
            'body' => 'This is a reply',
            'parent_id' => $parentComment->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('parent_id', $parentComment->id);
    }

    public function test_cannot_reply_to_nonexistent_parent(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->public()->for($user)->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/templates/' . $template->id . '/comments', [
            'body' => 'Reply',
            'parent_id' => '00000000-0000-0000-0000-000000000000',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['parent_id']);
    }

    public function test_owner_can_edit_own_comment(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->public()->for($user)->create();
        $comment = Comment::factory()->forTemplate($template)->for($user)->create(['body' => 'Original']);
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/v1/comments/' . $comment->id, [
            'body' => 'Updated comment',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('body', 'Updated comment');
    }

    public function test_cannot_edit_others_comment(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $template = Template::factory()->public()->for($owner)->create();
        $comment = Comment::factory()->forTemplate($template)->for($owner)->create();
        Sanctum::actingAs($other);

        $response = $this->putJson('/api/v1/comments/' . $comment->id, [
            'body' => 'Hacked',
        ]);

        $response->assertStatus(403);
    }

    public function test_owner_can_delete_own_comment(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->public()->for($user)->create();
        $comment = Comment::factory()->forTemplate($template)->for($user)->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson('/api/v1/comments/' . $comment->id);

        $response->assertStatus(204);
        $this->assertSoftDeleted('comments', ['id' => $comment->id]);
    }

    public function test_cannot_delete_others_comment(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $template = Template::factory()->public()->for($owner)->create();
        $comment = Comment::factory()->forTemplate($template)->for($owner)->create();
        Sanctum::actingAs($other);

        $response = $this->deleteJson('/api/v1/comments/' . $comment->id);

        $response->assertStatus(403);
    }

    public function test_guest_cannot_comment_on_template(): void
    {
        $template = Template::factory()->public()->create();

        $response = $this->postJson('/api/v1/templates/' . $template->id . '/comments', [
            'body' => 'Comment',
        ]);

        $response->assertStatus(401);
    }

    public function test_guest_cannot_edit_comment(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->public()->for($user)->create();
        $comment = Comment::factory()->forTemplate($template)->for($user)->create();

        $response = $this->putJson('/api/v1/comments/' . $comment->id, [
            'body' => 'Edited',
        ]);

        $response->assertStatus(401);
    }

    public function test_guest_cannot_delete_comment(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->public()->for($user)->create();
        $comment = Comment::factory()->forTemplate($template)->for($user)->create();

        $response = $this->deleteJson('/api/v1/comments/' . $comment->id);

        $response->assertStatus(401);
    }

    public function test_comment_body_is_required(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->public()->for($user)->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/templates/' . $template->id . '/comments', [
            'body' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['body']);
    }

    public function test_can_comment_on_resource(): void
    {
        $user = User::factory()->create();
        $resource = Resource::factory()->public()->for($user)->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/resources/' . $resource->id . '/comments', [
            'body' => 'Great resource!',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('body', 'Great resource!');
    }

    public function test_comments_on_private_resource_require_ownership(): void
    {
        $owner = User::factory()->create();
        $resource = Resource::factory()->private()->for($owner)->create();
        $other = User::factory()->create();

        Sanctum::actingAs($other);

        $response = $this->postJson('/api/v1/resources/' . $resource->id . '/comments', [
            'body' => 'Comment',
        ]);

        $response->assertStatus(403);
    }
}