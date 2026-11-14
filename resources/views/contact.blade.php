@extends('layouts.app')
@section('title', 'Hubungi Kami')

@section('content')
<div class="container mx-auto grid gap-10 px-4 py-10 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <h1 class="mb-2 text-3xl font-semibold">Hubungi Kami</h1>
        <p class="mb-6 text-muted-foreground">Tanya stok, ukuran khusus, atau pesanan dalam jumlah besar. Kami balas secepatnya.</p>

        <form method="POST" action="{{ route('contact.store') }}" class="card space-y-4 p-6">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <div><label class="label">Nama</label><input class="input" name="name" value="{{ old('name') }}" required>
                    @error('name')<p class="err">{{ $message }}</p>@enderror</div>
                <div><label class="label">Email</label><input class="input" type="email" name="email" value="{{ old('email') }}" required>
                    @error('email')<p class="err">{{ $message }}</p>@enderror</div>
            </div>
            <div><label class="label">Nomor telepon (opsional)</label><input class="input" name="phone" value="{{ old('phone') }}">
                @error('phone')<p class="err">{{ $message }}</p>@enderror</div>
            <div><label class="label">Subjek</label><input class="input" name="subject" value="{{ old('subject') }}" required>
                @error('subject')<p class="err">{{ $message }}</p>@enderror</div>
            <div><label class="label">Pesan</label><textarea class="input" name="body" rows="6" required>{{ old('body') }}</textarea>
                @error('body')<p class="err">{{ $message }}</p>@enderror</div>
            <div class="hidden" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
            <button class="btn btn-primary">Kirim Pesan</button>
        </form>
    </div>

    <aside class="card h-fit space-y-2 p-6 text-sm">
        <h2 class="text-xl font-semibold">Informasi toko</h2>
        <p>{{ $s('address') }}</p>
        <p>{{ $s('open_hours') }}</p>
        <p>{{ $s('email') }}</p>
        @if ($s('whatsapp'))<a class="btn btn-outline mt-2 w-full" href="https://wa.me/{{ $s('whatsapp') }}" target="_blank" rel="noopener">Chat WhatsApp</a>@endif
    </aside>
</div>
@endsection
