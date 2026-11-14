<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'newOrders' => Order::where('status', 'menunggu_pembayaran')->count(),
            'toVerify' => Payment::where('status', 'menunggu')->count(),
            'inProcess' => Order::whereIn('status', ['diproses', 'siap'])->count(),
            'revenue' => (int) Order::whereIn('status', ['diproses', 'siap', 'selesai'])->sum('total'),
            'products' => Product::count(),
            'categories' => Category::count(),
            'unread' => ContactMessage::where('is_read', false)->count(),
            'latestOrders' => Order::latest()->take(6)->get(),
            'lowStock' => ProductVariant::with('product')->where('is_active', true)->where('stock', '<=', 10)->orderBy('stock')->take(6)->get(),
        ]);
    }
}
