@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="mb-6 text-3xl font-semibold">Checkout</h1>

    @error('cart')<div class="mb-4 rounded-md bg-red-100 px-4 py-3 text-sm text-red-800">{{ $message }}</div>@enderror

    <form method="POST" action="{{ route('checkout.store') }}" class="grid gap-8 lg:grid-cols-3" id="checkout-form">
        @csrf
        <div class="card space-y-4 p-6 lg:col-span-2">
            <h2 class="text-xl font-semibold">Data pemesan</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div><label class="label">Nama lengkap</label><input class="input" name="customer_name" value="{{ old('customer_name') }}" required>
                    @error('customer_name')<p class="err">{{ $message }}</p>@enderror</div>
                <div><label class="label">Nomor telepon / WhatsApp</label><input class="input" name="customer_phone" value="{{ old('customer_phone') }}" required>
                    @error('customer_phone')<p class="err">{{ $message }}</p>@enderror</div>
            </div>
            <div><label class="label">Email (opsional)</label><input class="input" type="email" name="customer_email" value="{{ old('customer_email') }}">
                @error('customer_email')<p class="err">{{ $message }}</p>@enderror</div>

            <fieldset>
                <legend class="label">Pengambilan</legend>
                <div class="flex flex-wrap gap-3">
                    <label class="card flex cursor-pointer items-center gap-2 px-4 py-2 has-[:checked]:border-primary has-[:checked]:bg-accent">
                        <input type="radio" name="delivery_method" value="ambil" @checked(old('delivery_method', 'ambil') === 'ambil')> Ambil di toko
                    </label>
                    <label class="card flex cursor-pointer items-center gap-2 px-4 py-2 has-[:checked]:border-primary has-[:checked]:bg-accent">
                        <input type="radio" name="delivery_method" value="antar" @checked(old('delivery_method') === 'antar')> Diantar
                    </label>
                </div>
                @error('delivery_method')<p class="err">{{ $message }}</p>@enderror
            </fieldset>

            <div id="address-box" class="hidden">
                <label class="label">Alamat pengiriman</label>
                <textarea class="input" name="shipping_address" rows="3">{{ old('shipping_address') }}</textarea>
                @error('shipping_address')<p class="err">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-muted-foreground">{{ $s('shipping_note') }}</p>
            </div>

            <div><label class="label">Catatan (opsional)</label><textarea class="input" name="customer_note" rows="2">{{ old('customer_note') }}</textarea></div>

            {{-- Honeypot anti-spam: disembunyikan dari pengguna asli --}}
            <div class="hidden" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
            @error('website')<p class="err">{{ $message }}</p>@enderror
        </div>

        <aside class="card h-fit space-y-3 p-6">
            <h2 class="text-xl font-semibold">Pesanan Anda</h2>
            @foreach ($lines as $line)
                <div class="flex justify-between gap-3 text-sm">
                    <span>{{ $line->variant->product->name }} {{ $line->variant->label }}<br><span class="text-muted-foreground">{{ $line->qty }} {{ $line->variant->unit }}</span></span>
                    <span class="whitespace-nowrap">Rp {{ number_format($line->line_total, 0, ',', '.') }}</span>
                </div>
                @if ($line->problem)<p class="err">{{ $line->problem }}</p>@endif
            @endforeach
            <div class="flex justify-between border-t border-border pt-3 font-semibold"><span>Subtotal</span><span>Rp {{ number_format($lines->sum('line_total'), 0, ',', '.') }}</span></div>
            <p class="text-xs text-muted-foreground">Ongkos kirim (jika diantar) ditambahkan admin setelah pesanan masuk.</p>
            <button class="btn btn-primary w-full">Buat Pesanan</button>
            <a href="{{ route('cart.index') }}" class="block text-center text-sm text-muted-foreground hover:text-foreground">Kembali ke keranjang</a>
        </aside>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const radios = document.querySelectorAll('input[name=delivery_method]');
    const box = document.getElementById('address-box');
    const sync = () => box.classList.toggle('hidden', document.querySelector('input[name=delivery_method]:checked')?.value !== 'antar');
    radios.forEach(r => r.addEventListener('change', sync));
    sync();
</script>
@endpush
