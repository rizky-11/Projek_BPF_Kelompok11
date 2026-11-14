<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'categories' => Category::where('is_active', true)
                ->withCount(['products' => fn ($q) => $q->onSale()])
                ->orderBy('sort_order')->get(),
            'products' => Product::onSale()->withStartingPrice()->with(['category', 'images'])->latest()->take(8)->get(),
        ]);
    }
}
