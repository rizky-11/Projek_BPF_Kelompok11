<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => ['required', 'string', 'max:150', Rule::unique('products', 'name')->ignore($this->route('product'))],
            'brand' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:3000',
            'specification' => 'nullable|string|max:3000',
            'is_active' => 'boolean',
        ];
    }
}
