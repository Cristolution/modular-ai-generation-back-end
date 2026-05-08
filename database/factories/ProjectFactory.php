<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Template;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $typeName = TemplateFactory::$typeNames[array_rand(TemplateFactory::$typeNames)];
        $type = Type::firstOrCreate(
            ['name' => $typeName],
            ['description' => "Description for $typeName", 'icon' => 'icon']
        );

        return [
            'user_id' => User::factory(),
            'template_id' => null,
            'type_id' => $type->id,
            'origin_template_name' => null,
            'name' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'status' => fake()->randomElement(['draft', 'published', 'archived']),
            'visibility' => fake()->randomElement(['public', 'private', 'unlisted']),
            'tags' => fake()->randomElements(['business', 'pitch', 'creative', 'minimal', 'dark', 'colorful'], rand(1, 3)),
            'locale' => fake()->randomElement(['en', 'ar', 'es', 'fr']),
            'direction' => fake()->randomElement(['ltr', 'rtl']),
            'cloned_at' => null,
        ];
    }

    public function public(): static
    {
        return $this->state(fn (array $attributes) => [
            'visibility' => 'public',
        ]);
    }

    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'visibility' => 'private',
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
        ]);
    }
}
