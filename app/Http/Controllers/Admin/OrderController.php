<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::withCount('items')
            ->when($request->query('status'), fn ($q, $st) => $q->where('status', $st))
            ->when($request->query('q'), fn ($q, $s) => $q->where(fn ($w) => $w
                ->where('order_code', 'like', "%{$s}%")
                ->orWhere('customer_name', 'like', "%{$s}%")
                ->orWhere('customer_phone', 'like', "%{$s}%")))
            ->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items', 'payments.verifier']);

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order, OrderService $orders)
    {
        $next = Order::NEXT[$order->status];
        $final = empty($next);

        $data = $request->validate([
            'status' => ['required', Rule::in(array_merge([$order->status], $next))],
            'shipping_cost' => 'nullable|integer|min:0|max:100000000',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $order->admin_note = $data['admin_note'] ?? null;

        // Ongkir hanya bisa diubah sebelum pelanggan membayar.
        if ($order->status === 'menunggu_pembayaran') {
            $order->shipping_cost = (int) ($data['shipping_cost'] ?? 0);
            $order->total = $order->subtotal + $order->shipping_cost;
        }
        $order->save();

        if (! $final && $data['status'] === 'dibatalkan') {
            $orders->cancel($order);       // mengembalikan stok
        } elseif (! $final && $data['status'] !== $order->status) {
            $order->update(['status' => $data['status']]);
        }

        return back()->with('success', 'Pesanan diperbarui.');
    }
}
