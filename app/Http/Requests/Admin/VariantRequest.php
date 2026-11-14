<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'size_name' => 'required|string|max:50',
            'dimension' => 'nullable|string|max:50',
            'gsm' => 'nullable|integer|min:1|max:2000',
            'unit' => 'required|string|max:20',
            'content' => 'nullable|string|max:50',
            'price' => 'required|integer|min:0|max:100000000',
            'stock' => 'required|integer|min:0|max:10000000',
            'min_qty' => 'required|integer|min:1|max:100000',
            'sku' => ['nullable', 'string', 'max:50', Rule::unique('product_variants', 'sku')->ignore($this->route('variant'))],
            'is_active' => 'boolean',
        ];
    }
}
