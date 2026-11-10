@extends('layouts.app')
@section('title','Detail Surat Masuk')
@section('content')

@php
    $badge = [
        'baru'                      => 'text-bg-warning',
        'menunggu_disposisi_kepsek' => 'text-bg-primary',
        'menunggu_disposisi_tu'     => 'text-bg-info',
        'didisposisi'               => 'text-bg-info',
        'diproses'                  => 'text-bg-primary',
        'selesai'                   => 'text-bg-success',
        'diarsipkan'                => 'text-bg-secondary',
    ][$suratMasuk->status] ?? 'text-bg-secondary';

    $labelStatus = [
        'baru'                      => 'Baru',
        'menunggu_disposisi_kepsek' => 'Menunggu Arahan Kepsek',
        'menunggu_disposisi_tu'     => 'Menunggu Disposisi TU',
        'didisposisi'               => 'Didisposisi',
        'diproses'                  => 'Diproses',
        'selesai'                   => 'Selesai',
        'diarsipkan'                => 'Diarsipkan',
    ][$suratMasuk->status] ?? ucfirst($suratMasuk->status);
@endphp

<div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-4">
    <div class="d-flex align-items-start gap-3">
        <a href="{{ route('surat-masuk.index') }}" class="btn btn-light rounded-circle d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:38px;height:38px" title="Kembali">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <p class="text-muted mb-1" style="font-size:.82rem">Persuratan / Surat Masuk</p>
            <h2 class="mb-1" style="font-size:1.4rem">{{ $suratMasuk->perihal }}</h2>
            <p class="text-muted mb-0" style="font-size:.78rem">Dari {{ $suratMasuk->asal_instansi }} &middot; Agenda {{ $suratMasuk->nomor_surat_masuk }}</p>
        </div>
    </div>
    <span class="badge rounded-pill {{ $badge }}" style="font-size:.75rem;padding:.5rem .9rem">{{ $labelStatus }}</span>
</div>

@if(session('sukses'))
    <div class="alert alert-success rounded-3 d-flex align-items-center gap-2 mb-4" style="font-size:.85rem" role="alert">
        <i class="bi bi-check-circle"></i>
        <div>{{ session('sukses') }}</div>
    </div>
@endif

