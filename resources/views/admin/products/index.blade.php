@extends('layouts.admin.app')
@section('title', 'Produk')

@section('content')
<div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">+ Tambah Produk</a>
    <form method="GET" class="d-flex gap-2">
        <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Cari nama produk">
        <button class="btn btn-gray-800">Cari</button>
    </form>
</div>
<div class="card border-0 shadow">
    <div class="table-responsive">
        <table class="table table-hover align-items-center mb-0">
            <thead class="thead-light"><tr><th>Foto</th><th>Produk</th><th>Kategori</th><th>Varian</th><th>Harga mulai</th><th>Total stok</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse ($products as $p)
                <tr>
                    <td><img src="{{ $p->image_url }}" alt="" width="48" height="48" class="rounded" style="object-fit:cover"></td>
                    <td>{{ $p->name }}@if ($p->brand)<br><small class="text-gray-500">{{ $p->brand }}</small>@endif</td>
                    <td>{{ $p->category->name }}</td>
                    <td>{{ $p->variants_count }}</td>
                    <td>{{ $p->min_price !== null ? 'Rp '.number_format($p->min_price, 0, ',', '.') : '-' }}</td>
                    <td>{{ $p->total_stock ?? 0 }}</td>
                    <td><span class="badge bg-{{ $p->is_active ? 'success' : 'secondary' }}">{{ $p->is_active ? 'Tampil' : 'Disembunyikan' }}</span></td>
                    <td class="d-flex gap-2">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.products.edit', $p) }}">Kelola</a>
                        <form method="POST" action="{{ route('admin.products.destroy', $p) }}" onsubmit="return confirm('Hapus produk beserta varian dan fotonya?')">
                            @csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-gray-500">Belum ada produk.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $products->links('pagination::bootstrap-5') }}</div>
@endsection
