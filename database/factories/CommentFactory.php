<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Resource;
use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        $targetType = fake()->randomElement(['template', 'resource']);

        return [
            'user_id' => User::factory(),
            'target_id' => $targetType === 'template' ? Template::factory() : Resource::factory(),
            'target_type' => $targetType,
            'parent_id' => null,
            'body' => fake()->paragraph(),
        ];
    }

    public function forTemplate(Template $template): static
    {
        return $this->state(fn (array $attributes) => [
            'target_id' => $template->id,
            'target_type' => Template::class,
        ]);
    }

    public function forResource(Resource $resource): static
    {
        return $this->state(fn (array $attributes) => [
            'target_id' => $resource->id,
            'target_type' => Resource::class,
        ]);
    }

    public function reply(Comment $parent): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $parent->id,
            'target_id' => $parent->target_id,
            'target_type' => $parent->target_type,
        ]);
    }
}