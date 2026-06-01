<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'avatar_url' => 'nullable|string|url|max:500',
            'website' => 'nullable|string|url|max:500',
            'location' => 'nullable|string|max:255',
        ];
    }
}