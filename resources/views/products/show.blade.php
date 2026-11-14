@extends('layouts.app')
@section('title', $product->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <nav class="mb-6 text-sm text-muted-foreground">
        <a href="{{ route('home') }}" class="hover:text-foreground">Beranda</a> &rsaquo;
        <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-foreground">{{ $product->category->name }}</a> &rsaquo;
        {{ $product->name }}
    </nav>

    <div class="grid gap-10 lg:grid-cols-2">
        {{-- Galeri --}}
        <div>
            <img id="main-image" src="{{ $product->image_url }}" alt="{{ $product->name }}" class="card aspect-[4/3] w-full object-cover">
            @if ($product->images->count() > 1)
                <div class="mt-3 flex gap-2">
                    @foreach ($product->images as $img)
                        <button type="button" data-src="{{ $img->url }}" class="thumb h-16 w-16 overflow-hidden rounded-md border border-border">
                            <img src="{{ $img->url }}" alt="" class="h-full w-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Pemilih varian --}}
        <form method="POST" action="{{ route('cart.store') }}" class="space-y-5" id="order-form">
            @csrf
            <div>
                <p class="text-xs text-muted-foreground">{{ $product->category->name }}@if ($product->brand) &middot; {{ $product->brand }}@endif</p>
                <h1 class="text-3xl font-semibold">{{ $product->name }}</h1>
                @if ($product->description)<p class="mt-2 text-muted-foreground">{{ $product->description }}</p>@endif
            </div>

            <fieldset>
                <legend class="label">Pilih ukuran &amp; gramatur</legend>
                <div class="space-y-2">
                    @foreach ($product->variants as $v)
                        <label class="card flex cursor-pointer items-center gap-3 p-3 has-[:checked]:border-primary has-[:checked]:bg-accent {{ $v->stock < $v->min_qty ? 'opacity-50' : '' }}">
                            <input type="radio" name="variant_id" value="{{ $v->id }}" required
                                   data-price="{{ $v->price }}" data-min="{{ $v->min_qty }}" data-stock="{{ $v->stock }}" data-unit="{{ $v->unit }}"
                                   @checked(old('variant_id', $loop->first ? $v->id : null) == $v->id) @disabled($v->stock < $v->min_qty)>
                            <span class="flex-1">
                                <span class="block font-medium">{{ $v->label }}</span>
                                <span class="block text-xs text-muted-foreground">
                                    {{ $v->unit_label }}@if ($v->dimension) &middot; {{ $v->dimension }}@endif
                                    &middot; {{ $v->stock >= $v->min_qty ? 'Stok '.$v->stock : 'Stok habis' }}
                                </span>
                            </span>
                            <span class="font-semibold text-primary">Rp {{ number_format($v->price, 0, ',', '.') }}</span>
                        </label>
                    @endforeach
                </div>
            </fieldset>

            <div>
                <label class="label" for="quantity">Jumlah (<span id="unit-label">{{ $product->variants->first()->unit }}</span>)</label>
                <input id="quantity" type="number" name="quantity" value="{{ old('quantity', $product->variants->first()->min_qty) }}" min="1" class="input max-w-40" required>
                @error('quantity')<p class="err">{{ $message }}</p>@enderror
                @error('variant_id')<p class="err">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-end justify-between border-t border-border pt-4">
                <div>
                    <p class="text-xs text-muted-foreground">Total harga</p>
                    <p id="total" class="text-2xl font-semibold text-primary">Rp 0</p>
                </div>
                <button class="btn btn-primary">Tambah ke Keranjang</button>
            </div>
            @if ($s('whatsapp'))
                <a href="https://wa.me/{{ $s('whatsapp') }}?text={{ urlencode('Halo, saya ingin bertanya tentang '.$product->name) }}" target="_blank" rel="noopener" class="btn btn-outline w-full">Tanya via WhatsApp</a>
            @endif
        </form>
    </div>

    @if ($product->specification)
        <div class="card mt-10 p-6">
            <h2 class="mb-3 text-xl font-semibold">Spesifikasi</h2>
            <p class="whitespace-pre-line text-sm">{{ $product->specification }}</p>
        </div>
    @endif

    @if ($related->isNotEmpty())
        <h2 class="mb-4 mt-12 text-2xl font-semibold">Produk terkait</h2>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($related as $p)<x-product-card :product="$p" />@endforeach
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    const form = document.getElementById('order-form');
    const qty = document.getElementById('quantity');
    const total = document.getElementById('total');
    const unitLabel = document.getElementById('unit-label');
    const rupiah = n => 'Rp ' + new Intl.NumberFormat('id-ID').format(n);

    function current() { return form.querySelector('input[name=variant_id]:checked'); }

    function refresh(resetQty) {
        const r = current();
        if (!r) { total.textContent = rupiah(0); return; }
        qty.min = r.dataset.min;
        qty.max = r.dataset.stock;
        unitLabel.textContent = r.dataset.unit;
        if (resetQty || +qty.value < +r.dataset.min) qty.value = r.dataset.min;
        total.textContent = rupiah(r.dataset.price * (+qty.value || 0));
    }

    form.querySelectorAll('input[name=variant_id]').forEach(r => r.addEventListener('change', () => refresh(true)));
    qty.addEventListener('input', () => refresh(false));
    refresh(false);

    document.querySelectorAll('.thumb').forEach(b => b.addEventListener('click', () => {
        document.getElementById('main-image').src = b.dataset.src;
    }));
</script>
@endpush
