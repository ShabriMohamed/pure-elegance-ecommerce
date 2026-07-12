<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:255', 'unique:products,sku,' . $this->product->id],
            'category_id' => ['required', 'exists:categories,id'],
            'brand' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
            'primary_image' => ['nullable', 'image', 'max:2048'],
        ];
    }

    protected function prepareForValidation()
    {
        $data = [
            'is_active' => $this->has('is_active'),
            'is_featured' => $this->has('is_featured'),
        ];
        
        if ($this->name !== $this->product->name) {
            $data['slug'] = Str::slug($this->name) . '-' . Str::random(5);
        }
        
        $this->merge($data);
    }
}
