<?php

namespace Database\Seeders;

use App\Models\AiJob;
use App\Models\Project;
use Illuminate\Database\Seeder;

class AiJobSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::with('user.aiProviders')->has('user')->get();

        foreach ($projects as $project) {
            if ($project->user->aiProviders->isEmpty()) {
                continue;
            }

            $provider = $project->user->aiProviders->first();

            AiJob::factory()
                ->success()
                ->forProject($project)
                ->create([
                    'provider_id' => $provider->id,
                    'provider' => $provider->provider,
                    'model' => $provider->default_model,
                ]);
        }
    }
}