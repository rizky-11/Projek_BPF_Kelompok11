<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - {{ $s('store_name', config('app.name')) }}</title>
    <link type="text/css" href="{{ asset('assets-admin/css/volt.css') }}" rel="stylesheet">
</head>
<body>
<main>
    <section class="vh-lg-100 mt-5 mt-lg-0 bg-soft d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center form-bg-image">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500">
                        <div class="text-center text-md-center mb-4 mt-md-0">
                            <h1 class="mb-0 h3">Login Admin</h1>
                            <p class="text-gray-600 small">{{ $s('store_name') }}</p>
                        </div>
                        <form method="POST" action="{{ route('login.store') }}">
                            @csrf
                            <div class="form-group mb-4">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                                @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group mb-4">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                                <label class="form-check-label mb-0" for="remember">Ingat saya</label>
                            </div>
                            <div class="d-grid"><button type="submit" class="btn btn-gray-800">Masuk</button></div>
                        </form>
                        <p class="text-center mt-4 mb-0"><a href="{{ route('home') }}" class="small">&larr; Kembali ke situs</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
</body>
</html>
