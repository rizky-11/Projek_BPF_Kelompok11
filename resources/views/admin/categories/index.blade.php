@extends('layouts.admin.app')
@section('title', 'Kategori')

@section('content')
<div class="mb-3"><a href="{{ route('admin.categories.create') }}" class="btn btn-primary">+ Tambah Kategori</a></div>
<div class="card border-0 shadow">
    <div class="table-responsive">
        <table class="table table-hover align-items-center mb-0">
            <thead class="thead-light"><tr><th>Urutan</th><th>Nama</th><th>Slug</th><th>Produk</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse ($categories as $c)
                <tr>
                    <td>{{ $c->sort_order }}</td>
                    <td>{{ $c->name }}</td>
                    <td class="text-gray-500">{{ $c->slug }}</td>
                    <td>{{ $c->products_count }}</td>
                    <td><span class="badge bg-{{ $c->is_active ? 'success' : 'secondary' }}">{{ $c->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td class="d-flex gap-2">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.categories.edit', $c) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $c) }}" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-gray-500">Belum ada kategori.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $categories->links('pagination::bootstrap-5') }}</div>
@endsection
