<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VariantRequest;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductVariantController extends Controller
{
    public function store(VariantRequest $request, Product $product)
    {
        $product->variants()->create($this->data($request));

        return back()->with('success', 'Varian ditambahkan.');
    }

    public function update(VariantRequest $request, ProductVariant $variant)
    {
        $variant->update($this->data($request));

        return back()->with('success', 'Varian diperbarui.');
    }

    public function destroy(ProductVariant $variant)
    {
        $variant->delete();

        return back()->with('success', 'Varian dihapus.');
    }

    private function data(VariantRequest $request): array
    {
        $data = $request->validated();
        if (array_key_exists('sku', $data)) {
            $data['sku'] = $data['sku'] ?: null; // string kosong -> NULL agar tidak bentrok unik
        }

        return $data;
    }
}
