<nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse">
    <div class="sidebar-inner px-4 pt-3">
        <div class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4">
            <span class="fw-bold">{{ auth()->user()->name }}</span>
            <a href="#sidebarMenu" class="collapse-close d-md-none" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-label="Tutup menu">&times;</a>
        </div>
        <ul class="nav flex-column pt-3 pt-md-0">
            <li class="nav-item mb-3"><span class="nav-link h5 text-white mb-0">{{ $s('store_name') }}</span></li>

            @php
                $menu = [
                    ['admin.dashboard', 'admin.dashboard', 'home', 'Dashboard'],
                    ['admin.orders.index', 'admin.orders.*', 'bag', 'Pesanan'],
                    ['admin.products.index', 'admin.products.*', 'cube', 'Produk'],
                    ['admin.categories.index', 'admin.categories.*', 'tag', 'Kategori'],
                    ['admin.messages.index', 'admin.messages.*', 'chat', 'Pesan Masuk'],
                    ['admin.faqs.index', 'admin.faqs.*', 'question', 'FAQ'],
                    ['admin.settings.edit', 'admin.settings.*', 'sliders', 'Pengaturan Toko'],
                    ['admin.password.edit', 'admin.password.*', 'key', 'Ganti Password'],
                ];
            @endphp
            @foreach ($menu as [$route, $pattern, $icon, $label])
                <li class="nav-item {{ request()->routeIs($pattern) ? 'active' : '' }}">
                    <a href="{{ route($route) }}" class="nav-link">
                        <span class="sidebar-icon"><x-icon :name="$icon" /></span>
                        <span class="sidebar-text">{{ $label }}</span>
                    </a>
                </li>
            @endforeach

            <li role="separator" class="dropdown-divider mt-4 mb-3 border-gray-700"></li>
            <li class="nav-item"><a href="{{ route('home') }}" target="_blank" class="nav-link"><span class="sidebar-text">Lihat situs &nearr;</span></a></li>
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button class="btn btn-secondary w-100 mt-3">Keluar</button>
                </form>
            </li>
        </ul>
    </div>
</nav>
