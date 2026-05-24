<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserAiProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAiProviderFactory extends Factory
{
    protected $model = UserAiProvider::class;

    public function definition(): array
    {
        $provider = fake()->randomElement(['openai', 'anthropic', 'gemini', 'local', 'custom']);

        return [
            'user_id' => User::factory(),
            'provider' => $provider,
            'display_name' => match ($provider) {
                'openai' => 'OpenAI',
                'anthropic' => 'Anthropic',
                'gemini' => 'Google Gemini',
                'local' => 'Local LM Studio',
                'custom' => 'Custom Provider',
            },
            'api_key_encrypted' => encrypt('sk-test-' . fake()->uuid()),
            'base_url' => match ($provider) {
                'openai' => 'https://api.openai.com/v1',
                'anthropic' => 'https://api.anthropic.com',
                'gemini' => 'https://generativelanguage.googleapis.com/v1',
                'local' => 'http://localhost:1234/v1',
                'custom' => 'https://api.example.com/v1',
            },
            'default_model' => match ($provider) {
                'openai' => 'gpt-4o',
                'anthropic' => 'claude-sonnet-4-7',
                'gemini' => 'gemini-2.0-flash',
                'local' => 'llama3',
                'custom' => 'custom-model',
            },
            'is_active' => true,
            'created_at' => now(),
        ];
    }

    public function openai(): static
    {
        return $this->state(fn (array $attributes) => [
            'provider' => 'openai',
            'display_name' => 'OpenAI',
            'base_url' => 'https://api.openai.com/v1',
            'default_model' => 'gpt-4o',
        ]);
    }

    public function anthropic(): static
    {
        return $this->state(fn (array $attributes) => [
            'provider' => 'anthropic',
            'display_name' => 'Anthropic',
            'base_url' => 'https://api.anthropic.com',
            'default_model' => 'claude-sonnet-4-7',
        ]);
    }

    public function local(): static
    {
        return $this->state(fn (array $attributes) => [
            'provider' => 'local',
            'display_name' => 'Local LM Studio',
            'base_url' => 'http://localhost:1234/v1',
            'default_model' => 'llama3',
            'api_key_encrypted' => null,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}