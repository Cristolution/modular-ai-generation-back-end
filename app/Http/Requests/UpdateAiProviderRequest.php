<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAiProviderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'display_name' => 'sometimes|string|max:255',
            'api_key' => 'nullable|string|max:500',
            'base_url' => 'sometimes|string|url|max:500',
            'default_model' => 'nullable|string|max:100',
            'is_active' => 'sometimes|boolean',
        ];
    }
}