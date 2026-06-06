<?php

namespace Tests\Feature;

use App\Models\AiJob;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use App\Models\UserAiProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AiJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_list_jobs_for_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $provider = UserAiProvider::factory()->openai()->for($user)->create();

        AiJob::factory()->success()->forProject($project)->create(['provider_id' => $provider->id]);
        AiJob::factory()->pending()->forProject($project)->create(['provider_id' => $provider->id]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/projects/' . $project->id . '/jobs');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'project_id', 'provider', 'model', 'status', 'created_at'],
                ],
                'meta' => ['current_page', 'per_page', 'total', 'last_page'],
            ]);
    }

    public function test_guest_cannot_list_project_jobs(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $response = $this->getJson('/api/v1/projects/' . $project->id . '/jobs');

        $response->assertStatus(401);
    }

    public function test_user_cannot_list_jobs_for_others_project(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $project = Project::factory()->for($owner)->create();

        Sanctum::actingAs($other);

        $response = $this->getJson('/api/v1/projects/' . $project->id . '/jobs');

        $response->assertStatus(404);
    }

    public function test_authenticated_user_can_start_full_generation(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $provider = UserAiProvider::factory()->openai()->for($user)->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/projects/' . $project->id . '/generate', [
            'provider_id' => $provider->id,
            'prompt' => 'Generate a business pitch deck',
        ]);

        $response->assertStatus(202)
            ->assertJsonPath('status', 'pending')
            ->assertJsonPath('project_id', $project->id)
            ->assertJsonPath('provider', 'openai');

        $this->assertDatabaseHas('ai_jobs', [
            'project_id' => $project->id,
            'provider_id' => $provider->id,
            'status' => 'pending',
        ]);
    }

    public function test_cannot_generate_with_others_provider(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $project = Project::factory()->for($owner)->create();
        $provider = UserAiProvider::factory()->openai()->for($other)->create();

        Sanctum::actingAs($owner);

        $response = $this->postJson('/api/v1/projects/' . $project->id . '/generate', [
            'provider_id' => $provider->id,
        ]);

        // Provider not found in user's list - returns 404
        $response->assertStatus(404);
    }

    public function test_authenticated_user_can_start_layer_generation(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $file = File::factory()->for($project)->create(['layer' => 'slide']);
        $provider = UserAiProvider::factory()->openai()->for($user)->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/projects/' . $project->id . '/files/' . $file->id . '/generate', [
            'provider_id' => $provider->id,
        ]);

        $response->assertStatus(202)
            ->assertJsonPath('status', 'pending')
            ->assertJsonPath('file_id', $file->id)
            ->assertJsonPath('layer', 'slide');
    }

    public function test_cannot_generate_layer_for_others_file(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $project = Project::factory()->for($owner)->create();
        $file = File::factory()->for($project)->create();
        $provider = UserAiProvider::factory()->openai()->for($other)->create();

        Sanctum::actingAs($owner);

        $response = $this->postJson('/api/v1/projects/' . $project->id . '/files/' . $file->id . '/generate', [
            'provider_id' => $provider->id,
        ]);

        // Provider not found in user's list - returns 404
        $response->assertStatus(404);
    }

    public function test_guest_cannot_start_generation(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $provider = UserAiProvider::factory()->openai()->for($user)->create();

        $response = $this->postJson('/api/v1/projects/' . $project->id . '/generate', [
            'provider_id' => $provider->id,
        ]);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_poll_job_status(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $provider = UserAiProvider::factory()->openai()->for($user)->create();
        $job = AiJob::factory()->success()->forProject($project)->create(['provider_id' => $provider->id]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/jobs/' . $job->id);

        $response->assertStatus(200)
            ->assertJsonPath('id', $job->id)
            ->assertJsonPath('status', 'success');
    }

    public function test_user_cannot_poll_others_job(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $project = Project::factory()->for($owner)->create();
        $provider = UserAiProvider::factory()->openai()->for($owner)->create();
        $job = AiJob::factory()->forProject($project)->create(['provider_id' => $provider->id]);

        Sanctum::actingAs($other);

        $response = $this->getJson('/api/v1/jobs/' . $job->id);

        $response->assertStatus(404);
    }

    public function test_guest_cannot_poll_job(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $provider = UserAiProvider::factory()->openai()->for($user)->create();
        $job = AiJob::factory()->forProject($project)->create(['provider_id' => $provider->id]);

        $response = $this->getJson('/api/v1/jobs/' . $job->id);

        $response->assertStatus(401);
    }

    public function test_generation_with_model_override(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $provider = UserAiProvider::factory()->openai()->for($user)->create(['default_model' => 'gpt-4o']);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/projects/' . $project->id . '/generate', [
            'provider_id' => $provider->id,
            'model' => 'gpt-4-turbo',
        ]);

        $response->assertStatus(202)
            ->assertJsonPath('model', 'gpt-4-turbo');
    }

    public function test_jobs_list_is_paginated(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $provider = UserAiProvider::factory()->openai()->for($user)->create();

        AiJob::factory()->success()->forProject($project)->create(['provider_id' => $provider->id]);
        AiJob::factory()->success()->forProject($project)->create(['provider_id' => $provider->id]);
        AiJob::factory()->success()->forProject($project)->create(['provider_id' => $provider->id]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/projects/' . $project->id . '/jobs?per_page=2');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure(['meta' => ['current_page', 'per_page', 'total', 'last_page']]);
    }

    public function test_can_list_jobs_for_template_based_project(): void
    {
        $user = User::factory()->create();
        $template = \App\Models\Template::factory()->for($user)->create();
        $project = Project::factory()->for($user)->for($template, 'template')->create();
        $provider = UserAiProvider::factory()->openai()->for($user)->create();

        AiJob::factory()->success()->forProject($project)->create([
            'provider_id' => $provider->id,
            'template_id' => $template->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/projects/' . $project->id . '/jobs');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.template_id', $template->id);
    }

    public function test_cannot_generate_with_invalid_provider_id(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/projects/' . $project->id . '/generate', [
            'provider_id' => 'non-existent-uuid',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['provider_id']);
    }

    public function test_ai_job_belongs_to_correct_relationships(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $file = File::factory()->for($project)->create();
        $provider = UserAiProvider::factory()->openai()->for($user)->create();

        $job = AiJob::factory()->create([
            'project_id' => $project->id,
            'file_id' => $file->id,
            'provider_id' => $provider->id,
            'triggered_by' => $user->id,
            'provider' => $provider->provider,
        ]);

        $this->assertEquals($project->id, $job->project->id);
        $this->assertEquals($file->id, $job->file->id);
        $this->assertEquals($provider->id, $job->aiProvider->id);
    }
}