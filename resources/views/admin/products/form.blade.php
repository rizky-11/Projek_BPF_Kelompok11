@extends('layouts.admin.app')
@section('title', $product->exists ? 'Kelola Produk: '.$product->name : 'Tambah Produk')

@section('content')
{{-- 1. Data produk --}}
<div class="card border-0 shadow mb-4">
    <div class="card-header"><h2 class="h5 mb-0">Data produk</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}">
            @csrf
            @if ($product->exists) @method('PUT') @endif
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- pilih --</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}" @selected(old('category_id', $product->category_id) == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select></div>
                <div class="col-md-6 mb-3"><label class="form-label">Merek (opsional)</label>
                    <input class="form-control" name="brand" value="{{ old('brand', $product->brand) }}"></div>
            </div>
            <div class="mb-3"><label class="form-label">Nama produk (jenis kertas)</label>
                <input class="form-control" name="name" value="{{ old('name', $product->name) }}" required></div>
            <div class="mb-3"><label class="form-label">Deskripsi singkat</label>
                <textarea class="form-control" name="description" rows="2">{{ old('description', $product->description) }}</textarea></div>
            <div class="mb-3"><label class="form-label">Spesifikasi (satu baris per poin)</label>
                <textarea class="form-control" name="specification" rows="3">{{ old('specification', $product->specification) }}</textarea></div>
            <div class="form-check mb-3">
                <input type="hidden" name="is_active" value="0">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $product->is_active))>
                <label class="form-check-label" for="is_active">Tampilkan di toko</label>
            </div>
            <button class="btn btn-primary">Simpan produk</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-gray-200">Kembali</a>
        </form>
    </div>
</div>

