<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'cta_text' => ['nullable', 'string', 'max:255'],
            'cta_link' => ['nullable', 'string', 'max:255'],
            'position' => ['required', 'string', 'in:hero,category,promotional'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'image' => ['required', 'image', 'max:2048'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['boolean'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->has('is_active'),
        ]);
    }
}
