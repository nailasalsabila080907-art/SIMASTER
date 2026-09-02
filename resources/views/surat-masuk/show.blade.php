@extends('layouts.app')
@section('title','Detail Surat Masuk')
@section('content')

@php
    $badge = [
        'baru'                      => 'text-bg-warning',
        'menunggu_approval_kepsek'  => 'text-bg-info',
        'siap_kirim'                => 'text-bg-primary',
        'didisposisi'               => 'text-bg-info',
        'diproses'                  => 'text-bg-primary',
        'selesai'                   => 'text-bg-success',
        'diarsipkan'                => 'text-bg-secondary',
    ][$suratMasuk->status] ?? 'text-bg-secondary';

    $labelStatus = [
        'baru'                     => 'Baru',
        'menunggu_approval_kepsek' => 'Menunggu Kepsek',
        'siap_kirim'               => 'Siap Dikirim',
        'didisposisi'              => 'Didisposisi',
        'diproses'                 => 'Diproses',
        'selesai'                  => 'Selesai',
        'diarsipkan'               => 'Diarsipkan',
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

@if($suratMasuk->status === 'baru' && $suratMasuk->catatan_kepsek)
    <div class="alert alert-danger rounded-3 d-flex align-items-start gap-2 mb-4" style="font-size:.85rem" role="alert">
        <i class="bi bi-x-circle mt-1"></i>
        <div>
            <strong>Pengajuan ditolak Kepala Sekolah:</strong><br>
            {{ $suratMasuk->catatan_kepsek }}
        </div>
    </div>
@endif

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

{{-- TAHAP 1: TU ajukan ke Kepsek --}}
@if($suratMasuk->status === 'baru' && in_array(auth()->user()->role, ['admin_tu', 'super_admin']))
    <div class="card mb-3">
        <div class="card-header d-flex align-items-center gap-3">
            <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white flex-shrink-0"
                  style="width:38px;height:38px;background:linear-gradient(135deg,#178754,#0EA5A4);font-size:1rem">
                <i class="bi bi-send-check"></i>
            </span>
            <div>
                <h3 class="mb-1" style="font-size:1.05rem">Ajukan ke Kepala Sekolah</h3>
                <p class="text-muted mb-0" style="font-size:.78rem">Kepala Sekolah akan menentukan unit/pegawai tujuan disposisi</p>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('surat-masuk.ajukan-kepsek', $suratMasuk) }}">
                @csrf
                <label class="form-label" style="font-size:.85rem">Catatan pengantar (opsional)</label>
                <textarea name="catatan_pengantar" rows="2" class="form-control mb-3" placeholder="Ringkasan atau info tambahan untuk Kepala Sekolah"></textarea>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn d-inline-flex align-items-center gap-2 text-white"
                            style="background:linear-gradient(135deg,#178754,#0EA5A4);border:none">
                        <i class="bi bi-send"></i> Ajukan ke Kepala Sekolah
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

{{-- Info untuk TU saat surat masih menunggu Kepsek --}}
@if($suratMasuk->status === 'menunggu_approval_kepsek' && in_array(auth()->user()->role, ['admin_tu']))
    <div class="alert alert-info rounded-3 d-flex align-items-center gap-2 mb-4" style="font-size:.85rem" role="alert">
        <i class="bi bi-hourglass-split"></i>
        <div>Menunggu Kepala Sekolah menentukan tujuan disposisi.</div>
    </div>
@endif

