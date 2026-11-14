@extends('layouts.admin.app')
@section('title', 'Ganti Password')

@section('content')
<div class="card border-0 shadow col-12 col-lg-5">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.password.update') }}">
            @csrf @method('PUT')
            <div class="mb-3"><label class="form-label">Password saat ini</label><input type="password" class="form-control" name="current_password" required></div>
            <div class="mb-3"><label class="form-label">Password baru (min. 8 karakter)</label><input type="password" class="form-control" name="password" required></div>
            <div class="mb-3"><label class="form-label">Ulangi password baru</label><input type="password" class="form-control" name="password_confirmation" required></div>
            <button class="btn btn-primary">Ganti password</button>
        </form>
    </div>
</div>
@endsection
