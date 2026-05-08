<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type_id' => 'required|uuid|exists:types,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'visibility' => 'sometimes|string|in:public,private,unlisted',
            'tags' => 'sometimes|array',
            'tags.*' => 'string|max:50',
            'locale' => 'sometimes|string|in:en,ar,es,fr,de,zh',
            'direction' => 'sometimes|string|in:ltr,rtl',
        ];
    }
}
