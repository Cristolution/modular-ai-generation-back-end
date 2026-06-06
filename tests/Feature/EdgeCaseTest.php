<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\Project;
use App\Models\Template;
use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EdgeCaseTest extends TestCase
{
    use RefreshDatabase;

    // ═══════════════════════════════════════════════════════════════════════════
    // AUTH VALIDATION EDGE CASES
    // ═══════════════════════════════════════════════════════════════════════════

    public function test_registration_fails_with_mismatched_passwords(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertStatus(422);
    }

    public function test_registration_fails_with_duplicate_email(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Test User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422);
    }

    public function test_registration_fails_with_short_password(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertStatus(422);
    }

    public function test_registration_fails_with_missing_required_fields(): void
    {
        $response = $this->postJson('/api/v1/auth/register', []);

        $response->assertStatus(422)
            ->assertJsonStructure(['errors' => ['name', 'email', 'password']]);
    }

    public function test_login_fails_with_nonexistent_email(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(422);
    }

    public function test_login_fails_with_invalid_email_format(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'not-an-email',
            'password' => 'password123',
        ]);

        $response->assertStatus(422);
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // TEMPLATE VALIDATION EDGE CASES
    // ═══════════════════════════════════════════════════════════════════════════

    public function test_cannot_create_template_with_invalid_type_id(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/templates', [
            'type_id' => 'nonexistent-uuid',
            'name' => 'Test Template',
        ]);

        $response->assertStatus(422);
    }

    public function test_cannot_create_template_with_empty_name(): void
    {
        $user = User::factory()->create();
        $type = Type::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/templates', [
            'type_id' => $type->id,
            'name' => '',
        ]);

        $response->assertStatus(422);
    }

    public function test_cannot_create_template_with_name_exceeding_max_length(): void
    {
        $user = User::factory()->create();
        $type = Type::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/templates', [
            'type_id' => $type->id,
            'name' => str_repeat('a', 256),
        ]);

        $response->assertStatus(422);
    }

    public function test_cannot_create_template_with_invalid_visibility(): void
    {
        $user = User::factory()->create();
        $type = Type::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/templates', [
            'type_id' => $type->id,
            'name' => 'Test',
            'visibility' => 'invalid',
        ]);

        $response->assertStatus(422);
    }

    public function test_cannot_create_template_with_invalid_locale(): void
    {
        $user = User::factory()->create();
        $type = Type::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/templates', [
            'type_id' => $type->id,
            'name' => 'Test',
            'locale' => 'invalid_locale',
        ]);

        $response->assertStatus(422);
    }

    public function test_cannot_create_template_with_invalid_direction(): void
    {
        $user = User::factory()->create();
        $type = Type::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/templates', [
            'type_id' => $type->id,
            'name' => 'Test',
            'direction' => 'invalid',
        ]);

        $response->assertStatus(422);
    }

    public function test_cannot_create_template_with_invalid_tags(): void
    {
        $user = User::factory()->create();
        $type = Type::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/templates', [
            'type_id' => $type->id,
            'name' => 'Test',
            'tags' => [123, 456],
        ]);

        $response->assertStatus(422);
    }

    public function test_can_create_template_with_all_optional_fields(): void
    {
        $user = User::factory()->create();
        $type = Type::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/templates', [
            'type_id' => $type->id,
            'name' => 'Full Template',
            'description' => 'A detailed description',
            'visibility' => 'unlisted',
            'tags' => ['business', 'minimal', 'dark'],
            'locale' => 'ar',
            'direction' => 'rtl',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('visibility', 'unlisted')
            ->assertJsonPath('locale', 'ar')
            ->assertJsonPath('direction', 'rtl');
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // TEMPLATE SORTING & FILTERING EDGE CASES
    // ═══════════════════════════════════════════════════════════════════════════

    public function test_templates_default_sort_is_popular(): void
    {
        $t1 = Template::factory()->public()->create(['upvote_count' => 10]);
        $t2 = Template::factory()->public()->create(['upvote_count' => 50]);
        $t3 = Template::factory()->public()->create(['upvote_count' => 25]);

        $response = $this->getJson('/api/v1/templates');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertEquals($t2->id, $data[0]['id']); // highest upvotes first
    }

    public function test_can_sort_templates_by_newest(): void
    {
        Template::factory()->public()->create();
        sleep(1);
        Template::factory()->public()->create();

        $response = $this->getJson('/api/v1/templates?sort=newest');

        $response->assertStatus(200);
    }

    public function test_can_sort_templates_by_most_forked(): void
    {
        Template::factory()->public()->create(['fork_count' => 5]);
        Template::factory()->public()->create(['fork_count' => 50]);
        Template::factory()->public()->create(['fork_count' => 25]);

        $response = $this->getJson('/api/v1/templates?sort=most_forked');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertEquals(50, $data[0]['fork_count']);
    }

    public function test_filter_by_multiple_tags(): void
    {
        $t1 = Template::factory()->public()->create(['tags' => ['business', 'pitch']]);
        $t2 = Template::factory()->public()->create(['tags' => ['creative', 'minimal']]);

        $response = $this->getJson('/api/v1/templates?tags=business,pitch');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_filter_by_nonexistent_type_returns_empty(): void
    {
        Template::factory()->public()->create();

        $response = $this->getJson('/api/v1/templates?type_id=nonexistent-uuid');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    public function test_search_is_case_insensitive(): void
    {
        Template::factory()->public()->create(['name' => 'Business Plan']);
        Template::factory()->public()->create(['name' => 'creative portfolio']);

        $response = $this->getJson('/api/v1/templates?q=BUSINESS');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_search_matches_description(): void
    {
        Template::factory()->public()->create(['name' => 'Template A', 'description' => 'Annual report for business']);
        Template::factory()->public()->create(['name' => 'Template B', 'description' => 'Creative portfolio']);

        $response = $this->getJson('/api/v1/templates?q=annual');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_pagination_respects_per_page(): void
    {
        Template::factory()->public()->count(150)->create();

        $response = $this->getJson('/api/v1/templates?per_page=50');

        $response->assertStatus(200)
            ->assertJsonCount(50, 'data')
            ->assertJsonStructure(['meta' => ['current_page', 'per_page', 'total', 'last_page']]);
    }

    public function test_pagination_returns_empty_for_page_beyond_data(): void
    {
        Template::factory()->public()->count(5)->create();

        $response = $this->getJson('/api/v1/templates?page=100');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // PROJECT VALIDATION EDGE CASES
    // ═══════════════════════════════════════════════════════════════════════════

    public function test_cannot_create_project_with_invalid_type_id(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/projects', [
            'type_id' => 'nonexistent-uuid',
            'name' => 'Test Project',
        ]);

        $response->assertStatus(422);
    }

    public function test_cannot_create_project_with_empty_name(): void
    {
        $user = User::factory()->create();
        $type = Type::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/projects', [
            'type_id' => $type->id,
            'name' => '',
        ]);

        $response->assertStatus(422);
    }

    public function test_cannot_update_project_with_invalid_status(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/v1/projects/' . $project->id, [
            'status' => 'invalid_status',
        ]);

        $response->assertStatus(422);
    }

    public function test_can_update_project_status_to_published(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id, 'status' => 'draft']);
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/v1/projects/' . $project->id, [
            'status' => 'published',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'published');
    }

    public function test_can_update_project_visibility(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id, 'visibility' => 'private']);
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/v1/projects/' . $project->id, [
            'visibility' => 'public',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('visibility', 'public');
    }

    public function test_can_filter_projects_by_status(): void
    {
        $user = User::factory()->create();
        Project::factory()->create(['user_id' => $user->id, 'status' => 'draft']);
        Project::factory()->create(['user_id' => $user->id, 'status' => 'published']);
        Project::factory()->create(['user_id' => $user->id, 'status' => 'published']);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/projects?status=published');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_can_filter_projects_by_type_id(): void
    {
        $user = User::factory()->create();
        $type1 = Type::factory()->create();
        $type2 = Type::factory()->create();
        Project::factory()->create(['user_id' => $user->id, 'type_id' => $type1->id]);
        Project::factory()->create(['user_id' => $user->id, 'type_id' => $type2->id]);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/projects?type_id=' . $type1->id);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_search_projects_by_name(): void
    {
        $user = User::factory()->create();
        Project::factory()->create(['user_id' => $user->id, 'name' => 'Business Plan']);
        Project::factory()->create(['user_id' => $user->id, 'name' => 'Creative Portfolio']);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/projects?q=Business');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // FILE VALIDATION & CONTENT EDGE CASES
    // ═══════════════════════════════════════════════════════════════════════════

    public function test_cannot_add_file_with_invalid_layer(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/projects/' . $project->id . '/files', [
            'layer' => 'invalid_layer',
            'name' => 'test.html',
            'extension' => 'html',
        ]);

        $response->assertStatus(422);
    }

    public function test_cannot_add_file_with_empty_name(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/projects/' . $project->id . '/files', [
            'layer' => 'slide',
            'name' => '',
            'extension' => 'html',
        ]);

        $response->assertStatus(422);
    }

    public function test_can_add_file_with_all_valid_layers(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $layers = ['slide', 'style', 'layout', 'content', 'context', 'rules', 'meta', 'asset'];

        foreach ($layers as $index => $layer) {
            $response = $this->postJson('/api/v1/projects/' . $project->id . '/files', [
                'layer' => $layer,
                'name' => "file-{$layer}.html",
                'extension' => 'html',
                'sort_order' => $index,
            ]);

            $response->assertStatus(201);
        }
    }

    public function test_can_update_file_content_with_special_characters(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $file = File::factory()->create(['project_id' => $project->id, 'user_id' => $user->id]);
        Sanctum::actingAs($user);

        $specialContent = '<div class="test">Hello "World" & \'Friends\'</div>
<script>console.log("nested \"quotes\"");</script>
<style>
    .class { content: "\\n\\t\\r special"; }
</style>';

        $response = $this->putJson("/api/v1/projects/{$project->id}/files/{$file->id}", [
            'content' => $specialContent,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('content', $specialContent);
    }

    public function test_can_update_file_content_with_unicode(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $file = File::factory()->create(['project_id' => $project->id, 'user_id' => $user->id]);
        Sanctum::actingAs($user);

        $unicodeContent = "# 日本語テスト\n## 中文测试\n### 한국어 테스트\n\nemoji: 🚀🎉✨\n\nspecial: <\" & >\'";

        $response = $this->putJson("/api/v1/projects/{$project->id}/files/{$file->id}", [
            'content' => $unicodeContent,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('content', $unicodeContent);
    }

    public function test_can_update_file_content_with_json(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $file = File::factory()->create(['project_id' => $project->id, 'user_id' => $user->id]);
        Sanctum::actingAs($user);

        $jsonContent = '{
    "title": "Test JSON",
    "nested": {
        "array": [1, 2, 3],
        "escaped": "line\\nbreak"
    }
}';

        $response = $this->putJson("/api/v1/projects/{$project->id}/files/{$file->id}", [
            'content' => $jsonContent,
        ]);

        $response->assertStatus(200);
    }

    public function test_can_update_file_name(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $file = File::factory()->create(['project_id' => $project->id, 'user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->putJson("/api/v1/projects/{$project->id}/files/{$file->id}", [
            'name' => 'renamed-slide.html',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('name', 'renamed-slide.html');
    }

    public function test_can_update_file_sort_order(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $file = File::factory()->create(['project_id' => $project->id, 'user_id' => $user->id, 'sort_order' => 0]);
        Sanctum::actingAs($user);

        $response = $this->putJson("/api/v1/projects/{$project->id}/files/{$file->id}", [
            'sort_order' => 5,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('sort_order', 5);
    }

    public function test_reorder_files_with_invalid_uuid(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->patchJson("/api/v1/projects/{$project->id}/files/reorder", [
            'order' => ['not-a-valid-uuid'],
        ]);

        $response->assertStatus(422);
    }

    public function test_reorder_files_with_empty_order(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->patchJson("/api/v1/projects/{$project->id}/files/reorder", [
            'order' => [],
        ]);

        $response->assertStatus(422); // Empty array fails 'required' validation
    }

    public function test_cannot_reorder_another_users_project_files(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $file = File::factory()->create(['project_id' => $project->id, 'user_id' => $owner->id]);
        Sanctum::actingAs($other);

        $response = $this->patchJson("/api/v1/projects/{$project->id}/files/reorder", [
            'order' => [$file->id],
        ]);

        $response->assertStatus(403);
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // ACCESS CONTROL EDGE CASES
    // ═══════════════════════════════════════════════════════════════════════════

    public function test_cannot_update_template_without_auth(): void
    {
        $template = Template::factory()->create();

        $response = $this->putJson('/api/v1/templates/' . $template->id, [
            'name' => 'Hacked',
        ]);

        $response->assertStatus(401);
    }

    public function test_cannot_delete_template_without_auth(): void
    {
        $template = Template::factory()->create();

        $response = $this->deleteJson('/api/v1/templates/' . $template->id);

        $response->assertStatus(401);
    }

    public function test_cannot_view_nonexistent_template(): void
    {
        $response = $this->getJson('/api/v1/templates/nonexistent-uuid-0000-0000-0000-000000000000');

        $response->assertStatus(404);
    }

    public function test_cannot_view_nonexistent_project(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/projects/nonexistent-uuid-0000-0000-0000-000000000000');

        $response->assertStatus(404);
    }

    public function test_cannot_access_another_users_private_project_files(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $project = Project::factory()->private()->create(['user_id' => $owner->id]);
        $file = File::factory()->create(['project_id' => $project->id, 'user_id' => $owner->id]);
        Sanctum::actingAs($other);

        $response = $this->getJson('/api/v1/projects/' . $project->id . '/files');
        $response->assertStatus(403);
    }

    public function test_guest_cannot_list_private_template_files(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->private()->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/v1/templates/' . $template->id . '/files');

        $response->assertStatus(403);
    }

    public function test_owner_can_add_multiple_files_to_template(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $this->postJson('/api/v1/templates/' . $template->id . '/files', [
            'layer' => 'slide',
            'name' => 'slide-01.html',
            'extension' => 'html',
        ])->assertStatus(201);

        $this->postJson('/api/v1/templates/' . $template->id . '/files', [
            'layer' => 'style',
            'name' => 'style.css',
            'extension' => 'css',
        ])->assertStatus(201);

        $response = $this->getJson('/api/v1/templates/' . $template->id . '/files');
        $response->assertJsonCount(2, 'data');
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // UPDATE REQUESTS VALIDATION
    // ═══════════════════════════════════════════════════════════════════════════

    public function test_update_template_with_invalid_thumbnail_url(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/v1/templates/' . $template->id, [
            'thumbnail_url' => 'not-a-url',
        ]);

        $response->assertStatus(422);
    }

    public function test_update_template_partialy(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->create([
            'user_id' => $user->id,
            'name' => 'Original',
            'description' => 'Original description',
        ]);
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/v1/templates/' . $template->id, [
            'name' => 'Updated Only',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('name', 'Updated Only')
            ->assertJsonPath('description', 'Original description');
    }

    public function test_update_project_partialy(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'user_id' => $user->id,
            'name' => 'Original',
            'status' => 'draft',
        ]);
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/v1/projects/' . $project->id, [
            'status' => 'published',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'published')
            ->assertJsonPath('name', 'Original');
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // FORK EDGE CASES
    // ═══════════════════════════════════════════════════════════════════════════

    public function test_fork_creates_project_with_copied_files(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->public()->create(['name' => 'Source Template']);
        $file1 = File::factory()->create([
            'template_id' => $template->id,
            'user_id' => $template->user_id,
            'name' => 'slide-01.html',
            'content' => '<h1>Original</h1>',
        ]);
        $file2 = File::factory()->create([
            'template_id' => $template->id,
            'user_id' => $template->user_id,
            'name' => 'style.css',
            'content' => 'body { color: blue; }',
        ]);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/templates/' . $template->id . '/fork', [
            'name' => 'My Forked Project',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('name', 'My Forked Project')
            ->assertJsonPath('template_id', $template->id);

        $project = Project::where('name', 'My Forked Project')->first();
        $this->assertEquals(2, $project->files()->count());
    }

    public function test_fork_requires_name(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->public()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/templates/' . $template->id . '/fork', []);

        $response->assertStatus(422);
    }

    public function test_fork_increments_template_fork_count(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->public()->create(['fork_count' => 5]);
        Sanctum::actingAs($user);

        $this->postJson('/api/v1/templates/' . $template->id . '/fork', [
            'name' => 'My Fork',
        ]);

        $template->refresh();
        $this->assertEquals(6, $template->fork_count);
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // UPVOTE & BOOKMARK EDGE CASES
    // ═══════════════════════════════════════════════════════════════════════════

    public function test_upvote_requires_auth(): void
    {
        $template = Template::factory()->public()->create();

        $response = $this->postJson('/api/v1/templates/' . $template->id . '/upvote');

        $response->assertStatus(401);
    }

    public function test_bookmark_requires_auth(): void
    {
        $template = Template::factory()->public()->create();

        $response = $this->postJson('/api/v1/templates/' . $template->id . '/bookmark');

        $response->assertStatus(401);
    }

    public function test_toggle_bookmark_off(): void
    {
        $user = User::factory()->create();
        $template = Template::factory()->public()->create();
        Sanctum::actingAs($user);

        $this->postJson('/api/v1/templates/' . $template->id . '/bookmark');

        $response = $this->postJson('/api/v1/templates/' . $template->id . '/bookmark');

        $response->assertStatus(200)
            ->assertJson(['bookmarked' => false]);
    }

    public function test_user_can_upvote_multiple_templates(): void
    {
        $user = User::factory()->create();
        $t1 = Template::factory()->public()->create(['upvote_count' => 0]);
        $t2 = Template::factory()->public()->create(['upvote_count' => 0]);
        Sanctum::actingAs($user);

        $this->postJson('/api/v1/templates/' . $t1->id . '/upvote');
        $this->postJson('/api/v1/templates/' . $t2->id . '/upvote');

        $this->assertEquals(1, $t1->fresh()->upvote_count);
        $this->assertEquals(1, $t2->fresh()->upvote_count);
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // DELETE & SOFT DELETE EDGE CASES
    // ═══════════════════════════════════════════════════════════════════════════

    public function test_deleting_project_soft_deletes_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $file = File::factory()->create(['project_id' => $project->id, 'user_id' => $user->id]);
        Sanctum::actingAs($user);

        $this->deleteJson('/api/v1/projects/' . $project->id);

        $this->assertSoftDeleted('projects', ['id' => $project->id]);
        // Files remain but project_id would be null if cascade was set
        $this->assertNotNull($file->fresh()->project_id);
    }

    public function test_can_query_soft_deleted_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $this->deleteJson('/api/v1/projects/' . $project->id);

        $response = $this->getJson('/api/v1/projects/' . $project->id);
        $response->assertStatus(404);
    }

    public function test_cannot_delete_other_users_template(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $template = Template::factory()->create(['user_id' => $owner->id]);
        Sanctum::actingAs($other);

        $response = $this->deleteJson('/api/v1/templates/' . $template->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('templates', ['id' => $template->id]);
    }

    public function test_cannot_delete_other_users_project(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        Sanctum::actingAs($other);

        $response = $this->deleteJson('/api/v1/projects/' . $project->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('projects', ['id' => $project->id]);
    }
}