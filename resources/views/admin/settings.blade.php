@extends('layouts.admin.app')
@section('title', 'Pengaturan Toko')

@section('content')
<div class="card border-0 shadow col-12 col-lg-8">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf @method('PUT')
            @foreach ($fields as $key => $label)
                <div class="mb-3">
                    <label class="form-label">{{ $label }}</label>
                    @if (in_array($key, ['hero_text', 'shipping_note', 'tagline', 'address']))
                        <textarea class="form-control" name="{{ $key }}" rows="2">{{ old($key, $s($key)) }}</textarea>
                    @else
                        <input class="form-control" name="{{ $key }}" value="{{ old($key, $s($key)) }}">
                    @endif
                </div>
            @endforeach
            <button class="btn btn-primary">Simpan pengaturan</button>
        </form>
    </div>
</div>
@endsection
