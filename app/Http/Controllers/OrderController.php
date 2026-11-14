<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function trackForm()
    {
        return view('orders.track');
    }

    public function track(Request $request)
    {
        $data = $request->validate(['order_code' => 'required|string|max:30', 'phone' => 'required|string|max:20']);

        $order = Order::where('order_code', strtoupper(trim($data['order_code'])))->first();

        if (! $order || $order->customer_phone !== Order::normalizePhone($data['phone'])) {
            return back()->withErrors(['order_code' => 'Kode pesanan atau nomor telepon tidak cocok.'])->onlyInput('order_code');
        }

        $request->session()->push('tracked_orders', $order->order_code);

        return redirect()->route('orders.show', $order->order_code);
    }

    public function show(Request $request, Order $order)
    {
        if (! $this->allowed($request, $order)) {
            return redirect()->route('orders.track')->with('error', 'Masukkan kode pesanan dan nomor telepon untuk melihat pesanan.');
        }

        $order->load(['items', 'payments']);

        return view('orders.show', compact('order'));
    }

    public function pay(Request $request, Order $order)
    {
        abort_unless($this->allowed($request, $order), 403);
        abort_unless($order->canBePaid(), 422, 'Pesanan ini tidak menerima bukti pembayaran.');

        $data = $request->validate([
            'sender_name' => 'required|string|max:100',
            'amount' => 'required|integer|min:1|max:1000000000',
            'paid_at' => 'required|date|before_or_equal:today',
            'proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Disimpan di disk privat (storage/app/private), hanya admin yang bisa membuka.
        $path = $request->file('proof')->store('payments', 'local');

        Payment::create([
            'order_id' => $order->id,
            'sender_name' => $data['sender_name'],
            'amount' => $data['amount'],
            'proof_path' => $path,
            'paid_at' => $data['paid_at'],
            'status' => 'menunggu',
        ]);
        $order->update(['status' => 'menunggu_verifikasi']);

        return back()->with('success', 'Bukti pembayaran terkirim. Admin akan memverifikasinya.');
    }

    private function allowed(Request $request, Order $order): bool
    {
        return in_array($order->order_code, $request->session()->get('tracked_orders', []), true);
    }
}
