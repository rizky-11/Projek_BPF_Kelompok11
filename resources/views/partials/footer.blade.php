<footer class="mt-16 border-t border-border bg-secondary">
    <div class="container mx-auto grid gap-8 px-4 py-10 md:grid-cols-3">
        <div>
            <p class="font-display text-xl font-semibold">{{ $s('store_name') }}</p>
            <p class="mt-2 max-w-sm text-sm text-muted-foreground">{{ $s('tagline') }}</p>
        </div>
        <div class="space-y-1 text-sm text-muted-foreground">
            <p class="font-semibold text-foreground">Kontak</p>
            <p>{{ $s('address') }}</p>
            <p>{{ $s('open_hours') }}</p>
            @if ($s('whatsapp'))<p><a class="hover:text-foreground" href="https://wa.me/{{ $s('whatsapp') }}" target="_blank" rel="noopener">WhatsApp {{ $s('whatsapp') }}</a></p>@endif
            <p>{{ $s('email') }}</p>
        </div>
        <div class="space-y-1 text-sm">
            <p class="font-semibold">Menu</p>
            <a class="block text-muted-foreground hover:text-foreground" href="{{ route('products.index') }}">Semua Produk</a>
            <a class="block text-muted-foreground hover:text-foreground" href="{{ route('orders.track') }}">Lacak Pesanan</a>
            <a class="block text-muted-foreground hover:text-foreground" href="{{ route('faq') }}">FAQ</a>
            <a class="block text-muted-foreground hover:text-foreground" href="{{ route('login') }}">Login Admin</a>
        </div>
    </div>
    <p class="border-t border-border py-4 text-center text-xs text-muted-foreground">&copy; {{ date('Y') }} {{ $s('store_name') }}</p>
</footer>
