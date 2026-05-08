<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\Project;
use App\Models\Template;
use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_own_projects(): void
    {
        $user = User::factory()->create();
        Project::factory()->create(['user_id' => $user->id]);
        Project::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/projects');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_guest_cannot_list_projects(): void
    {
        $response = $this->getJson('/api/v1/projects');

        $response->assertStatus(401);
    }

    public function test_can_create_project(): void
    {
        $user = User::factory()->create();
        $type = Type::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/projects', [
            'type_id' => $type->id,
            'name' => 'My Project',
            'visibility' => 'private',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'My Project');

        $this->assertDatabaseHas('projects', ['name' => 'My Project']);
    }

    public function test_can_view_own_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/projects/' . $project->id);

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $project->id);
    }

    public function test_cannot_view_others_project(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $project = Project::factory()->private()->create(['user_id' => $owner->id]);
        Sanctum::actingAs($other);

        $response = $this->getJson('/api/v1/projects/' . $project->id);

        $response->assertStatus(403);
    }

    public function test_can_update_own_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/v1/projects/' . $project->id, [
            'name' => 'Updated Name',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Updated Name');
    }

    public function test_can_delete_own_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->deleteJson('/api/v1/projects/' . $project->id);

        $response->assertStatus(204);
        $this->assertSoftDeleted('projects', ['id' => $project->id]);
    }

    public function test_can_list_project_files(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        File::factory()->count(3)->create(['project_id' => $project->id, 'user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/projects/' . $project->id . '/files');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_add_file_to_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/projects/' . $project->id . '/files', [
            'layer' => 'slide',
            'name' => 'slide-01.html',
            'extension' => 'html',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'slide-01.html');
    }

    public function test_can_update_project_file(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $file = File::factory()->create(['project_id' => $project->id, 'user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->putJson("/api/v1/projects/{$project->id}/files/{$file->id}", [
            'content' => '<div>Updated</div>',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.content', '<div>Updated</div>');
    }

    public function test_can_delete_project_file(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $file = File::factory()->create(['project_id' => $project->id, 'user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/v1/projects/{$project->id}/files/{$file->id}");

        $response->assertStatus(204);
    }

    public function test_can_reorder_project_files(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $file1 = File::factory()->create(['project_id' => $project->id, 'user_id' => $user->id, 'sort_order' => 0]);
        $file2 = File::factory()->create(['project_id' => $project->id, 'user_id' => $user->id, 'sort_order' => 1]);
        Sanctum::actingAs($user);

        $response = $this->patchJson("/api/v1/projects/{$project->id}/files/reorder", [
            'order' => [$file2->id, $file1->id],
        ]);

        $response->assertStatus(200);
    }
}
