@extends('layouts.app')
@section('title', 'Detail Surat')
@section('content')

@php
    $badge = [
        'draft'      => 'text-bg-secondary',
        'diajukan'   => 'text-bg-warning',
        'disetujui'  => 'text-bg-success',
        'ditolak'    => 'text-bg-danger',
        'terkirim'   => 'text-bg-primary',
        'diarsipkan' => 'text-bg-secondary',
    ][$suratKeluar->status] ?? 'text-bg-secondary';
@endphp

<div style="max-width:760px">

    <div class="d-flex align-items-start gap-3 mb-4">
        <a href="{{ route('surat-keluar.index') }}" class="btn btn-light rounded-circle d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:38px;height:38px" title="Kembali">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <p class="text-muted mb-1" style="font-size:.82rem">Persuratan / Surat Keluar</p>
            <h2 class="mb-0" style="font-size:1.4rem">Detail Surat</h2>
        </div>
    </div>

    @if(session('sukses'))
        <div class="alert alert-success rounded-3 d-flex align-items-center gap-2 mb-3" style="font-size:.85rem" role="alert">
            <i class="bi bi-check-circle"></i>
            <div>{{ session('sukses') }}</div>
        </div>
    @endif
    @if(session('gagal'))
        <div class="alert alert-danger rounded-3 d-flex align-items-center gap-2 mb-3" style="font-size:.85rem" role="alert">
            <i class="bi bi-x-circle"></i>
            <div>{{ session('gagal') }}</div>
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex align-items-start justify-content-between gap-3">
                <div>
                    <h3 class="mb-1" style="font-size:1.15rem">{{ $suratKeluar->perihal }}</h3>
                    <p class="text-muted mb-0" style="font-size:.82rem">
                        {{ $suratKeluar->kategori->nama_kategori }} &middot; {{ $suratKeluar->tanggal_surat?->format('d M Y') }}
                    </p>
                </div>
                <span class="badge rounded-pill {{ $badge }}" style="font-size:.75rem;padding:.5rem .9rem">{{ ucfirst($suratKeluar->status) }}</span>
            </div>

            @if($suratKeluar->nomor_surat)
                <span class="d-inline-block font-monospace text-muted mt-3" style="font-size:.82rem;background:var(--surface);border-radius:8px;padding:.5rem .85rem">
                    {{ $suratKeluar->nomor_surat }}
                </span>
            @endif

            @if($suratKeluar->isi_surat)
                <div class="mt-4 pt-4 border-top" style="font-size:.88rem">
                    {!! $suratKeluar->isi_surat !!}
                </div>
            @else
                <div class="mt-4 pt-4 border-top">
                    <div class="row g-3">
                        @foreach($suratKeluar->data_variabel ?? [] as $key => $value)
                            @php $labelVar = $suratKeluar->template->variabel->firstWhere('nama_variabel', $key)?->label ?? $key; @endphp
                            <div class="col-md-6">
                                <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em">{{ $labelVar }}</p>
                                <p class="fw-semibold mb-0" style="font-size:.88rem">{{ $value }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(in_array($suratKeluar->status, ['draft', 'ditolak']) && ($suratKeluar->dibuat_oleh === auth()->id() || in_array(auth()->user()->role, ['admin_tu', 'super_admin'])))
                <div class="d-flex flex-wrap gap-2 mt-4 pt-4 border-top">
                    <a href="{{ route('surat-keluar.edit', $suratKeluar) }}" class="btn btn-light">Ubah Draft</a>
                    <form method="POST" action="{{ route('surat-keluar.ajukan', $suratKeluar) }}">
                        @csrf
                        <button type="submit" class="btn d-inline-flex align-items-center gap-2 text-white"
                                style="background:linear-gradient(135deg,#178754,#0EA5A4);border:none">
                            <i class="bi bi-send"></i> Ajukan untuk Persetujuan
                        </button>
                    </form>
                </div>
            @endif

            @if($suratKeluar->status === 'terkirim')
                <div class="d-flex flex-wrap gap-2 mt-4 pt-4 border-top">
                    <a href="{{ route('surat-keluar.cetak-pdf', $suratKeluar) }}" target="_blank"
                       class="btn d-inline-flex align-items-center gap-2 text-white"
                       style="background:linear-gradient(135deg,#D98C00,#F0A202);border:none">
                        <i class="bi bi-printer"></i> Cetak PDF
                    </a>
                    <form method="POST" action="{{ route('arsip.surat-keluar', $suratKeluar) }}">
                        @csrf
                        <button type="submit" class="btn btn-light d-inline-flex align-items-center gap-2">
                            <i class="bi bi-archive"></i> Arsipkan Surat
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    {{-- Riwayat approval --}}
    @if($suratKeluar->approval->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h3 class="mb-1" style="font-size:1.05rem">Riwayat Persetujuan</h3>
                <p class="text-muted mb-0" style="font-size:.78rem">{{ $suratKeluar->approval->count() }} tahap persetujuan</p>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($suratKeluar->approval as $a)
                        @php
                            $wStatus = [
                                'menunggu'  => ['bg' => '#FDF1E2', 'ink' => '#F7A02A', 'badge' => 'text-bg-light text-muted'],
                                'disetujui' => ['bg' => '#E6F5EC', 'ink' => '#178754', 'badge' => 'text-bg-success'],
                                'ditolak'   => ['bg' => '#FBEAE7', 'ink' => '#E5484D', 'badge' => 'text-bg-danger'],
                            ][$a->status] ?? ['bg' => '#F1F2F4', 'ink' => '#8B8D97', 'badge' => 'text-bg-light text-muted'];
                        @endphp
                        <div class="list-group-item d-flex align-items-center justify-content-between gap-3 py-3 px-3 border-0 border-bottom">
                            <div class="d-flex gap-3 min-w-0">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                      style="width:34px;height:34px;background:{{ $wStatus['bg'] }};color:{{ $wStatus['ink'] }};font-size:.6rem">
                                    <i class="bi bi-dot fs-4"></i>
                                </span>
                                <div class="min-w-0">
                                    <span class="d-block fw-semibold text-truncate" style="font-size:.85rem;color:var(--ink)">
                                        {{ $a->pegawaiPemberiApproval->nama_lengkap ?? '-' }}
                                    </span>
                                    @if($a->catatan)
                                        <span class="d-block text-muted" style="font-size:.76rem">{{ $a->catatan }}</span>
                                    @endif
                                </div>
                            </div>
                            <span class="badge rounded-pill {{ $wStatus['badge'] }} flex-shrink-0" style="font-size:.72rem">{{ ucfirst($a->status) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection