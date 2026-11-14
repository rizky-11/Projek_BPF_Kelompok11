@extends('layouts.app')
@section('title', 'Lacak Pesanan')

@section('content')
<div class="container mx-auto max-w-md px-4 py-12">
    <h1 class="mb-2 text-3xl font-semibold">Lacak Pesanan</h1>
    <p class="mb-6 text-sm text-muted-foreground">Masukkan kode pesanan dan nomor telepon yang Anda pakai saat memesan.</p>
    <form method="POST" action="{{ route('orders.search') }}" class="card space-y-4 p-6">
        @csrf
        <div><label class="label">Kode pesanan</label><input class="input uppercase" name="order_code" value="{{ old('order_code') }}" placeholder="KRT-261112-ABC123" required>
            @error('order_code')<p class="err">{{ $message }}</p>@enderror</div>
        <div><label class="label">Nomor telepon</label><input class="input" name="phone" required>
            @error('phone')<p class="err">{{ $message }}</p>@enderror</div>
        <button class="btn btn-primary w-full">Cari Pesanan</button>
    </form>
</div>
@endsection
