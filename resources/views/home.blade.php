@extends('layouts.app')

@section('content')
{{-- Hero: ukuran kertas seri A sesuai proporsi aslinya (skala 0,66 px/mm) --}}
<section class="border-b border-border bg-secondary">
    <div class="container mx-auto grid items-center gap-10 px-4 py-14 lg:grid-cols-2">
        <div>
            <h1 class="text-4xl font-semibold leading-tight md:text-5xl">{{ $s('hero_title') }}</h1>
            <p class="mt-4 max-w-xl text-lg text-muted-foreground">{{ $s('hero_text') }}</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('products.index') }}" class="btn btn-primary">Lihat Semua Kertas</a>
                <a href="{{ route('orders.track') }}" class="btn btn-outline">Lacak Pesanan</a>
            </div>
        </div>
        <svg viewBox="0 0 420 300" class="mx-auto w-full max-w-md" role="img" aria-label="Perbandingan ukuran kertas A3, A4, A5, dan A6">
            <g fill="white" stroke="currentColor" stroke-width="1.5" class="text-primary">
                <rect x="10" y="13" width="196" height="277" />
                <rect x="10" y="94" width="139" height="196" />
                <rect x="10" y="151" width="98" height="139" />
                <rect x="10" y="192" width="69" height="98" />
            </g>
            <g class="fill-muted-foreground" font-size="12" font-family="Instrument Sans, sans-serif">
                <text x="214" y="26">A3 · 297 × 420 mm</text>
                <text x="157" y="107">A4 · 210 × 297 mm</text>
                <text x="116" y="164">A5 · 148 × 210 mm</text>
                <text x="87" y="205">A6 · 105 × 148 mm</text>
            </g>
        </svg>
    </div>
</section>

<section class="container mx-auto px-4 py-10">
    <h2 class="mb-4 text-2xl font-semibold">Jenis kertas</h2>
    <div class="flex flex-wrap gap-2">
        @foreach ($categories as $c)
            <a href="{{ route('products.index', ['category' => $c->slug]) }}" class="chip">{{ $c->name }}
                <span class="rounded-full bg-muted px-2 text-xs text-muted-foreground">{{ $c->products_count }}</span></a>
        @endforeach
    </div>
</section>

<section class="container mx-auto px-4">
    <h2 class="mb-4 text-2xl font-semibold">Produk terbaru</h2>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @forelse ($products as $p)
            <x-product-card :product="$p" />
        @empty
            <p class="col-span-full text-muted-foreground">Belum ada produk.</p>
        @endforelse
    </div>
</section>

<section class="container mx-auto mt-14 px-4">
    <div class="grid gap-6 md:grid-cols-3">
        @foreach ([['Pilih varian', 'Tentukan ukuran dan gramatur kertas, harga langsung terlihat.'], ['Isi data & bayar', 'Tanpa perlu akun. Transfer ke rekening toko lalu unggah bukti.'], ['Lacak pesanan', 'Pantau status dengan kode pesanan dan nomor telepon Anda.']] as [$title, $text])
            <div class="card p-6"><h3 class="font-semibold">{{ $title }}</h3><p class="mt-2 text-sm text-muted-foreground">{{ $text }}</p></div>
        @endforeach
    </div>
</section>
@endsection
