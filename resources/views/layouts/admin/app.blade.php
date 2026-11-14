<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Admin') - {{ $s('store_name', config('app.name')) }}</title>
    <link rel="icon" href="{{ asset('assets-admin/img/favicon/favicon-32x32.png') }}">
    <link type="text/css" href="{{ asset('assets-admin/css/volt.css') }}" rel="stylesheet">
</head>
<body>
    {{-- Bar atas untuk layar kecil --}}
    <nav class="navbar navbar-dark navbar-theme-primary px-4 col-12 d-lg-none">
        <span class="navbar-brand me-lg-5">{{ $s('store_name') }}</span>
        <button class="navbar-toggler d-lg-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Menu">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>

    @include('layouts.admin.sidebar')

    <main class="content">
        <div class="d-flex justify-content-between align-items-center py-3">
            <h1 class="h4 mb-0">@yield('title', 'Admin')</h1>
            <span class="text-gray-600 small">{{ auth()->user()->name }}</span>
        </div>

        @if (session('success'))<div class="alert alert-success" role="alert">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="alert alert-danger" role="alert">{{ session('error') }}</div>@endif
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Periksa kembali isian Anda:</strong>
                <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        @yield('content')

        <footer class="text-center text-gray-500 small py-4">&copy; {{ date('Y') }} {{ $s('store_name') }} &middot; Panel admin</footer>
    </main>

    <script src="{{ asset('assets-admin/vendor/@popperjs/core/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets-admin/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
