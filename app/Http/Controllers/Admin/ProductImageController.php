<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'images' => 'required|array|max:8',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $order = (int) ProductImage::where('product_id', $product->id)->max('sort_order');
        foreach ($request->file('images') as $file) {
            $product->images()->create(['path' => $file->store('products', 'public'), 'sort_order' => ++$order]);
        }

        return back()->with('success', 'Foto ditambahkan.');
    }

    public function destroy(ProductImage $image)
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();

        return back()->with('success', 'Foto dihapus.');
    }
}
