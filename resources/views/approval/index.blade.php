@extends('layouts.app')
@section('title', 'Approval Surat')

@section('content')
<div class="max-w-3xl">
    @if(session('sukses'))<div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">{{ session('sukses') }}</div>@endif
    @if(session('gagal'))<div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">{{ session('gagal') }}</div>@endif

    <p class="text-sm mb-4" style="color: var(--ink-muted);">Surat yang menunggu persetujuan Anda.</p>

    <div class="space-y-4">
        @forelse($approvalSaya as $a)
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="font-medium">{{ $a->suratKeluar->perihal }}</p>
                        <p class="text-xs mt-1" style="color: var(--ink-muted);">
                            {{ $a->suratKeluar->kategori->nama_kategori }} &middot;
                            Diajukan oleh {{ $a->suratKeluar->pembuat->pegawai->nama_lengkap ?? $a->suratKeluar->pembuat->username }}
                        </p>
                    </div>
                    <a href="{{ route('surat-keluar.show', $a->suratKeluar) }}" class="text-xs font-medium" style="color: var(--navy);">Lihat detail</a>
                </div>

                <div class="mt-4 flex gap-3">
                    <form method="POST" action="{{ route('approval.setujui', $a) }}">
                        @csrf
                        <button type="submit" class="text-sm px-4 py-2 rounded-lg text-white" style="background: #2F6B4F;">Setujui</button>
                    </form>
                    <button type="button" onclick="document.getElementById('tolak-{{ $a->id_approval }}').classList.toggle('hidden')"
                        class="text-sm px-4 py-2 rounded-lg border border-red-300 text-red-600">Tolak</button>
                </div>

                <form id="tolak-{{ $a->id_approval }}" method="POST" action="{{ route('approval.tolak', $a) }}" class="hidden mt-3">
                    @csrf
                    <textarea name="catatan" rows="2" placeholder="Alasan penolakan (wajib diisi)" class="w-full rounded-lg border border-gray-300 px-3.5 py-2 text-sm" required></textarea>
                    <button type="submit" class="mt-2 text-sm px-4 py-2 rounded-lg bg-red-600 text-white">Kirim Penolakan</button>
                </form>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-sm" style="color: var(--ink-muted);">
                Tidak ada surat yang menunggu persetujuan Anda saat ini.
            </div>
        @endforelse
    </div>
    <div class="mt-4">{{ $approvalSaya->links() }}</div>
</div>
@endsection