{{-- TAHAP 2: Kepsek tentukan tujuan + setujui / tolak --}}
@if($suratMasuk->status === 'menunggu_approval_kepsek' && in_array(auth()->user()->role, ['kepala_sekolah', 'super_admin']))
    <div class="card mb-3">
        <div class="card-header d-flex align-items-center gap-3">
            <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white flex-shrink-0"
                  style="width:38px;height:38px;background:linear-gradient(135deg,#178754,#0EA5A4);font-size:1rem">
                <i class="bi bi-signpost-split"></i>
            </span>
            <div>
                <h3 class="mb-1" style="font-size:1.05rem">Tentukan Tujuan Disposisi</h3>
                <p class="text-muted mb-0" style="font-size:.78rem">Pilih satu atau lebih pegawai/unit tujuan, lalu setujui</p>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('surat-masuk.setujui-kepsek', $suratMasuk) }}" class="mb-4">
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
                        <label class="form-label" style="font-size:.85rem">Catatan</label>
                        <input type="text" name="catatan" class="form-control">
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn d-inline-flex align-items-center gap-2 text-white"
                            style="background:linear-gradient(135deg,#178754,#0EA5A4);border:none">
                        <i class="bi bi-check2"></i> Setujui &amp; Tentukan Tujuan
                    </button>
                </div>
            </form>

            <hr>

            <form method="POST" action="{{ route('surat-masuk.tolak-kepsek', $suratMasuk) }}"
                  onsubmit="return confirm('Tolak pengajuan surat ini? Surat akan dikembalikan ke Admin TU.')">
                @csrf
                <label class="form-label" style="font-size:.85rem">Alasan penolakan (kalau surat ini mau ditolak)</label>
                <div class="d-flex gap-2">
                    <input type="text" name="catatan_penolakan" class="form-control" placeholder="Wajib diisi kalau menolak">
                    <button type="submit" class="btn btn-outline-danger flex-shrink-0" style="font-size:.85rem">
                        <i class="bi bi-x-lg"></i> Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

{{-- TAHAP 3: TU kirim disposisi yang sudah disiapkan Kepsek --}}
@if($suratMasuk->status === 'siap_kirim')
    @php $siapKirim = $suratMasuk->disposisi->where('status', 'siap_kirim'); @endphp
    <div class="card mb-3">
        <div class="card-header d-flex align-items-center gap-3">
            <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white flex-shrink-0"
                  style="width:38px;height:38px;background:linear-gradient(135deg,#178754,#0EA5A4);font-size:1rem">
                <i class="bi bi-envelope-arrow-up"></i>
            </span>
            <div>
                <h3 class="mb-1" style="font-size:1.05rem">Siap Dikirim ke {{ $siapKirim->count() }} Tujuan</h3>
                <p class="text-muted mb-0" style="font-size:.78rem">Ditentukan Kepala Sekolah, menunggu dikirim Admin TU</p>
            </div>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush mb-3">
                @foreach($siapKirim as $d)
                    <li class="list-group-item px-0" style="font-size:.85rem">
                        <i class="bi bi-arrow-right-short"></i> {{ $d->tujuan_label }}
                        @if($d->instruksi)
                            <span class="text-muted">&middot; {{ $d->instruksi }}</span>
                        @endif
                    </li>
                @endforeach
            </ul>
            @if(in_array(auth()->user()->role, ['admin_tu', 'super_admin']))
                <form method="POST" action="{{ route('surat-masuk.kirim-disposisi', $suratMasuk) }}" class="d-flex justify-content-end">
                    @csrf
                    <button type="submit" class="btn d-inline-flex align-items-center gap-2 text-white"
                            style="background:linear-gradient(135deg,#178754,#0EA5A4);border:none">
                        <i class="bi bi-send"></i> Kirim Disposisi Sekarang
                    </button>
                </form>
            @endif
        </div>
    </div>
@endif

