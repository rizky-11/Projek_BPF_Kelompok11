@props(['product'])
<a href="{{ route('products.show', $product->slug) }}" class="card group block overflow-hidden transition hover:-translate-y-1 hover:shadow-lg">
    <div class="aspect-[4/3] overflow-hidden bg-muted">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
    </div>
    <div class="space-y-1 p-4">
        <p class="text-xs text-muted-foreground">{{ $product->category->name }}@if ($product->brand) &middot; {{ $product->brand }}@endif</p>
        <h3 class="line-clamp-2 font-semibold">{{ $product->name }}</h3>
        <p class="pt-1 text-xs text-muted-foreground">Mulai dari</p>
        <p class="text-lg font-semibold text-primary">Rp {{ number_format($product->starting_price, 0, ',', '.') }}</p>
    </div>
</a>
