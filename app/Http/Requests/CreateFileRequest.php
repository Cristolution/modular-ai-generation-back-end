<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'layer' => 'required|string|in:slide,style,layout,content,context,rules,meta,asset',
            'name' => 'required|string|max:255',
            'extension' => 'required|string|max:10',
            'sort_order' => 'sometimes|integer|min:0',
            'content' => 'nullable|string',
        ];
    }
}
