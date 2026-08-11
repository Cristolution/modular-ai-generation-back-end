<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\Project;
use App\Models\Template;
use App\Models\Type;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProjectSeeder extends BaseSeeder
{
    protected function seed(): void
    {
        $user = User::factory()->create([
            'name' => 'Project Owner',
            'email' => 'projects@example.com',
            'password_hash' => Hash::make('password'),
        ]);

        $types = Type::all();
        $templates = Template::all();

        $projects = [
            [
                'name' => 'My Business Pitch',
                'description' => 'A compelling pitch deck for investors',
                'status' => 'draft',
                'visibility' => 'private',
                'tags' => ['business', 'pitch'],
                'locale' => 'en',
                'direction' => 'ltr',
            ],
            [
                'name' => 'Product Launch Plan',
                'description' => 'Comprehensive product launch presentation',
                'status' => 'published',
                'visibility' => 'public',
                'tags' => ['product', 'marketing'],
                'locale' => 'en',
                'direction' => 'ltr',
            ],
            [
                'name' => 'Annual Report 2026',
                'description' => 'Company annual report design',
                'status' => 'published',
                'visibility' => 'public',
                'tags' => ['business', 'report'],
                'locale' => 'en',
                'direction' => 'ltr',
            ],
        ];

        foreach ($projects as $projectData) {
            $type = $types->where('name', 'presentation')->first();

            $project = Project::factory()->create([
                'user_id' => $user->id,
                'template_id' => $templates->isNotEmpty() ? $templates->random()->id : null,
                'type_id' => $type->id,
                'name' => $projectData['name'],
                'description' => $projectData['description'],
                'status' => $projectData['status'],
                'visibility' => $projectData['visibility'],
                'tags' => $projectData['tags'],
                'locale' => $projectData['locale'],
                'direction' => $projectData['direction'],
                'cloned_at' => $templates->isNotEmpty() ? now() : null,
            ]);

            $this->createProjectFiles($project, $user);
        }

        Project::factory(3)->create([
            'user_id' => $user->id,
            'template_id' => $templates->isNotEmpty() ? $templates->random()->id : null,
            'cloned_at' => $templates->isNotEmpty() ? now() : null,
        ])->each(function ($project) use ($user) {
            $this->createProjectFiles($project, $user);
        });
    }

    private function createProjectFiles(Project $project, User $user): void
    {
        $files = [
            ['layer' => 'meta', 'name' => 'meta.md', 'extension' => 'md', 'content' => "# {$project->name}"],
            ['layer' => 'context', 'name' => 'context.md', 'extension' => 'md', 'content' => "Context for project"],
            ['layer' => 'rules', 'name' => 'rules.md', 'extension' => 'md', 'content' => "Project rules"],
            ['layer' => 'layout', 'name' => 'layout.html', 'extension' => 'html', 'content' => '<div class="layout">{{content}}</div>'],
            ['layer' => 'style', 'name' => 'style.css', 'extension' => 'css', 'content' => 'body { font-family: system-ui; }'],
            ['layer' => 'slide', 'name' => 'slide-01.html', 'extension' => 'html', 'content' => '<div class="slide"><h1>Title</h1></div>'],
        ];

        foreach ($files as $index => $fileData) {
            File::factory()->create([
                'project_id' => $project->id,
                'user_id' => $user->id,
                'layer' => $fileData['layer'],
                'name' => $fileData['name'],
                'extension' => $fileData['extension'],
                'sort_order' => $index,
                'content' => $fileData['content'],
                'size_bytes' => strlen($fileData['content']),
            ]);
        }
    }
}
