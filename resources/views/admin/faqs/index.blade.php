@extends('layouts.admin.app')
@section('title', 'FAQ')

@section('content')
<div class="mb-3"><a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">+ Tambah FAQ</a></div>
<div class="card border-0 shadow">
    <div class="table-responsive">
        <table class="table table-hover align-items-center mb-0">
            <thead class="thead-light"><tr><th>Urutan</th><th>Pertanyaan</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse ($faqs as $f)
                <tr>
                    <td>{{ $f->sort_order }}</td>
                    <td>{{ $f->question }}</td>
                    <td><span class="badge bg-{{ $f->is_active ? 'success' : 'secondary' }}">{{ $f->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td class="d-flex gap-2">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.faqs.edit', $f) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.faqs.destroy', $f) }}" onsubmit="return confirm('Hapus FAQ ini?')">
                            @csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-gray-500">Belum ada FAQ.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $faqs->links('pagination::bootstrap-5') }}</div>
@endsection
