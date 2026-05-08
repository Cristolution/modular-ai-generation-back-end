<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'thumbnail_url' => 'nullable|string|url',
            'visibility' => 'sometimes|string|in:public,private,unlisted',
            'tags' => 'sometimes|array',
            'tags.*' => 'string|max:50',
        ];
    }
}
