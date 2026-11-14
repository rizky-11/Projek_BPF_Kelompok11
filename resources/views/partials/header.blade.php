<header class="sticky top-0 z-50 border-b border-border bg-background/95 backdrop-blur">
    <div class="container mx-auto flex flex-wrap items-center gap-x-6 gap-y-3 px-4 py-3">
        <a href="{{ route('home') }}" class="font-display text-2xl font-semibold tracking-tight">{{ $s('store_name', config('app.name')) }}</a>

        <form action="{{ route('products.index') }}" method="GET" class="order-last w-full md:order-none md:max-w-sm md:flex-1">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari kertas: HVS, art paper, kraft..." class="input rounded-full">
        </form>

        <nav class="ml-auto flex flex-wrap items-center gap-1 text-sm font-medium">
            @foreach ([['home', 'Beranda'], ['products.index', 'Produk'], ['orders.track', 'Lacak Pesanan'], ['faq', 'FAQ'], ['contact.create', 'Hubungi Kami']] as [$route, $label])
                <a href="{{ route($route) }}" class="rounded-lg px-3 py-2 {{ request()->routeIs($route) ? 'bg-accent text-accent-foreground' : 'hover:bg-muted' }}">{{ $label }}</a>
            @endforeach
            <a href="{{ route('cart.index') }}" class="btn btn-primary ml-2">
                Keranjang
                @if ($cartCount > 0)<span class="rounded-full bg-white/25 px-2 text-xs">{{ $cartCount }}</span>@endif
            </a>
        </nav>
    </div>
</header>
