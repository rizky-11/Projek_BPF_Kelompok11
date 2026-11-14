<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::onSale()->withStartingPrice()->with(['category', 'images'])
            ->when($request->query('category'), fn ($q, $slug) => $q->whereHas('category', fn ($c) => $c->where('slug', $slug)))
            ->when($request->query('q'), fn ($q, $s) => $q->where(fn ($w) => $w->where('name', 'like', "%{$s}%")->orWhere('brand', 'like', "%{$s}%")))
            ->latest()->paginate(12)->withQueryString();

        $categories = Category::where('is_active', true)
            ->withCount(['products' => fn ($q) => $q->onSale()])
            ->orderBy('sort_order')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active && $product->category->is_active, 404);

        $product->load(['category', 'images', 'variants' => fn ($q) => $q->where('is_active', true)->orderBy('price')]);
        abort_if($product->variants->isEmpty(), 404);

        $related = Product::onSale()->withStartingPrice()->with(['category', 'images'])
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)->take(4)->get();

        return view('products.show', compact('product', 'related'));
    }
}
