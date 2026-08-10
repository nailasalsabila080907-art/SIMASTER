@extends('layouts.app')
@section('title', 'Detail Surat')

@section('content')
<div class="max-w-2xl">
    @if(session('sukses'))<div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">{{ session('sukses') }}</div>@endif
    @if(session('gagal'))<div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">{{ session('gagal') }}</div>@endif

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="font-display text-lg" style="color: var(--navy);">{{ $suratKeluar->perihal }}</h2>
                <p class="text-sm mt-1" style="color: var(--ink-muted);">{{ $suratKeluar->kategori->nama_kategori }} &middot; {{ $suratKeluar->tanggal_surat?->format('d M Y') }}</p>
            </div>
            @php $warna = ['draft'=>'#767C86','diajukan'=>'#C9972F','disetujui'=>'#2F6B4F','ditolak'=>'#B0432E','terkirim'=>'#16324F','diarsipkan'=>'#767C86']; @endphp
            <span class="text-xs px-2.5 py-1 rounded" style="background: {{ $warna[$suratKeluar->status] }}22; color: {{ $warna[$suratKeluar->status] }};">
                {{ ucfirst($suratKeluar->status) }}
            </span>
        </div>

        @if($suratKeluar->nomor_surat)
            <p class="mt-4 text-sm font-mono px-3 py-2 rounded" style="background: var(--paper);">{{ $suratKeluar->nomor_surat }}</p>
        @endif

        @if($suratKeluar->isi_surat)
            <div class="mt-5 prose prose-sm max-w-none border-t border-gray-100 pt-5" style="color: var(--ink);">
                {!! $suratKeluar->isi_surat !!}
            </div>
        @else
            <div class="mt-5 space-y-3">
                @foreach($suratKeluar->data_variabel ?? [] as $key => $value)
                    @php $labelVar = $suratKeluar->template->variabel->firstWhere('nama_variabel', $key)?->label ?? $key; @endphp
                    <div class="flex text-sm">
                        <span class="w-48 shrink-0" style="color: var(--ink-muted);">{{ $labelVar }}</span>
                        <span>{{ $value }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        @if($suratKeluar->status === 'draft' && $suratKeluar->dibuat_oleh === auth()->id())
            <form method="POST" action="{{ route('surat-keluar.ajukan', $suratKeluar) }}" class="mt-6">
                @csrf
                <button type="submit" class="px-5 py-2.5 rounded-lg text-white text-sm font-medium" style="background: var(--navy);">
                    Ajukan untuk Persetujuan
                </button>
            </form>
        @endif

        @if($suratKeluar->status === 'terkirim')
            <a href="{{ route('surat-keluar.cetak-pdf', $suratKeluar) }}" target="_blank" class="inline-block mt-6 px-5 py-2.5 rounded-lg text-white text-sm font-medium" style="background: var(--gold);">
                Cetak PDF
            </a>
        @endif
    </div>

    {{-- Riwayat approval --}}
    @if($suratKeluar->approval->isNotEmpty())
        <div class="mt-6">
            <h3 class="font-display text-base mb-3" style="color: var(--navy);">Riwayat Persetujuan</h3>
            <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">
                @foreach($suratKeluar->approval as $a)
                    <div class="px-5 py-3 flex items-center justify-between text-sm">
                        <div>
                            <p>{{ $a->pegawaiPemberiApproval->nama_lengkap ?? '-' }}</p>
                            @if($a->catatan)<p class="text-xs mt-0.5" style="color: var(--ink-muted);">{{ $a->catatan }}</p>@endif
                        </div>
                        @php $wStatus = ['menunggu'=>'#C9972F','disetujui'=>'#2F6B4F','ditolak'=>'#B0432E']; @endphp
                        <span class="text-xs px-2 py-0.5 rounded" style="background: {{ $wStatus[$a->status] }}22; color: {{ $wStatus[$a->status] }};">
                            {{ ucfirst($a->status) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
