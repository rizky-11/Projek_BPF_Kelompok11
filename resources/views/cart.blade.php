@extends('layouts.app')
@section('title', 'Keranjang')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="mb-6 text-3xl font-semibold">Keranjang</h1>

    @if ($lines->isEmpty())
        <div class="card p-10 text-center">
            <p class="text-muted-foreground">Keranjang Anda masih kosong.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary mt-4">Mulai Belanja</a>
        </div>
    @else
        <div class="grid gap-8 lg:grid-cols-3">
            <div class="space-y-3 lg:col-span-2">
                @foreach ($lines as $line)
                    @php($v = $line->variant)
                    <div class="card flex flex-wrap items-center gap-4 p-4">
                        <img src="{{ $v->product->image_url }}" alt="" class="h-20 w-20 rounded-md object-cover">
                        <div class="min-w-48 flex-1">
                            <a href="{{ route('products.show', $v->product->slug) }}" class="font-semibold hover:underline">{{ $v->product->name }}</a>
                            <p class="text-sm text-muted-foreground">{{ $v->label }} &middot; {{ $v->unit_label }}</p>
                            <p class="text-sm">Rp {{ number_format($v->price, 0, ',', '.') }} / {{ $v->unit }}</p>
                            @if ($line->problem)<p class="err">{{ $line->problem }}</p>@endif
                        </div>
                        <form method="POST" action="{{ route('cart.update', $v) }}" class="flex items-center gap-2">
                            @csrf @method('PATCH')
                            <input type="number" name="quantity" value="{{ $line->qty }}" min="{{ $v->min_qty }}" max="{{ $v->stock }}" class="input w-24">
                            <button class="btn btn-outline">Ubah</button>
                        </form>
                        <p class="w-32 text-right font-semibold">Rp {{ number_format($line->line_total, 0, ',', '.') }}</p>
                        <form method="POST" action="{{ route('cart.destroy', $v) }}" onsubmit="return confirm('Hapus dari keranjang?')">
                            @csrf @method('DELETE')<button class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                @endforeach
            </div>

            <aside class="card h-fit space-y-3 p-6">
                <h2 class="text-xl font-semibold">Ringkasan</h2>
                <div class="flex justify-between text-sm"><span>Subtotal</span><span class="font-semibold">Rp {{ number_format($lines->sum('line_total'), 0, ',', '.') }}</span></div>
                <p class="text-xs text-muted-foreground">{{ $s('shipping_note') }}</p>
                <a href="{{ route('checkout.create') }}" class="btn btn-primary w-full">Lanjut ke Checkout</a>
            </aside>
        </div>
    @endif
</div>
@endsection