<div class="row g-3 mb-3">
    @php
        $ringkasan = [
            ['icon' => 'bi-file-earmark-text', 'label' => 'Nomor Asal', 'nilai' => $suratMasuk->nomor_surat_asal ?? '-', 'grad' => 'linear-gradient(135deg,#0F5C39,#178754)'],
            ['icon' => 'bi-calendar3', 'label' => 'Tanggal Surat', 'nilai' => $suratMasuk->tanggal_surat?->format('d/m/Y') ?? '-', 'grad' => 'linear-gradient(135deg,#0EA5A4,#22C3A6)'],
            ['icon' => 'bi-calendar-check', 'label' => 'Diterima', 'nilai' => $suratMasuk->tanggal_diterima?->format('d/m/Y'), 'grad' => 'linear-gradient(135deg,#178754,#4FBE85)'],
            ['icon' => 'bi-flag', 'label' => 'Sifat', 'nilai' => ucfirst($suratMasuk->sifat_surat), 'grad' => 'linear-gradient(135deg,#D98C00,#F0A202)'],
        ];
    @endphp
    @foreach($ringkasan as $r)
        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white flex-shrink-0"
                          style="width:38px;height:38px;background:{{ $r['grad'] }};font-size:1rem">
                        <i class="bi {{ $r['icon'] }}"></i>
                    </span>
                    <div class="min-w-0">
                        <p class="text-muted mb-0 text-truncate" style="font-size:.72rem">{{ $r['label'] }}</p>
                        <p class="fw-semibold mb-0 text-truncate" style="font-size:.9rem">{{ $r['nilai'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

@if($suratMasuk->file_scan_path)
    <div class="mb-3">
        <a target="_blank" href="{{ asset('storage/' . $suratMasuk->file_scan_path) }}"
           class="d-inline-flex align-items-center gap-2 text-decoration-none" style="font-size:.85rem">
            <i class="bi bi-file-earmark-arrow-up"></i> Buka scan surat
        </a>
    </div>
@endif

{{-- ========================================================= --}}
{{-- 1. AKSI ADMIN TU: Mengajukan Surat ke Kepala Sekolah        --}}
{{-- ========================================================= --}}
@if($suratMasuk->status === 'baru' && in_array(auth()->user()->role, ['admin_tu', 'super_admin'], true))
    <div class="card mb-3 border-warning">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1" style="font-size:1rem">Ajukan ke Kepala Sekolah</h5>
                <p class="text-muted mb-0" style="font-size:.8rem">Surat baru masuk. Ajukan ke Kepala Sekolah untuk mendapatkan catatan atau arahan.</p>
            </div>
            <form method="POST" action="{{ route('surat-masuk.ajukan-kepsek', $suratMasuk) }}">
                @csrf
                <button type="submit" class="btn btn-warning btn-sm text-dark fw-semibold">
                    <i class="bi bi-arrow-up-right-circle"></i> Ajukan ke Kepala Sekolah
                </button>
            </form>
        </div>
    </div>
@endif

{{-- ========================================================= --}}
{{-- 2. AKSI KEPALA SEKOLAH: Memberikan Catatan & Arahan        --}}
{{-- ========================================================= --}}
@if($suratMasuk->status === 'menunggu_disposisi_kepsek' && auth()->user()->role === 'kepala_sekolah')
    <div class="card mb-3 border-primary">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0" style="font-size:1rem"><i class="bi bi-journal-text"></i> Lembar Disposisi / Arahan Kepala Sekolah</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('surat-masuk.beri-arahan', $suratMasuk) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:.85rem">Catatan / Arahan Pimpinan</label>
                    <textarea name="catatan_kepsek" rows="4" class="form-control" required placeholder="Tulis catatan atau arahan di sini (contoh: 'Dilanjutkan ke bidang terkait' atau 'Arsipkan saja')..."></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:.85rem">Pilih Aksi Lanjutan</label>
                    <select name="aksi_kepsek" class="form-select" required>
                        <option value="lanjut_tu">Teruskan ke Admin TU untuk Disposisi</option>
                        <option value="arsip">Langsung Arsipkan Surat Ini</option>
                    </select>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-send"></i> Kirim Arahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

{{-- Tampilkan Catatan Kepala Sekolah jika sudah diisi --}}
@if($suratMasuk->catatan_kepsek)
    <div class="card mb-3 bg-light border-success">
        <div class="card-body">
            <h6 class="text-success mb-1" style="font-size:.8rem"><i class="bi bi-check-circle"></i> Catatan / Arahan Kepala Sekolah:</h6>
            <p class="mb-0 fw-semibold" style="font-size:.9rem; color: #333;">{{ $suratMasuk->catatan_kepsek }}</p>
        </div>
    </div>
@endif

{{-- ========================================================= --}}
{{-- 3. AKSI ADMIN TU: Membuat Disposisi (Setelah Ada Arahan Kepsek) --}}
{{-- ========================================================= --}}
@if($suratMasuk->status === 'menunggu_disposisi_tu' && in_array(auth()->user()->role, ['admin_tu', 'super_admin']))
    <div class="card mb-3">
        <div class="card-header d-flex align-items-center gap-3">
            <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white flex-shrink-0"
                  style="width:38px;height:38px;background:linear-gradient(135deg,#178754,#0EA5A4);font-size:1rem">
                <i class="bi bi-signpost-split"></i>
            </span>
            <div>
                <h3 class="mb-1" style="font-size:1.05rem">Buat Disposisi</h3>
                <p class="text-muted mb-0" style="font-size:.78rem">Kepala Sekolah sudah memberi arahan. Pilih satu atau lebih pegawai/unit tujuan.</p>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('surat-masuk.disposisikan', $suratMasuk) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">Tujuan Pegawai (boleh pilih lebih dari satu)</label>
                        <select name="tujuan_pegawai[]" class="form-select" multiple size="6">
                            @foreach($pegawaiList as $p)
                                <option value="{{ $p->id_pegawai }}">{{ $p->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">Tujuan Unit Kerja (boleh pilih lebih dari satu)</label>
                        <select name="tujuan_unit[]" class="form-select" multiple size="6">
                            @foreach($unitList as $u)
                                <option value="{{ $u->id_unit }}">{{ $u->nama_unit }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">Instruksi</label>
                        <input type="text" name="instruksi" class="form-control" placeholder="Mohon ditindaklanjuti">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">Catatan Tambahan</label>
                        <input type="text" name="catatan" class="form-control">
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn d-inline-flex align-items-center gap-2 text-white"
                            style="background:linear-gradient(135deg,#178754,#0EA5A4);border:none">
                        <i class="bi bi-send"></i> Kirim Disposisi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

{{-- Riwayat Disposisi --}}
@if($suratMasuk->disposisi->isNotEmpty())
    <div class="card mb-3">
        <div class="card-header">
            <h3 class="mb-1" style="font-size:1.05rem">Riwayat Disposisi</h3>
            <p class="text-muted mb-0" style="font-size:.78rem">{{ $suratMasuk->disposisi->count() }} disposisi tercatat</p>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @foreach($suratMasuk->disposisi as $d)
                    @php
                        $warnaTitik = match($d->status) {
                            'selesai' => ['bg' => '#E6F5EC', 'fg' => '#178754'],
                            'ditolak' => ['bg' => '#FCEBEA', 'fg' => '#C4463F'],
                            default   => ['bg' => '#FDF1E2', 'fg' => '#F7A02A'],
                        };
                        $badgeStatus = match($d->status) {
                            'selesai' => 'text-bg-success',
                            'ditolak' => 'text-bg-danger',
                            default   => 'text-bg-light text-muted',
                        };
                        $bolehAdmin = in_array(auth()->user()->role, ['admin_tu', 'super_admin'], true);
                        $bolehAksi = $bolehAdmin
                            || $d->ke_pegawai === auth()->user()->pegawai?->id_pegawai
                            || ($d->ke_unit && auth()->user()->adalahAdminUnit($d->ke_unit));
                        
                        $modeUnitSederhana = $d->ke_unit && ! $bolehAdmin;
                        $statusFinal = in_array($d->status, ['selesai', 'ditolak'], true);
                    @endphp
                    <div class="list-group-item d-flex flex-wrap align-items-center justify-content-between gap-3 py-3 px-3 border-0 border-bottom">
                        <div class="d-flex gap-3 min-w-0">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                  style="width:34px;height:34px;background:{{ $warnaTitik['bg'] }};color:{{ $warnaTitik['fg'] }};font-size:.6rem">
                                <i class="bi bi-dot fs-4"></i>
                            </span>
                            <div class="min-w-0">
                                <span class="d-block fw-semibold text-truncate" style="font-size:.85rem;color:var(--ink)">Ke: {{ $d->tujuan_label }}</span>
                                <span class="d-block text-muted" style="font-size:.76rem">Dari {{ $d->pemberiDisposisi->nama_lengkap ?? '-' }} &middot; {{ $d->tanggal_disposisi?->diffForHumans() }}</span>
                                @if($d->instruksi)
                                    <span class="d-block mt-1" style="font-size:.82rem">{{ $d->instruksi }}</span>
                                @endif
                                @if($d->status === 'ditolak' && $d->catatan)
                                    <span class="d-block mt-1 text-danger" style="font-size:.78rem"><i class="bi bi-info-circle"></i> {{ $d->catatan }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                            <span class="badge rounded-pill {{ $badgeStatus }}" style="font-size:.72rem">
                                {{ ucfirst($d->status) }}
                            </span>

                            <a href="{{ route('disposisi.show', $d) }}" class="btn btn-sm btn-light" style="font-size:.78rem">
                                Lihat Detail
                            </a>

                            @if(! $statusFinal && $bolehAksi)
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" style="font-size:.78rem">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if($modeUnitSederhana)
                                            <li>
                                                <form method="POST" action="{{ route('disposisi.terima', $d) }}"
                                                      onsubmit="return confirm('Terima disposisi surat ini? Setelah diterima, statusnya langsung dianggap selesai.')">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item" style="font-size:.85rem">
                                                        <i class="bi bi-check2 me-1"></i> Terima
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item text-danger" style="font-size:.85rem"
                                                        data-bs-toggle="modal" data-bs-target="#tolakModal{{ $d->id_disposisi }}">
                                                    <i class="bi bi-x-lg me-1"></i> Tolak
                                                </button>
                                            </li>
                                        @else
                                            @if($d->status !== 'ditindaklanjuti')
                                                <li>
                                                    <form method="POST" action="{{ route('disposisi.tindaklanjuti', $d) }}"
                                                          onsubmit="return confirm('Tandai disposisi ini mulai ditindaklanjuti?')">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item" style="font-size:.85rem">
                                                            <i class="bi bi-play-circle me-1"></i> Tindak Lanjuti
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                            <li>
                                                <form method="POST" action="{{ route('disposisi.selesaikan', $d) }}"
                                                      onsubmit="return confirm('Tandai disposisi ini selesai?')">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item" style="font-size:.85rem">
                                                        <i class="bi bi-check2 me-1"></i> Tandai Selesai
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item text-danger" style="font-size:.85rem"
                                                        data-bs-toggle="modal" data-bs-target="#tolakModal{{ $d->id_disposisi }}">
                                                    <i class="bi bi-x-lg me-1"></i> Tolak
                                                </button>
                                            </li>
                                        @endif
                                    </ul>
                                </div>

                                <div class="modal fade" id="tolakModal{{ $d->id_disposisi }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('disposisi.tolak', $d) }}">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title" style="font-size:1rem">Tolak Disposisi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <label class="form-label" style="font-size:.85rem">Alasan penolakan</label>
                                                    <textarea name="catatan_penolakan" rows="3" class="form-control" required placeholder="Jelaskan kenapa disposisi ini ditolak"></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="font-size:.85rem">Batal</button>
                                                    <button type="submit" class="btn btn-danger" style="font-size:.85rem">Tolak Disposisi</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

@if($suratMasuk->status === 'selesai' && in_array(auth()->user()->role, ['admin_tu', 'super_admin'], true))
    <div class="d-flex justify-content-end mt-3 pt-3 border-top">
        <form method="POST"
              action="{{ route('arsip.surat-masuk', $suratMasuk) }}"
              onsubmit="return confirm('Yakin ingin mengarsipkan surat ini?')">
            @csrf

            <button type="submit"
                    class="btn d-inline-flex align-items-center gap-2 text-white"
                    style="background:linear-gradient(135deg,#178754,#0F5C39);border:none">
                <i class="bi bi-archive"></i>
                Arsipkan Surat
            </button>
        </form>
    </div>
@endif

@endsection