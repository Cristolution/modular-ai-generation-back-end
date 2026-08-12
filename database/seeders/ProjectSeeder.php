<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Template;
use App\Models\Type;
use App\Models\User;
use Database\Seeders\Concerns\MgfFileBuilders;
use Illuminate\Support\Facades\Hash;

class ProjectSeeder extends BaseSeeder
{
    use MgfFileBuilders;

    protected function seed(): void
    {
        $user = User::factory()->create([
            'name' => 'Project Owner',
            'email' => 'projects@example.com',
            'password_hash' => Hash::make('password'),
        ]);

        $types = Type::all();
        $templates = Template::all();

        // Three archetypes drawn from the MGF (Modular Generation Framework)
        // prompt suite. Each emits style.css + layout.css + data.json +
        // distinct mgf-* slide-NN.html files per the AI output contract.
        //
        //   1. Investor pitch deck  (10 slides: cover · problem · features · stats · ...)
        //   2. Launch summary       ( 8 slides: cover · stats · timeline · quote · ...)
        //   3. Scaffolded minimal   ( 2 slides: cover · announcement)
        $archetypes = [
            [
                'name' => 'My Business Pitch',
                'description' => 'A compelling investor pitch deck for a B2B SaaS startup.',
                'status' => 'draft',
                'visibility' => 'private',
                'tags' => ['business', 'pitch', 'investor'],
                'archetype' => 'pitch',
            ],
            [
                'name' => 'Product Launch Plan',
                'description' => 'A presentation summarizing the launch plan for our new product.',
                'status' => 'published',
                'visibility' => 'public',
                'tags' => ['product', 'launch', 'marketing'],
                'archetype' => 'summary',
            ],
            [
                'name' => 'Annual Report 2026',
                'description' => 'A scaffolded annual report presentation in early draft stage.',
                'status' => 'published',
                'visibility' => 'public',
                'tags' => ['business', 'report'],
                'archetype' => 'minimal',
            ],
        ];

        foreach ($archetypes as $projectData) {
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
                'locale' => 'en',
                'direction' => 'ltr',
                'cloned_at' => $templates->isNotEmpty() ? now() : null,
            ]);

            $this->createMgfFiles($project, $user, $projectData['archetype']);
        }

        // Three additional generic projects for variety (random factory content).
        Project::factory(3)->create([
            'user_id' => $user->id,
            'template_id' => $templates->isNotEmpty() ? $templates->random()->id : null,
            'cloned_at' => $templates->isNotEmpty() ? now() : null,
        ])->each(function ($project) use ($user) {
            $this->createMgfFiles($project, $user, 'minimal');
        });
    }

    /**
     * Build a complete MGF file set for a project based on its archetype.
     * Delegates to the MgfFileBuilders trait — same files are produced for
     * any owner (template fork produces a clone with the same file set).
     */
    private function createMgfFiles(Project $project, User $user, string $archetype): void
    {
        $files = match ($archetype) {
            'pitch'   => $this->pitchFiles($project),
            'summary' => $this->summaryFiles($project),
            default   => $this->minimalFiles($project),
        };

        $this->persistFilesOnProject($project, $user, $files);
    }
}
