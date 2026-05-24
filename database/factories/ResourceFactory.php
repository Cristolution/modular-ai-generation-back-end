<?php

namespace Database\Factories;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceFactory extends Factory
{
    protected $model = Resource::class;

    public static array $kinds = ['prompt', 'skill', 'agent', 'rule', 'mcp', 'design_doc', 'hook'];

    public function definition(): array
    {
        $kind = fake()->randomElement(self::$kinds);

        return [
            'user_id' => User::factory(),
            'forked_from_id' => null,
            'kind' => $kind,
            'name' => fake()->words(3, true),
            'description' => fake()->optional()->sentence(),
            'content' => $this->generateContent($kind),
            'placeholders' => $this->generatePlaceholders($kind),
            'visibility' => fake()->randomElement(['public', 'private', 'unlisted']),
            'tags' => fake()->randomElements(['business', 'creative', 'technical', 'ai', 'automation', 'design'], rand(1, 3)),
        ];
    }

    private function generateContent(string $kind): string
    {
        return match ($kind) {
            'prompt' => 'You are a helpful assistant. {{task}}',
            'skill' => 'Skill description here...',
            'agent' => 'Agent configuration and instructions...',
            'rule' => 'Rule: Always follow these guidelines...',
            'mcp' => 'MCP server configuration...',
            'design_doc' => '# Design Document\n\n## Overview\n\n## Details',
            'hook' => 'Hook implementation code...',
            default => 'Content here...',
        };
    }

    private function generatePlaceholders(string $kind): ?array
    {
        if ($kind === 'prompt') {
            return [
                ['key' => 'task', 'label' => 'Task', 'default' => 'Write something', 'type' => 'textarea'],
            ];
        }

        return null;
    }

    public function prompt(): static
    {
        return $this->state(fn (array $attributes) => [
            'kind' => 'prompt',
            'content' => 'You are a helpful AI assistant. {{task}}',
            'placeholders' => [['key' => 'task', 'label' => 'Task Description', 'default' => '', 'type' => 'textarea']],
        ]);
    }

    public function skill(): static
    {
        return $this->state(fn (array $attributes) => [
            'kind' => 'skill',
            'content' => 'Skill: Custom implementation here...',
        ]);
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

    public function forFork(Resource $original): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => User::factory(),
            'forked_from_id' => $original->id,
            'name' => 'Fork of ' . $original->name,
            'visibility' => 'private',
        ]);
    }
}