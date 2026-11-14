@extends('layouts.app')
@section('title', 'FAQ')

@section('content')
<div class="container mx-auto max-w-3xl px-4 py-10">
    <h1 class="mb-6 text-3xl font-semibold">Pertanyaan Umum</h1>
    <div class="space-y-3">
        @forelse ($faqs as $faq)
            <details class="card group p-4">
                <summary class="cursor-pointer font-medium">{{ $faq->question }}</summary>
                <p class="mt-3 whitespace-pre-line text-sm text-muted-foreground">{{ $faq->answer }}</p>
            </details>
        @empty
            <p class="text-muted-foreground">Belum ada FAQ.</p>
        @endforelse
    </div>
</div>
@endsection
