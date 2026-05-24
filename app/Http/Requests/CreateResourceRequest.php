<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateResourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kind' => 'required|string|in:prompt,skill,agent,rule,mcp,design_doc,hook',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'placeholders' => 'nullable|array',
            'placeholders.*.key' => 'required_with:placeholders|string|max:50',
            'placeholders.*.label' => 'required_with:placeholders|string|max:100',
            'placeholders.*.default' => 'nullable|string',
            'placeholders.*.type' => 'required_with:placeholders|string|in:text,textarea,select',
            'visibility' => 'sometimes|string|in:public,private,unlisted',
            'tags' => 'sometimes|array',
            'tags.*' => 'string|max:50',
        ];
    }
}