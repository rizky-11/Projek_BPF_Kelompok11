@extends('layouts.app')
@section('title', 'Pesanan '.$order->order_code)

@section('content')
@php($payment = $order->payments->first())
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex flex-wrap items-center gap-3">
        <h1 class="text-3xl font-semibold">Pesanan {{ $order->order_code }}</h1>
        <span class="badge bg-accent text-accent-foreground">{{ $order->status_label }}</span>
    </div>
    <p class="mb-6 text-sm text-muted-foreground">Simpan kode pesanan ini untuk melacak pesanan Anda. Dibuat {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB.</p>

    <div class="grid gap-8 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="card overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-muted"><tr><th class="p-3">Produk</th><th class="p-3">Harga</th><th class="p-3">Jumlah</th><th class="p-3 text-right">Total</th></tr></thead>
                    <tbody>
                    @foreach ($order->items as $item)
                        <tr class="border-t border-border">
                            <td class="p-3">{{ $item->product_name }}<br><span class="text-muted-foreground">{{ $item->variant_label }} &middot; {{ $item->unit }}</span></td>
                            <td class="p-3">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="p-3">{{ $item->quantity }}</td>
                            <td class="p-3 text-right">Rp {{ number_format($item->line_total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot class="border-t border-border">
                        <tr><td colspan="3" class="p-3 text-right">Subtotal</td><td class="p-3 text-right">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td></tr>
                        <tr><td colspan="3" class="p-3 text-right">Ongkos kirim</td><td class="p-3 text-right">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td></tr>
                        <tr class="font-semibold"><td colspan="3" class="p-3 text-right">Total bayar</td><td class="p-3 text-right text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</td></tr>
                    </tfoot>
                </table>
            </div>

            {{-- Pembayaran --}}
            <div class="card space-y-4 p-6">
                <h2 class="text-xl font-semibold">Pembayaran</h2>

                @if ($order->status === 'dibatalkan')
                    <p class="text-sm text-muted-foreground">Pesanan ini dibatalkan.</p>
                @elseif ($order->canBePaid())
                    <div class="rounded-md bg-accent p-4 text-sm">
                        Transfer <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong> ke
                        <strong>{{ $s('bank_name') }} {{ $s('bank_account') }}</strong> a.n. {{ $s('bank_holder') }}, lalu unggah bukti di bawah.
                        @if ($order->delivery_method === 'antar' && $order->shipping_cost == 0)
                            <br><span class="text-muted-foreground">Catatan: ongkos kirim mungkin masih ditambahkan admin. Hubungi kami jika total berubah.</span>
                        @endif
                    </div>
                    @if ($payment && $payment->status === 'ditolak')
                        <p class="err text-sm">Bukti sebelumnya ditolak: {{ $payment->reject_reason }}. Silakan unggah ulang.</p>
                    @endif

                    <form method="POST" action="{{ route('orders.pay', $order->order_code) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div><label class="label">Nama pengirim</label><input class="input" name="sender_name" value="{{ old('sender_name', $order->customer_name) }}" required>
                                @error('sender_name')<p class="err">{{ $message }}</p>@enderror</div>
                            <div><label class="label">Jumlah transfer (Rp)</label><input class="input" type="number" name="amount" value="{{ old('amount', $order->total) }}" required>
                                @error('amount')<p class="err">{{ $message }}</p>@enderror</div>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div><label class="label">Tanggal transfer</label><input class="input" type="date" name="paid_at" value="{{ old('paid_at', now()->toDateString()) }}" max="{{ now()->toDateString() }}" required>
                                @error('paid_at')<p class="err">{{ $message }}</p>@enderror</div>
                            <div><label class="label">Bukti transfer (JPG/PNG/PDF, maks 2 MB)</label><input class="input" type="file" name="proof" accept=".jpg,.jpeg,.png,.pdf" required>
                                @error('proof')<p class="err">{{ $message }}</p>@enderror</div>
                        </div>
                        <button class="btn btn-primary">Kirim Bukti Pembayaran</button>
                    </form>
                @elseif ($order->status === 'menunggu_verifikasi')
                    <p class="text-sm">Bukti pembayaran Anda sudah kami terima dan sedang diverifikasi admin.</p>
                @else
                    <p class="text-sm text-success">Pembayaran terverifikasi. Terima kasih!</p>
                @endif
            </div>
        </div>

        <aside class="card h-fit space-y-2 p-6 text-sm">
            <h2 class="text-xl font-semibold">Data pemesan</h2>
            <p><strong>{{ $order->customer_name }}</strong></p>
            <p>{{ $order->customer_phone }}@if ($order->customer_email)<br>{{ $order->customer_email }}@endif</p>
            <p>{{ $order->delivery_method === 'antar' ? 'Diantar ke:' : 'Ambil di toko' }}</p>
            @if ($order->shipping_address)<p class="whitespace-pre-line text-muted-foreground">{{ $order->shipping_address }}</p>@endif
            @if ($order->customer_note)<p class="border-t border-border pt-2 text-muted-foreground">Catatan: {{ $order->customer_note }}</p>@endif
            @if ($order->admin_note)<p class="border-t border-border pt-2">Info dari toko: {{ $order->admin_note }}</p>@endif
        </aside>
    </div>
</div>
@endsection
