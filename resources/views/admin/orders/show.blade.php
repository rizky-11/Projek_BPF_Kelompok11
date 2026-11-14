@extends('layouts.admin.app')
@section('title', 'Pesanan '.$order->order_code)

@section('content')
@php($next = \App\Models\Order::NEXT[$order->status])
<div class="row">
    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="h5 mb-0">Item pesanan</h2>
                <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="thead-light"><tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th class="text-end">Total</th></tr></thead>
                    <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}<br><small class="text-gray-500">{{ $item->variant_label }} &middot; {{ $item->unit }}</small></td>
                            <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="text-end">Rp {{ number_format($item->line_total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr><td colspan="3" class="text-end">Subtotal</td><td class="text-end">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td></tr>
                    <tr><td colspan="3" class="text-end">Ongkos kirim</td><td class="text-end">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td></tr>
                    <tr class="fw-bold"><td colspan="3" class="text-end">Total</td><td class="text-end">Rp {{ number_format($order->total, 0, ',', '.') }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow mb-4">
            <div class="card-header"><h2 class="h5 mb-0">Pembayaran</h2></div>
            <div class="card-body">
                @forelse ($order->payments as $pay)
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between flex-wrap">
                            <div>
                                <strong>{{ $pay->sender_name }}</strong> &middot; Rp {{ number_format($pay->amount, 0, ',', '.') }}
                                @if ($pay->amount != $order->total)<span class="badge bg-warning">Nominal berbeda dari total</span>@endif
                                <br><small class="text-gray-500">Tanggal transfer {{ $pay->paid_at?->format('d/m/Y') }} &middot; diunggah {{ $pay->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            <div><span class="badge bg-{{ ['menunggu' => 'info', 'terverifikasi' => 'success', 'ditolak' => 'danger'][$pay->status] }}">{{ ucfirst($pay->status) }}</span></div>
                        </div>
                        <a href="{{ route('admin.payments.proof', $pay) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">Lihat bukti transfer</a>

                        @if ($pay->status === 'menunggu' && $order->status === 'menunggu_verifikasi')
                            <div class="d-flex flex-wrap gap-2 mt-3">
                                <form method="POST" action="{{ route('admin.payments.verify', $pay) }}" onsubmit="return confirm('Verifikasi pembayaran ini?')">
                                    @csrf<button class="btn btn-sm btn-success">Verifikasi</button>
                                </form>
                                <form method="POST" action="{{ route('admin.payments.reject', $pay) }}" class="d-flex gap-2">
                                    @csrf
                                    <input class="form-control form-control-sm" name="reject_reason" placeholder="Alasan penolakan" required>
                                    <button class="btn btn-sm btn-outline-danger">Tolak</button>
                                </form>
                            </div>
                        @elseif ($pay->status === 'ditolak')
                            <p class="small text-danger mb-0 mt-2">Alasan: {{ $pay->reject_reason }}</p>
                        @elseif ($pay->status === 'terverifikasi')
                            <p class="small text-gray-500 mb-0 mt-2">Diverifikasi {{ $pay->verified_at?->format('d/m/Y H:i') }} oleh {{ $pay->verifier?->name ?? '-' }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500 mb-0">Belum ada bukti pembayaran.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card border-0 shadow mb-4">
            <div class="card-header"><h2 class="h5 mb-0">Pemesan</h2></div>
            <div class="card-body small">
                <p class="mb-1"><strong>{{ $order->customer_name }}</strong></p>
                <p class="mb-1">{{ $order->customer_phone }}
                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', $order->customer_phone) }}" target="_blank" rel="noopener">(WhatsApp)</a></p>
                @if ($order->customer_email)<p class="mb-1">{{ $order->customer_email }}</p>@endif
                <p class="mb-1">{{ $order->delivery_method === 'antar' ? 'Diantar ke:' : 'Ambil di toko' }}</p>
                @if ($order->shipping_address)<p class="mb-1" style="white-space:pre-line">{{ $order->shipping_address }}</p>@endif
                @if ($order->customer_note)<p class="mb-0 text-gray-600">Catatan: {{ $order->customer_note }}</p>@endif
            </div>
        </div>

        <div class="card border-0 shadow">
            <div class="card-header"><h2 class="h5 mb-0">Ubah pesanan</h2></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                    @csrf @method('PUT')
                    <div class="mb-3"><label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="{{ $order->status }}" selected>{{ $order->status_label }} (saat ini)</option>
                            @foreach ($next as $st)
                                <option value="{{ $st }}">{{ \App\Models\Order::STATUSES[$st][0] }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Pembayaran diverifikasi/ditolak lewat tombol di bagian Pembayaran. Pembatalan mengembalikan stok.</div>
                    </div>
                    <div class="mb-3"><label class="form-label">Ongkos kirim (Rp)</label>
                        <input type="number" min="0" name="shipping_cost" class="form-control" value="{{ old('shipping_cost', $order->shipping_cost) }}" @disabled($order->status !== 'menunggu_pembayaran')>
                        @if ($order->status !== 'menunggu_pembayaran')<div class="form-text">Hanya bisa diubah sebelum pelanggan membayar.</div>@endif
                    </div>
                    <div class="mb-3"><label class="form-label">Catatan untuk pelanggan</label>
                        <textarea name="admin_note" rows="3" class="form-control">{{ old('admin_note', $order->admin_note) }}</textarea></div>
                    <button class="btn btn-primary w-100">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<a href="{{ route('admin.orders.index') }}" class="btn btn-gray-200">&larr; Kembali</a>
@endsection