@if ($product->exists)
{{-- 2. Varian: ukuran, gramatur, harga, stok --}}
<div class="card border-0 shadow mb-4">
    <div class="card-header"><h2 class="h5 mb-0">Varian (ukuran, gramatur, harga, stok)</h2></div>
    <div class="table-responsive">
        <table class="table align-items-center mb-0">
            <thead class="thead-light">
                <tr><th>Ukuran</th><th>Dimensi</th><th>Gsm</th><th>Satuan</th><th>Isi</th><th>Harga (Rp)</th><th>Stok</th><th>Min</th><th>Aktif</th><th></th></tr>
            </thead>
            <tbody>
            @foreach ($product->variants as $v)
                <tr>
                    <td><input form="vf-{{ $v->id }}" class="form-control form-control-sm" name="size_name" value="{{ $v->size_name }}" required style="min-width:70px"></td>
                    <td><input form="vf-{{ $v->id }}" class="form-control form-control-sm" name="dimension" value="{{ $v->dimension }}" style="min-width:110px"></td>
                    <td><input form="vf-{{ $v->id }}" type="number" class="form-control form-control-sm" name="gsm" value="{{ $v->gsm }}" style="min-width:70px"></td>
                    <td><input form="vf-{{ $v->id }}" class="form-control form-control-sm" name="unit" value="{{ $v->unit }}" required style="min-width:70px"></td>
                    <td><input form="vf-{{ $v->id }}" class="form-control form-control-sm" name="content" value="{{ $v->content }}" style="min-width:100px"></td>
                    <td><input form="vf-{{ $v->id }}" type="number" min="0" class="form-control form-control-sm" name="price" value="{{ $v->price }}" required style="min-width:100px"></td>
                    <td><input form="vf-{{ $v->id }}" type="number" min="0" class="form-control form-control-sm" name="stock" value="{{ $v->stock }}" required style="min-width:80px"></td>
                    <td><input form="vf-{{ $v->id }}" type="number" min="1" class="form-control form-control-sm" name="min_qty" value="{{ $v->min_qty }}" required style="min-width:60px"></td>
                    <td>
                        <input form="vf-{{ $v->id }}" type="hidden" name="is_active" value="0">
                        <input form="vf-{{ $v->id }}" class="form-check-input" type="checkbox" name="is_active" value="1" @checked($v->is_active)>
                    </td>
                    <td class="text-nowrap">
                        <button form="vf-{{ $v->id }}" class="btn btn-sm btn-primary">Simpan</button>
                        <button form="vd-{{ $v->id }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus varian ini?')">Hapus</button>
                    </td>
                </tr>
            @endforeach
            @if ($product->variants->isEmpty())
                <tr><td colspan="10" class="text-center text-gray-500">Belum ada varian. Produk tidak tampil di toko sebelum punya varian aktif.</td></tr>
            @endif
            </tbody>
        </table>
    </div>
    {{-- Form disimpan di luar tabel (HTML tidak mengizinkan form di dalam tr) --}}
    @foreach ($product->variants as $v)
        <form id="vf-{{ $v->id }}" method="POST" action="{{ route('admin.variants.update', $v) }}">@csrf @method('PUT')</form>
        <form id="vd-{{ $v->id }}" method="POST" action="{{ route('admin.variants.destroy', $v) }}">@csrf @method('DELETE')</form>
    @endforeach

    <div class="card-body border-top">
        <h3 class="h6">Tambah varian baru</h3>
        <form method="POST" action="{{ route('admin.products.variants.store', $product) }}" class="row g-2">
            @csrf
            <div class="col-6 col-md-2"><input class="form-control" name="size_name" placeholder="Ukuran (A4)" required></div>
            <div class="col-6 col-md-2"><input class="form-control" name="dimension" placeholder="210 x 297 mm"></div>
            <div class="col-6 col-md-1"><input type="number" class="form-control" name="gsm" placeholder="Gsm"></div>
            <div class="col-6 col-md-1"><input class="form-control" name="unit" value="rim" required></div>
            <div class="col-6 col-md-2"><input class="form-control" name="content" placeholder="500 lembar"></div>
            <div class="col-6 col-md-2"><input type="number" min="0" class="form-control" name="price" placeholder="Harga" required></div>
            <div class="col-6 col-md-1"><input type="number" min="0" class="form-control" name="stock" placeholder="Stok" value="0" required></div>
            <div class="col-6 col-md-1"><input type="number" min="1" class="form-control" name="min_qty" value="1" title="Minimal pesan" required></div>
            <div class="col-6 col-md-2"><input class="form-control" name="sku" placeholder="SKU (opsional)"></div>
            <input type="hidden" name="is_active" value="1">
            <div class="col-12 col-md-2"><button class="btn btn-primary w-100">+ Tambah varian</button></div>
        </form>
    </div>
</div>

{{-- 3. Foto --}}
<div class="card border-0 shadow mb-4">
    <div class="card-header"><h2 class="h5 mb-0">Foto produk</h2></div>
    <div class="card-body">
        <div class="d-flex flex-wrap gap-3 mb-3">
            @forelse ($product->images as $img)
                <div class="text-center">
                    <img src="{{ $img->url }}" alt="" width="120" height="90" class="rounded d-block mb-1" style="object-fit:cover">
                    <form method="POST" action="{{ route('admin.images.destroy', $img) }}" onsubmit="return confirm('Hapus foto ini?')">
                        @csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button>
                    </form>
                </div>
            @empty
                <p class="text-gray-500 mb-0">Belum ada foto (toko memakai gambar bawaan).</p>
            @endforelse
        </div>
        <form method="POST" action="{{ route('admin.products.images.store', $product) }}" enctype="multipart/form-data" class="d-flex flex-wrap gap-2">
            @csrf
            <input type="file" name="images[]" multiple accept=".jpg,.jpeg,.png,.webp" class="form-control" style="max-width:360px" required>
            <button class="btn btn-primary">Unggah foto</button>
            <small class="text-gray-500 align-self-center">JPG/PNG/WEBP, maks 2 MB per foto. Foto pertama jadi foto utama.</small>
        </form>
    </div>
</div>
@endif
@endsection
