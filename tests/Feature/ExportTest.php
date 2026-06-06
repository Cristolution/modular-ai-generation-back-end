<?php

namespace Tests\Feature;

use App\Models\ExportJob;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_export_job(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/projects/' . $project->id . '/export', [
            'format' => 'pdf',
        ]);

        $response->assertStatus(202)
            ->assertJsonPath('format', 'pdf')
            ->assertJsonPath('status', 'pending')
            ->assertJsonPath('project_id', $project->id);

        $this->assertDatabaseHas('export_jobs', ['project_id' => $project->id, 'format' => 'pdf']);
    }

    public function test_can_create_export_job_with_options(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/projects/' . $project->id . '/export', [
            'format' => 'html',
            'options' => [
                'page_size' => 'A4',
                'quality' => 90,
            ],
        ]);

        $response->assertStatus(202)
            ->assertJsonPath('format', 'html')
            ->assertJsonPath('status', 'pending');
    }

    public function test_cannot_export_others_project(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $project = Project::factory()->for($owner)->create();
        Sanctum::actingAs($other);

        $response = $this->postJson('/api/v1/projects/' . $project->id . '/export', [
            'format' => 'pdf',
        ]);

        $response->assertStatus(403);
    }

    public function test_guest_cannot_create_export_job(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $response = $this->postJson('/api/v1/projects/' . $project->id . '/export', [
            'format' => 'pdf',
        ]);

        $response->assertStatus(401);
    }

    public function test_export_job_requires_valid_format(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/projects/' . $project->id . '/export', [
            'format' => 'invalid_format',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['format']);
    }

    public function test_can_poll_export_job(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $exportJob = ExportJob::factory()->for($project)->create(['status' => 'processing']);

        $response = $this->getJson('/api/v1/export-jobs/' . $exportJob->id);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'processing')
            ->assertJsonPath('id', $exportJob->id);
    }

    public function test_export_job_shows_download_url_when_ready(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $exportJob = ExportJob::factory()->for($project)->ready()->create();

        $response = $this->getJson('/api/v1/export-jobs/' . $exportJob->id);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'ready')
            ->assertJsonPath('download_url', $exportJob->download_url);
    }

    public function test_export_job_shows_error_when_failed(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $exportJob = ExportJob::factory()->for($project)->failed()->create();

        $response = $this->getJson('/api/v1/export-jobs/' . $exportJob->id);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'failed');
    }

    public function test_returns_404_for_nonexistent_export_job(): void
    {
        $response = $this->getJson('/api/v1/export-jobs/00000000-0000-0000-0000-000000000000');

        $response->assertStatus(404);
    }

    public function test_all_valid_formats_are_accepted(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        Sanctum::actingAs($user);

        $formats = ['html', 'pdf', 'png', 'jpg', 'pptx', 'zip', 'md'];

        foreach ($formats as $format) {
            $response = $this->postJson('/api/v1/projects/' . $project->id . '/export', [
                'format' => $format,
            ]);

            $response->assertStatus(202)
                ->assertJsonPath('format', $format);
        }
    }

    public function test_export_options_validation(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/projects/' . $project->id . '/export', [
            'format' => 'pdf',
            'options' => [
                'page_size' => 'invalid_size',
                'quality' => 200,
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['options.page_size', 'options.quality']);
    }
}