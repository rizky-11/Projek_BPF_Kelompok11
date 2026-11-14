@extends('layouts.admin.app')
@section('title', 'Pesan Masuk')

@section('content')
<div class="card border-0 shadow">
    <div class="table-responsive">
        <table class="table table-hover align-items-center mb-0">
            <thead class="thead-light"><tr><th>Pengirim</th><th>Subjek</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse ($messages as $m)
                <tr class="{{ $m->is_read ? '' : 'fw-bold' }}">
                    <td>{{ $m->name }}<br><small class="text-gray-500 fw-normal">{{ $m->email }}</small></td>
                    <td>{{ $m->subject }}</td>
                    <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
                    <td><span class="badge bg-{{ $m->is_read ? 'secondary' : 'info' }}">{{ $m->is_read ? 'Dibaca' : 'Baru' }}</span></td>
                    <td class="d-flex gap-2">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.messages.show', $m) }}">Buka</a>
                        <form method="POST" action="{{ route('admin.messages.destroy', $m) }}" onsubmit="return confirm('Hapus pesan ini?')">
                            @csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-gray-500">Belum ada pesan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $messages->links('pagination::bootstrap-5') }}</div>
@endsection
