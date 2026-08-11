<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\Template;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TemplateFactory extends Factory
{
    protected $model = Template::class;

    public static array $typeNames = [
        'presentation', 'carousel', 'poster', 'infographic', 'document', 'website',
    ];

    protected static int $typeIndex = 0;

    public function definition(): array
    {
        $typeName = self::$typeNames[self::$typeIndex % \count(self::$typeNames)];
        self::$typeIndex++;

        $type = Type::firstOrCreate(
            ['name' => $typeName],
            ['description' => "Description for $typeName", 'icon' => 'icon']
        );

        return [
            'user_id' => User::factory(),
            'type_id' => $type->id,
            'name' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'thumbnail_url' => fake()->optional()->imageUrl(400, 300),
            'visibility' => fake()->randomElement(['public', 'private', 'unlisted']),
            'tags' => fake()->randomElements(['business', 'pitch', 'creative', 'minimal', 'dark', 'colorful'], rand(1, 3)),
            'locale' => fake()->randomElement(['en', 'ar', 'es', 'fr']),
            'direction' => fake()->randomElement(['ltr', 'rtl']),
            'fork_count' => fake()->numberBetween(0, 50),
            'upvote_count' => fake()->numberBetween(0, 100),
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

    public function unlisted(): static
    {
        return $this->state(fn (array $attributes) => [
            'visibility' => 'unlisted',
        ]);
    }

    public function withFiles(int $count = 6): static
    {
        return $this->afterCreating(function (Template $template) use ($count) {
            File::factory()
                ->count($count)
                ->forTemplate($template)
                ->create();
        });
    }

    public function withTags(array $tags): static
    {
        return $this->state(fn (array $attributes) => [
            'tags' => $tags,
        ]);
    }
}
