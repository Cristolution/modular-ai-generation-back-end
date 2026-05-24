<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateFullRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'provider_id' => 'required|uuid|exists:user_ai_providers,id',
            'model' => 'nullable|string|max:100',
            'prompt' => 'nullable|string|max:5000',
        ];
    }
}