{{-- Jalur darurat: super_admin bisa langsung disposisi tanpa lewat approval Kepsek --}}
@if($suratMasuk->status === 'baru' && auth()->user()->role === 'super_admin')
    <details class="mb-3">
        <summary class="text-muted" style="font-size:.78rem;cursor:pointer">Opsi darurat: disposisi langsung (lewati approval Kepsek)</summary>
        <div class="card mt-2">
            <div class="card-body">
                <form method="POST" action="{{ route('surat-masuk.disposisi.store', $suratMasuk) }}" class="row g-3">
                    @csrf
                    <div class="col-md-4">
                        <label class="form-label" style="font-size:.85rem">Tujuan</label>
                        <select name="tujuan_tipe" id="tujuan_tipe" onchange="ubahTujuan()" class="form-select">
                            <option value="pegawai">Pegawai</option>
                            <option value="unit">Unit Kerja</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" style="font-size:.85rem">Penerima</label>
                        <select name="tujuan_id" id="tujuan_id" class="form-select">
                            @foreach($pegawaiList as $p)
                                <option data-tipe="pegawai" value="{{ $p->id_pegawai }}">{{ $p->nama_lengkap }}</option>
                            @endforeach
                            @foreach($unitList as $u)
                                <option data-tipe="unit" value="{{ $u->id_unit }}" style="display:none">{{ $u->nama_unit }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" style="font-size:.85rem">Instruksi</label>
                        <input type="text" name="instruksi" class="form-control" placeholder="Mohon ditindaklanjuti">
                    </div>
                    <div class="col-12">
                        <label class="form-label" style="font-size:.85rem">Catatan</label>
                        <textarea name="catatan" rows="2" class="form-control"></textarea>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                            <i class="bi bi-send"></i> Kirim Disposisi Langsung
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </details>
@endif

@if($suratMasuk->disposisi->where('status', '!=', 'siap_kirim')->isNotEmpty())
    <div class="card mb-3">
        <div class="card-header">
            <h3 class="mb-1" style="font-size:1.05rem">Riwayat Disposisi</h3>
            <p class="text-muted mb-0" style="font-size:.78rem">{{ $suratMasuk->disposisi->where('status', '!=', 'siap_kirim')->count() }} disposisi tercatat</p>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @foreach($suratMasuk->disposisi->where('status', '!=', 'siap_kirim') as $d)
                    <div class="list-group-item d-flex flex-wrap align-items-center justify-content-between gap-3 py-3 px-3 border-0 border-bottom">
                        <div class="d-flex gap-3 min-w-0">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                  style="width:34px;height:34px;background:{{ $d->status === 'selesai' ? '#E6F5EC' : '#FDF1E2' }};color:{{ $d->status === 'selesai' ? '#178754' : '#F7A02A' }};font-size:.6rem">
                                <i class="bi bi-dot fs-4"></i>
                            </span>
                            <div class="min-w-0">
                                <span class="d-block fw-semibold text-truncate" style="font-size:.85rem;color:var(--ink)">Ke: {{ $d->tujuan_label }}</span>
                                <span class="d-block text-muted" style="font-size:.76rem">Dari {{ $d->pemberiDisposisi->nama_lengkap ?? '-' }} &middot; {{ $d->tanggal_disposisi?->diffForHumans() }}</span>
                                @if($d->instruksi)
                                    <span class="d-block mt-1" style="font-size:.82rem">{{ $d->instruksi }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                            <span class="badge rounded-pill {{ $d->status === 'selesai' ? 'text-bg-success' : 'text-bg-light text-muted' }}" style="font-size:.72rem">
                                {{ ucfirst($d->status) }}
                            </span>
                            @if($d->status !== 'selesai')
                                <form method="POST" action="{{ route('disposisi.selesaikan', $d) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-light" style="font-size:.78rem">Tandai Selesai</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

@if($suratMasuk->status === 'selesai' && auth()->user()->role !== 'guru')
    <form method="POST" action="{{ route('arsip.surat-masuk', $suratMasuk) }}">
        @csrf
        <button type="submit" class="btn d-inline-flex align-items-center gap-2 text-white"
                style="background:linear-gradient(135deg,#3E4652,#5B5D6B);border:none">
            <i class="bi bi-archive"></i> Arsipkan Surat
        </button>
    </form>
@endif

@push('scripts')
<script>
    function ubahTujuan() {
        const t = document.getElementById('tujuan_tipe').value;
        document.querySelectorAll('#tujuan_id option').forEach(o => {
            o.style.display = o.dataset.tipe === t ? 'block' : 'none';
        });
        const f = document.querySelector('#tujuan_id option[data-tipe="' + t + '"]');
        if (f) document.getElementById('tujuan_id').value = f.value;
    }
    ubahTujuan();
</script>
@endpush
@endsection