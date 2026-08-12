@extends('layouts.app')
@section('title','Surat Masuk')
@section('content')

<div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
    <div>
        <p class="text-muted mb-1" style="font-size:.82rem">Persuratan</p>
        <h2 class="mb-1" style="font-size:1.5rem">Daftar Surat Masuk</h2>
        <p class="text-muted mb-0" style="font-size:.78rem">Pencatatan surat, disposisi, tindak lanjut, dan arsip.</p>
    </div>
    <a href="{{ route('surat-masuk.create') }}" class="btn d-inline-flex align-items-center gap-2 px-3 py-2 text-white"
       style="background:linear-gradient(135deg,#178754,#0EA5A4);border:none">
        <i class="bi bi-plus-lg"></i> Catat Surat Masuk
    </a>
</div>

@if(session('sukses'))
    <div class="alert alert-success rounded-3 d-flex align-items-center gap-2" style="font-size:.85rem" role="alert">
        <i class="bi bi-check-circle"></i>
        <div>{{ session('sukses') }}</div>
    </div>
@endif

<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            @php
                $statusOptions = ['' => 'Semua', 'baru' => 'Baru', 'didisposisi' => 'Didisposisi', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'diarsipkan' => 'Diarsipkan'];
            @endphp
            @foreach($statusOptions as $v => $l)
                @php $isActive = ($filterStatus === $v) || (!$filterStatus && !$v); @endphp
                <a href="{{ route('surat-masuk.index', $v ? ['status' => $v] : []) }}"
                   class="badge rounded-pill text-decoration-none {{ $isActive ? 'text-white' : 'text-bg-light text-muted' }}"
                   style="font-size:.78rem;padding:.5rem .9rem;{{ $isActive ? 'background:linear-gradient(135deg,#178754,#0EA5A4)' : '' }}">
                    {{ $l }}
                </a>
            @endforeach
        </div>
        <span class="text-muted" style="font-size:.78rem">{{ $suratMasuk->total() }} surat</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr class="text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em">
                    <th class="ps-3">Agenda</th>
                    <th>Asal Instansi</th>
                    <th>Perihal</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th class="pe-3 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suratMasuk as $i => $s)
                    @php
                        $badge = [
                            'baru'        => 'text-bg-warning',
                            'didisposisi' => 'text-bg-info',
                            'diproses'    => 'text-bg-primary',
                            'selesai'     => 'text-bg-success',
                            'diarsipkan'  => 'text-bg-secondary',
                        ][$s->status] ?? 'text-bg-secondary';

                        $avatarGradients = [
                            'linear-gradient(135deg,#0F5C39,#178754)',
                            'linear-gradient(135deg,#0EA5A4,#22C3A6)',
                            'linear-gradient(135deg,#178754,#4FBE85)',
                            'linear-gradient(135deg,#D98C00,#F0A202)',
                            'linear-gradient(135deg,#3E4652,#5B5D6B)',
                        ];
                    @endphp
                    <tr>
                        <td class="ps-3">
                            <span class="font-monospace text-muted" style="font-size:.78rem">{{ $s->nomor_surat_masuk }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white fw-bold flex-shrink-0"
                                      style="width:32px;height:32px;font-size:.72rem;background:{{ $avatarGradients[$i % count($avatarGradients)] }}">
                                    {{ strtoupper(substr($s->asal_instansi, 0, 1)) }}
                                </span>
                                <span style="font-size:.85rem">{{ $s->asal_instansi }}</span>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('surat-masuk.show', $s) }}" class="fw-semibold text-decoration-none" style="font-size:.85rem;color:var(--ink)">
                                {{ $s->perihal }}
                            </a>
                        </td>
                        <td class="text-muted text-nowrap" style="font-size:.78rem">{{ $s->tanggal_diterima?->format('d M Y') }}</td>
                        <td>
                            <span class="badge rounded-pill {{ $badge }}" style="font-size:.72rem">{{ ucfirst($s->status) }}</span>
                        </td>
                        <td class="pe-3 text-end">
                            <a href="{{ route('surat-masuk.show', $s) }}" class="btn btn-sm btn-light rounded-circle" title="Lihat detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5" style="font-size:.85rem">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            Belum ada surat masuk.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($suratMasuk->hasPages())
        <div class="card-footer bg-transparent d-flex flex-wrap align-items-center justify-content-between gap-2">
            <span class="text-muted" style="font-size:.78rem">
                Menampilkan {{ $suratMasuk->firstItem() }}–{{ $suratMasuk->lastItem() }} dari {{ $suratMasuk->total() }} data
            </span>
            {{ $suratMasuk->links() }}
        </div>
    @endif
</div>

@endsection