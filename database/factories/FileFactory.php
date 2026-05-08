<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\Project;
use App\Models\Template;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{
    protected $model = File::class;

    public function definition(): array
    {
        $typeName = TemplateFactory::$typeNames[array_rand(TemplateFactory::$typeNames)];
        $type = Type::firstOrCreate(
            ['name' => $typeName],
            ['description' => "Description for $typeName", 'icon' => 'icon']
        );

        return [
            'template_id' => null,
            'project_id' => null,
            'user_id' => User::factory(),
            'layer' => fake()->randomElement(['slide', 'style', 'layout', 'content', 'context', 'rules', 'meta', 'asset']),
            'name' => fake()->word() . '.html',
            'extension' => 'html',
            'sort_order' => fake()->numberBetween(0, 10),
            'content' => '<div class="slide">{{content}}</div>',
            'storage_url' => null,
            'size_bytes' => fake()->numberBetween(1000, 50000),
        ];
    }

    public function forTemplate(Template $template): static
    {
        return $this->state(fn (array $attributes) => [
            'template_id' => $template->id,
            'user_id' => $template->user_id,
        ]);
    }

    public function forProject(Project $project): static
    {
        return $this->state(fn (array $attributes) => [
            'project_id' => $project->id,
            'user_id' => $project->user_id,
        ]);
    }

    public function slide(): static
    {
        return $this->state(fn (array $attributes) => [
            'layer' => 'slide',
            'name' => 'slide-01.html',
            'extension' => 'html',
        ]);
    }

    public function style(): static
    {
        return $this->state(fn (array $attributes) => [
            'layer' => 'style',
            'name' => 'style.css',
            'extension' => 'css',
        ]);
    }

    public function content(): static
    {
        return $this->state(fn (array $attributes) => [
            'layer' => 'content',
            'name' => 'content.html',
            'extension' => 'html',
        ]);
    }
}
