<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForkResourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
        ];
    }
}