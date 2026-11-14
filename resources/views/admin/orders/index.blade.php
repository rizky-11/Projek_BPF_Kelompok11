@extends('layouts.admin.app')
@section('title', 'Pesanan')

@section('content')
<form method="GET" class="d-flex flex-wrap gap-2 mb-3">
    <select name="status" class="form-select" style="max-width:240px">
        <option value="">Semua status</option>
        @foreach (\App\Models\Order::STATUSES as $key => [$label])
            <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
        @endforeach
    </select>
    <input class="form-control" style="max-width:280px" name="q" value="{{ request('q') }}" placeholder="Kode / nama / telepon">
    <button class="btn btn-gray-800">Filter</button>
</form>
<div class="card border-0 shadow">
    <div class="table-responsive">
        <table class="table table-hover align-items-center mb-0">
            <thead class="thead-light"><tr><th>Kode</th><th>Tanggal</th><th>Pemesan</th><th>Item</th><th>Total</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse ($orders as $o)
                <tr>
                    <td class="fw-bold">{{ $o->order_code }}</td>
                    <td>{{ $o->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $o->customer_name }}<br><small class="text-gray-500">{{ $o->customer_phone }}</small></td>
                    <td>{{ $o->items_count }}</td>
                    <td>Rp {{ number_format($o->total, 0, ',', '.') }}</td>
                    <td><span class="badge bg-{{ $o->status_color }}">{{ $o->status_label }}</span></td>
                    <td><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.orders.show', $o) }}">Detail</a></td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-gray-500">Tidak ada pesanan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $orders->links('pagination::bootstrap-5') }}</div>
@endsection
