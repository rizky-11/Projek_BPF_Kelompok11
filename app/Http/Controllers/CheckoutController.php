<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Services\CartService;
use App\Services\OrderService;

class CheckoutController extends Controller
{
    public function create(CartService $cart)
    {
        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        return view('checkout', ['lines' => $cart->lines()]);
    }

    public function store(CheckoutRequest $request, OrderService $orders, CartService $cart)
    {
        $order = $orders->createFromCart($request->validated(), $cart);

        // Pelanggan yang membuat pesanan boleh melihat pesanan ini di sesi browser-nya.
        $request->session()->push('tracked_orders', $order->order_code);

        return redirect()->route('orders.show', $order->order_code)
            ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran dan simpan kode pesanan Anda.');
    }
}
