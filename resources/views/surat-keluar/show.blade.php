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

    {{-- 
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

    notif berhasil/gagal tampil otomatis pakai toast global
    --}}

    <div class="card mb-3">
        <div class="card-body">

            @if(! $suratKeluar->isDraft())
                {{-- Kop Surat, disamakan dengan tampilan cetak-pdf.blade.php --}}
                @php
                    $logoKiri = $sekolah?->logo_path
                        ? asset('storage/' . $sekolah->logo_path)
                        : null;
                    $logoKanan = asset('images/logo-tut-wuri.png.jpg');
                @endphp
                <div class="d-flex align-items-center gap-3 pb-3 mb-4" style="border-bottom:3px double #000;">
                    <div class="flex-shrink-0" style="width:68px;text-align:center;">
                        @if($logoKiri)
                            <img src="{{ $logoKiri }}" alt="Logo Sekolah" style="width:72px;height:100px;object-fit:contain;transform:translateX(+20px);">
                        @endif
                    </div>
                    <div class="flex-grow-1 text-center" style="font-family:'Times New Roman', Times, serif; color:#000;">
                        <p class="mb-0 fw-bold" style="font-size:1.05rem;>PEMERINTAH PROVINSI RIAU</p>
                        <p class="mb-0 fw-bold" style="font-size:1rem;">DINAS PENDIDIKAN</p>
                        <p class="mb-1 fw-bold" style="font-size:.88rem;">SEKOLAH MENENGAH KEJURUAN (SMK) NEGERI 7 PEKANBARU</p>
                        <p class="mb-1" style="font-size:.68rem;">
                            {{ $sekolah->alamat ?? '-' }} {{ $sekolah->kota ?? 'Pekanbaru' }} {{ $sekolah->provinsi ?? 'Riau' }} {{ $sekolah->kode_pos ?? '' }}
                        </p>
                        <p class="mb-1" style="font-size:.68rem;">
                            E-mail: {{ $sekolah->email ?? '-' }} &nbsp;&nbsp; Website: {{ $sekolah->website ?? '-' }} &nbsp;&nbsp; Telp: {{ $sekolah->telepon ?? '-' }}
                        </p>
                        <p class="mb-0" style="font-size:.68rem;">
                            NPSN: {{ $sekolah->npsn ?? '-' }} &nbsp;&nbsp; NSS: {{ $sekolah->nss ?? '16120632160' }}
                        </p>
                    </div>
                    <div class="flex-shrink-0" style="width:75px;text-align:center;">
                        <img src="{{ $logoKanan }}" alt="Tut Wuri Handayani" style="width:100px;height:100px;object-fit:contain;transform:translateX(-27px);">
                    </div>
                </div>
            @endif

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

            @if(in_array($suratKeluar->status, ['terkirim', 'diarsipkan'], true))
                <div class="d-flex flex-wrap gap-2 mt-4 pt-4 border-top">
                    <a href="{{ route('surat-keluar.cetak-pdf', $suratKeluar) }}" target="_blank"
                       class="btn d-inline-flex align-items-center gap-2 text-white"
                       style="background:linear-gradient(135deg,#D98C00,#F0A202);border:none">
                        <i class="bi bi-printer"></i> Cetak PDF
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- perubahan saran pak zaki: button approve ada di detail surat juga --}}
    @if($approvalSaya)
        <div class="card mb-3" style="border:1px solid var(--bs-primary)">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-patch-check" style="color:var(--bs-primary);font-size:1.1rem"></i>
                    <h3 class="mb-0" style="font-size:1rem">Surat ini menunggu persetujuan Anda</h3>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-sm text-white"
                        style="background:var(--bs-primary);border-radius:8px;font-weight:600;"
                        data-bs-toggle="modal" data-bs-target="#modalKonfirmasiSetujuiDetail">
                        <i class="bi bi-check2"></i> Setujui
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger"
                        style="border-radius:8px;font-weight:600;"
                        data-bs-toggle="collapse" data-bs-target="#tolakDariDetail">
                        <i class="bi bi-x-lg"></i> Tolak
                    </button>
                </div>
                <div class="collapse mt-3" id="tolakDariDetail">
                    <form method="POST" action="{{ route('approval.tolak', ['approval' => $approvalSaya->id_approval]) }}">
                        @csrf
                        <textarea name="catatan" rows="3" required
                                    class="form-control mb-2"
                                    style="border-radius:10px;border-color:var(--border);font-size:.85rem;"
                                    placeholder="Masukkan alasan penolakan..."></textarea>
                        <button type="submit" class="btn btn-sm btn-danger" style="border-radius:8px;font-weight:600;">
                            Konfirmasi Tolak
                        </button>
                    </form> 
                </div>
            </div>
        </div>

        {{--  modal konfirmasi setujui --}}
        <div class="modal fade" id="modalKonfirmasiSetujuiDetail" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius:16px;border:none;">
                    <div class="modal-body text-center p-4">
                        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center"
                             style="width:56px;height:56px;border-radius:50%;background:var(--primary-light);">
                            <i class="bi bi-patch-check" style="font-size:1.5rem;color:var(--bs-primary);"></i>
                        </div>
                        <h5 class="fw-bold mb-1">Setujui surat ini?</h5>
                        <p class="text-muted mb-4" style="font-size:.85rem;">
                            Surat "{{ $suratKeluar->perihal }}" akan disetujui dan diteruskan ke tahap berikutnya.
                        </p>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light w-50" data-bs-dismiss="modal" style="border-radius:10px;">
                                Batal
                            </button>
                            <button type="submit" form="formSetujuiDetail" class="btn w-50"
                                    style="border-radius:10px;background:var(--bs-primary);color:#fff;">
                                Ya, Setujui
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form id="formSetujuiDetail" method="POST" action="{{ route('approval.setujui', ['approval' => $approvalSaya->id_approval]) }}">
            @csrf
        </form>
    @endif

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