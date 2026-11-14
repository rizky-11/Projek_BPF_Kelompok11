@extends('layouts.app')
@section('title', 'Semua Produk')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-semibold">Semua Produk</h1>
    <p class="mb-6 text-muted-foreground">
        {{ $products->total() }} produk @if (request('q')) untuk &ldquo;{{ request('q') }}&rdquo;@endif
    </p>

    <div class="mb-8 flex flex-wrap gap-2">
        <a href="{{ route('products.index', array_filter(['q' => request('q')])) }}" class="chip {{ request('category') ? '' : 'chip-active' }}">Semua</a>
        @foreach ($categories as $c)
            <a href="{{ route('products.index', array_filter(['category' => $c->slug, 'q' => request('q')])) }}"
               class="chip {{ request('category') === $c->slug ? 'chip-active' : '' }}">{{ $c->name }}
                <span class="rounded-full bg-muted px-2 text-xs text-muted-foreground">{{ $c->products_count }}</span></a>
        @endforeach
    </div>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @forelse ($products as $p)
            <x-product-card :product="$p" />
        @empty
            <p class="col-span-full py-10 text-center text-muted-foreground">Produk tidak ditemukan.</p>
        @endforelse
    </div>

    <div class="mt-8">{{ $products->links() }}</div>
</div>
@endsection
