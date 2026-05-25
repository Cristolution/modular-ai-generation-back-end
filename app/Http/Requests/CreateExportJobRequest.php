<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateExportJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'format' => 'required|string|in:html,pdf,png,jpg,pptx,zip,md',
            'options' => 'nullable|array',
            'options.page_size' => 'nullable|string|in:A4,letter,custom',
            'options.width_px' => 'nullable|integer|min:1',
            'options.height_px' => 'nullable|integer|min:1',
            'options.quality' => 'nullable|integer|min:1|max:100',
            'options.slides' => 'nullable|array',
            'options.slides.*' => 'uuid',
        ];
    }
}