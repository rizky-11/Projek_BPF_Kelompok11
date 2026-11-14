<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function verify(Request $request, Payment $payment)
    {
        abort_unless($payment->status === 'menunggu' && $payment->order->status === 'menunggu_verifikasi', 422);

        $payment->update([
            'status' => 'terverifikasi',
            'verified_at' => now(),
            'verified_by' => $request->user()->id,
            'reject_reason' => null,
        ]);
        $payment->order->update(['status' => 'diproses']);

        return back()->with('success', 'Pembayaran diverifikasi. Pesanan masuk tahap diproses.');
    }

    public function reject(Request $request, Payment $payment)
    {
        abort_unless($payment->status === 'menunggu' && $payment->order->status === 'menunggu_verifikasi', 422);

        $data = $request->validate(['reject_reason' => 'required|string|max:200']);

        $payment->update([
            'status' => 'ditolak',
            'reject_reason' => $data['reject_reason'],
            'verified_at' => now(),
            'verified_by' => $request->user()->id,
        ]);
        $payment->order->update(['status' => 'menunggu_pembayaran']); // pelanggan bisa unggah ulang

        return back()->with('success', 'Bukti pembayaran ditolak.');
    }

    public function proof(Payment $payment)
    {
        abort_unless(Storage::disk('local')->exists($payment->proof_path), 404);

        return Storage::disk('local')->response($payment->proof_path);
    }
}
