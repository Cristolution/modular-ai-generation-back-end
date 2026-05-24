<?php

namespace Database\Factories;

use App\Models\AiJob;
use App\Models\Project;
use App\Models\Template;
use App\Models\User;
use App\Models\UserAiProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class AiJobFactory extends Factory
{
    protected $model = AiJob::class;

    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'running', 'success', 'failed']);

        return [
            'file_id' => null,
            'project_id' => Project::factory(),
            'template_id' => null,
            'triggered_by' => User::factory(),
            'provider_id' => UserAiProvider::factory()->openai(),
            'provider' => 'openai',
            'model' => fake()->randomElement(['gpt-4o', 'gpt-4-turbo', 'gpt-3.5-turbo']),
            'layer' => fake()->randomElement(['slide', 'style', 'content', 'context', null]),
            'prompt' => fake()->optional()->sentence(),
            'status' => $status,
            'error_message' => $status === 'failed' ? fake()->sentence() : null,
            'tokens_used' => $status === 'success' ? fake()->numberBetween(100, 10000) : null,
            'duration_ms' => $status === 'success' ? fake()->numberBetween(1000, 60000) : null,
            'created_at' => fake()->dateTimeBetween('-1 week', 'now'),
            'completed_at' => $status === 'success' || $status === 'failed' ? fake()->dateTimeBetween('-1 week', 'now') : null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'error_message' => null,
            'tokens_used' => null,
            'duration_ms' => null,
            'completed_at' => null,
        ]);
    }

    public function running(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'running',
            'error_message' => null,
            'tokens_used' => null,
            'duration_ms' => null,
            'completed_at' => null,
        ]);
    }

    public function success(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'success',
            'error_message' => null,
            'tokens_used' => fake()->numberBetween(100, 10000),
            'duration_ms' => fake()->numberBetween(1000, 60000),
            'completed_at' => now(),
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'error_message' => fake()->sentence(),
            'tokens_used' => null,
            'duration_ms' => fake()->numberBetween(1000, 30000),
            'completed_at' => now(),
        ]);
    }

    public function forProject(Project $project): static
    {
        return $this->state(fn (array $attributes) => [
            'project_id' => $project->id,
            'template_id' => $project->template_id,
        ]);
    }
}