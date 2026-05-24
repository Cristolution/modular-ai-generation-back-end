<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAiProviderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'provider' => [
                'required',
                'string',
                'in:openai,anthropic,gemini,local,custom',
                fn ($attribute, $value, $fail) => $this->duplicateProviderCheck($value) ? $fail('You already have a ' . $value . ' provider configured.') : null,
            ],
            'display_name' => 'nullable|string|max:255',
            'api_key' => 'nullable|string|max:500',
            'base_url' => 'required|string|url|max:500',
            'default_model' => 'nullable|string|max:100',
        ];
    }

    private function duplicateProviderCheck(string $provider): bool
    {
        return $this->user()->aiProviders()->where('provider', $provider)->exists();
    }
}