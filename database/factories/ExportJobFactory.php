<?php

namespace Database\Factories;

use App\Models\ExportJob;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExportJobFactory extends Factory
{
    protected $model = ExportJob::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'format' => fake()->randomElement(['html', 'pdf', 'png', 'jpg', 'pptx', 'zip', 'md']),
            'status' => fake()->randomElement(['pending', 'processing', 'ready', 'failed']),
            'download_url' => fake()->optional()->url(),
            'expires_at' => fake()->optional()->dateTimeBetween('now', '+1 hour'),
            'error_message' => null,
            'options' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'pending']);
    }

    public function processing(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'processing']);
    }

    public function ready(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'ready',
            'download_url' => fake()->url(),
            'expires_at' => now()->addHour(),
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'error_message' => fake()->sentence(),
        ]);
    }
}