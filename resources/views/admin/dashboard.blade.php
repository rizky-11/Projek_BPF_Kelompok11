@extends('layouts.admin.app')
@section('title', 'Dashboard')

@section('content')
<div class="row">
    @foreach ([
        ['Menunggu pembayaran', $newOrders, 'admin.orders.index', ['status' => 'menunggu_pembayaran']],
        ['Perlu diverifikasi', $toVerify, 'admin.orders.index', ['status' => 'menunggu_verifikasi']],
        ['Sedang diproses', $inProcess, 'admin.orders.index', ['status' => 'diproses']],
        ['Pesan belum dibaca', $unread, 'admin.messages.index', []],
    ] as [$label, $count, $route, $params])
        <div class="col-12 col-sm-6 col-xl-3 mb-4">
            <a href="{{ route($route, $params) }}" class="card border-0 shadow text-decoration-none">
                <div class="card-body">
                    <h2 class="h6 text-gray-500">{{ $label }}</h2>
                    <div class="h3 mb-0 text-dark">{{ $count }}</div>
                </div>
            </a>
        </div>
    @endforeach
</div>

<div class="row">
    <div class="col-12 col-xl-7 mb-4">
        <div class="card border-0 shadow">
            <div class="card-header"><h2 class="h5 mb-0">Pesanan terbaru</h2></div>
            <div class="table-responsive">
                <table class="table table-hover align-items-center mb-0">
                    <thead class="thead-light"><tr><th>Kode</th><th>Pemesan</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse ($latestOrders as $o)
                        <tr>
                            <td><a href="{{ route('admin.orders.show', $o) }}">{{ $o->order_code }}</a></td>
                            <td>{{ $o->customer_name }}</td>
                            <td>Rp {{ number_format($o->total, 0, ',', '.') }}</td>
                            <td><span class="badge bg-{{ $o->status_color }}">{{ $o->status_label }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-gray-500">Belum ada pesanan.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-5 mb-4">
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <h2 class="h6 text-gray-500">Pendapatan (pesanan terverifikasi)</h2>
                <div class="h3 mb-0">Rp {{ number_format($revenue, 0, ',', '.') }}</div>
                <p class="small text-gray-500 mb-0">{{ $products }} produk &middot; {{ $categories }} kategori</p>
            </div>
        </div>
        <div class="card border-0 shadow">
            <div class="card-header"><h2 class="h5 mb-0">Stok menipis (&le; 10)</h2></div>
            <ul class="list-group list-group-flush">
                @forelse ($lowStock as $v)
                    <li class="list-group-item d-flex justify-content-between">
                        <a href="{{ route('admin.products.edit', $v->product_id) }}">{{ $v->product->name }} {{ $v->label }}</a>
                        <span class="badge bg-{{ $v->stock == 0 ? 'danger' : 'warning' }}">{{ $v->stock }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-gray-500">Semua stok aman.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
