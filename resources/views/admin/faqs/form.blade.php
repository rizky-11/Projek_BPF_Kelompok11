@extends('layouts.admin.app')
@section('title', $faq->exists ? 'Edit FAQ' : 'Tambah FAQ')

@section('content')
<div class="card border-0 shadow col-12 col-lg-8">
    <div class="card-body">
        <form method="POST" action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}">
            @csrf
            @if ($faq->exists) @method('PUT') @endif
            <div class="mb-3"><label class="form-label">Pertanyaan</label>
                <input class="form-control" name="question" value="{{ old('question', $faq->question) }}" required></div>
            <div class="mb-3"><label class="form-label">Jawaban</label>
                <textarea class="form-control" name="answer" rows="4" required>{{ old('answer', $faq->answer) }}</textarea></div>
            <div class="mb-3"><label class="form-label">Urutan tampil</label>
                <input type="number" min="0" class="form-control" name="sort_order" value="{{ old('sort_order', $faq->sort_order) }}"></div>
            <div class="form-check mb-4">
                <input type="hidden" name="is_active" value="0">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $faq->is_active))>
                <label class="form-check-label" for="is_active">Aktif</label>
            </div>
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.faqs.index') }}" class="btn btn-gray-200">Batal</a>
        </form>
    </div>
</div>
@endsection
