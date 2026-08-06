@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-5xl">
    <p class="text-sm" style="color: var(--ink-muted);">
        Selamat datang kembali, <span style="color: var(--ink);">{{ $user->pegawai->nama_lengkap ?? $user->username }}</span>.
    </p>

    {{-- Kartu statistik --}}
    <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($statistik as $item)
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-2xl font-semibold font-display" style="color: var(--navy);">{{ $item['nilai'] }}</p>
                <p class="mt-1 text-xs" style="color: var(--ink-muted);">{{ $item['label'] }}</p>
            </div>
        @endforeach

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-2xl font-semibold font-display" style="color: var(--gold);">{{ $notifikasiBelumDibaca }}</p>
            <p class="mt-1 text-xs" style="color: var(--ink-muted);">Notifikasi Belum Dibaca</p>
        </div>
    </div>

    {{-- Aktivitas terbaru --}}
    <div class="mt-8">
        <h2 class="font-display text-base" style="color: var(--navy);">Aktivitas Terakhir Anda</h2>
        <div class="mt-3 bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">
            @forelse($aktivitasTerbaru as $log)
                <div class="px-5 py-3 flex items-center justify-between text-sm">
                    <span>{{ $log->deskripsi ?? $log->aktivitas }}</span>
                    <span class="text-xs" style="color: var(--ink-muted);">{{ $log->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <div class="px-5 py-6 text-sm text-center" style="color: var(--ink-muted);">
                    Belum ada aktivitas tercatat.
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-6 rounded-xl border border-dashed border-gray-300 p-5 text-sm" style="color: var(--ink-muted);">
        Menu di sidebar sebelah kiri yang masih redup (Surat Keluar, Surat Masuk, dll) adalah fitur yang belum dibangun — akan aktif satu per satu di fase-fase selanjutnya.
    </div>
</div>
@endsection
