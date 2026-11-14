@extends('layouts.admin.app')
@section('title', 'Pesan')

@section('content')
<div class="card border-0 shadow col-12 col-lg-8">
    <div class="card-body">
        <h2 class="h5">{{ $message->subject }}</h2>
        <p class="small text-gray-600">Dari <strong>{{ $message->name }}</strong> &lt;{{ $message->email }}&gt;@if ($message->phone) &middot; {{ $message->phone }}@endif
            <br>{{ $message->created_at->format('d/m/Y H:i') }}</p>
        <p class="border-top pt-3 mb-0" style="white-space:pre-line">{{ $message->body }}</p>
    </div>
</div>
<a href="{{ route('admin.messages.index') }}" class="btn btn-gray-200 mt-3">&larr; Kembali</a>
@endsection
