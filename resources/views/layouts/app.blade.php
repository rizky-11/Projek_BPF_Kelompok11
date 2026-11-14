<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $s('store_name', config('app.name')))</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col antialiased">
    @include('partials.header')

    <main class="flex-grow">
        <div class="container mx-auto px-4 pt-6 empty:hidden">
            @if (session('success'))
                <div class="rounded-md bg-green-100 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="rounded-md bg-red-100 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
            @endif
        </div>
        @yield('content')
    </main>

    @include('partials.footer')
    @stack('scripts')
</body>
</html>
