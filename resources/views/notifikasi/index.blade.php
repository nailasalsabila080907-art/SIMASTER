@extends('layouts.app')
@section('title', 'Notifikasi')

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center justify-between mb-4">
        <p class="text-sm" style="color: var(--ink-muted);">Semua notifikasi untuk Anda.</p>
        <form method="POST" action="{{ route('notifikasi.tandai-semua') }}">
            @csrf
            <button type="submit" class="text-xs font-medium" style="color: var(--navy);">Tandai semua sudah dibaca</button>
        </form>
    </div>

    @if(session('sukses'))<div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">{{ session('sukses') }}</div>@endif

    <div class="space-y-2">
        @forelse($notifikasi as $n)
            <form method="POST" action="{{ route('notifikasi.tandai-dibaca', $n) }}">
                @csrf
                <button type="submit" class="w-full text-left bg-white rounded-xl border {{ $n->sudah_dibaca ? 'border-gray-200' : 'border-gray-300' }} p-4 flex items-start gap-3 hover:bg-gray-50">
                    @unless($n->sudah_dibaca)
                        <span class="w-2 h-2 rounded-full mt-1.5 shrink-0" style="background: var(--gold);"></span>
                    @else
                        <span class="w-2 h-2 shrink-0"></span>
                    @endunless
                    <div class="flex-1">
                        <p class="text-sm font-medium">{{ $n->judul }}</p>
                        <p class="text-sm mt-0.5" style="color: var(--ink-muted);">{{ $n->pesan }}</p>
                        <p class="text-xs mt-1.5" style="color: var(--ink-muted);">{{ $n->created_at->diffForHumans() }}</p>
                    </div>
                </button>
            </form>
        @empty
            <div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-sm" style="color: var(--ink-muted);">
                Belum ada notifikasi.
            </div>
        @endforelse
    </div>
    <div class="mt-4">{{ $notifikasi->links() }}</div>
</div>
@endsection
