@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
<div class="max-w-5xl">
    <p class="text-sm mb-6" style="color: var(--ink-muted);">
        @if($bolehLihatSemua)
            Riwayat aktivitas seluruh pengguna sistem.
        @else
            Riwayat aktivitas Anda di sistem.
        @endif
    </p>

    {{-- Filter --}}
    <form method="GET" class="flex flex-wrap gap-3 mb-4">
        @if($bolehLihatSemua)
            <select name="user_id" onchange="this.form.submit()" class="text-sm rounded-lg border border-gray-300 px-3 py-2 bg-white">
                <option value="">Semua pengguna</option>
                @foreach($daftarUser as $u)
                    <option value="{{ $u->id_user }}" {{ (string) $filterUserId === (string) $u->id_user ? 'selected' : '' }}>
                        {{ $u->pegawai->nama_lengkap ?? $u->username }}
                    </option>
                @endforeach
            </select>
        @endif

        <select name="aktivitas" onchange="this.form.submit()" class="text-sm rounded-lg border border-gray-300 px-3 py-2 bg-white">
            <option value="">Semua aktivitas</option>
            @foreach(['login', 'logout', 'lihat_halaman', 'tambah_data', 'ubah_data', 'hapus_data'] as $opsi)
                <option value="{{ $opsi }}" {{ $filterAktivitas === $opsi ? 'selected' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $opsi)) }}
                </option>
            @endforeach
        </select>

        @if($filterUserId || $filterAktivitas)
            <a href="{{ route('log-aktivitas.index') }}" class="text-sm px-3 py-2" style="color: var(--ink-muted);">Reset filter</a>
        @endif
    </form>

    {{-- Tabel log --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-100" style="color: var(--ink-muted);">
                    @if($bolehLihatSemua)
                        <th class="px-5 py-3 font-medium">Pengguna</th>
                    @endif
                    <th class="px-5 py-3 font-medium">Aktivitas</th>
                    <th class="px-5 py-3 font-medium">Deskripsi</th>
                    <th class="px-5 py-3 font-medium">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                    <tr>
                        @if($bolehLihatSemua)
                            <td class="px-5 py-3">{{ $log->user->pegawai->nama_lengkap ?? $log->user->username ?? '-' }}</td>
                        @endif
                        <td class="px-5 py-3">
                            <span class="inline-block px-2 py-0.5 rounded text-xs" style="background: #EAF0F6; color: var(--navy);">
                                {{ ucwords(str_replace('_', ' ', $log->aktivitas)) }}
                            </span>
                        </td>
                        <td class="px-5 py-3" style="color: var(--ink);">{{ $log->deskripsi ?? '-' }}</td>
                        <td class="px-5 py-3 whitespace-nowrap" style="color: var(--ink-muted);">{{ $log->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $bolehLihatSemua ? 4 : 3 }}" class="px-5 py-8 text-center" style="color: var(--ink-muted);">
                            Belum ada aktivitas tercatat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
@endsection
