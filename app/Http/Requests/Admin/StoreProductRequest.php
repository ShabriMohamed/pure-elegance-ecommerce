<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                  => ['required', 'string', 'max:255'],
            'sku'                   => ['required', 'string', 'unique:products,sku', 'max:255'],
            'category_id'           => ['required', 'exists:categories,id'],
            'brand'                 => ['nullable', 'string', 'max:255'],
            'price'                 => ['required', 'numeric', 'min:0'],
            'sale_price'            => ['nullable', 'numeric', 'min:0'],
            'stock_quantity'        => ['required', 'integer', 'min:0'],
            'short_description'     => ['nullable', 'string'],
            'description'           => ['nullable', 'string'],
            'is_active'             => ['boolean'],
            'is_featured'           => ['boolean'],
            'primary_image'         => ['required', 'image', 'max:4096'],
            'additional_images'     => ['nullable', 'array', 'max:10'],
            'additional_images.*'   => ['image', 'max:4096'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_active'   => $this->has('is_active'),
            'is_featured' => $this->has('is_featured'),
            'slug'        => Str::slug($this->name) . '-' . Str::random(5),
        ]);
    }
}
