@extends('layouts.app')
@section('title', 'Surat Keluar')
@section('content')

<div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
    <div>
        <p class="text-muted mb-1" style="font-size:.82rem">Persuratan</p>
        <h2 class="mb-1" style="font-size:1.5rem">Daftar Surat Keluar</h2>
        <p class="text-muted mb-0" style="font-size:.78rem">Kelola draft, pengajuan, approval, dan surat yang sudah terbit.</p>
    </div>
   @if(Auth::user()->role !== 'kepala_sekolah')
        <a href="{{ route('surat-keluar.create') }}" class="btn d-inline-flex align-items-center gap-2 px-3 py-2 text-white"
           style="background:linear-gradient(135deg,#178754,#0EA5A4);border:none">
            <i class="bi bi-plus-lg"></i> Buat Surat
        </a>
    @endif
</div>

@if(session('sukses'))
    <div class="alert alert-success rounded-3 d-flex align-items-center gap-2" style="font-size:.85rem" role="alert">
        <i class="bi bi-check-circle"></i>
        <div>{{ session('sukses') }}</div>
    </div>
@endif

@if(session('gagal'))
    <div class="alert alert-danger rounded-3 d-flex align-items-center gap-2" style="font-size:.85rem" role="alert">
        <i class="bi bi-x-circle"></i>
        <div>{{ session('gagal') }}</div>
    </div>
@endif

<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            @foreach(['' => 'Semua', 'draft' => 'Draft', 'ditolak' => 'Ditolak', 'diajukan' => 'Diajukan', 'terkirim' => 'Terbit'] as $val => $label)
                @php $isActive = ($filterStatus === $val) || (!$filterStatus && !$val); @endphp
                <a href="{{ route('surat-keluar.index', $val ? ['status' => $val] : []) }}"
                   class="badge rounded-pill text-decoration-none {{ $isActive ? 'text-white' : 'text-bg-light text-muted' }}"
                   style="font-size:.78rem;padding:.5rem .9rem;{{ $isActive ? 'background:linear-gradient(135deg,#178754,#0EA5A4)' : '' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
        <span class="text-muted" style="font-size:.78rem">{{ $suratKeluar->total() }} surat</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr class="text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em">
                    <th class="ps-3">Nomor</th>
                    <th>Perihal</th>
                    <th>Kategori</th>
                    <th>Tanggal</th>
                    <th>Pembuat</th>
                    <th>Status</th>
                    <th class="pe-3 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suratKeluar as $s)
                    @php
                        $badge = [
                            'draft'      => 'text-bg-secondary',
                            'diajukan'   => 'text-bg-warning',
                            'disetujui'  => 'text-bg-success',
                            'ditolak'    => 'text-bg-danger',
                            'terkirim'   => 'text-bg-primary',
                            'diarsipkan' => 'text-bg-secondary',
                        ][$s->status] ?? 'text-bg-secondary';
                    @endphp
                    <tr>
                        <td class="ps-3">
                            <span class="font-monospace text-muted" style="font-size:.78rem">{{ $s->nomor_surat ?? '—' }}</span>
                        </td>
                        <td>
                            <a href="{{ route('surat-keluar.show', $s) }}" class="fw-semibold text-decoration-none" style="font-size:.85rem;color:var(--ink)">
                                {{ $s->perihal }}
                            </a>
                        </td>
                        <td class="text-muted" style="font-size:.82rem">{{ $s->kategori->nama_kategori ?? '-' }}</td>
                        <td class="text-muted text-nowrap" style="font-size:.78rem">{{ $s->tanggal_surat?->format('d M Y') ?? '-' }}</td>
                        <td class="text-muted" style="font-size:.82rem">{{ $s->pembuat->pegawai->nama_lengkap ?? $s->pembuat->username ?? '-' }}</td>
                        <td>
                            <span class="badge rounded-pill {{ $badge }}" style="font-size:.72rem">{{ ucfirst($s->status) }}</span>
                        </td>
                        <td class="pe-3 text-end">
                            <a href="{{ route('surat-keluar.show', $s) }}" class="btn btn-sm btn-light rounded-circle" title="Lihat detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5" style="font-size:.85rem">
                            <i class="bi bi-send fs-3 d-block mb-2"></i>
                            Belum ada surat keluar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($suratKeluar->hasPages())
        <div class="card-footer bg-transparent d-flex flex-wrap align-items-center justify-content-between gap-2">
            <span class="text-muted" style="font-size:.78rem">
                Menampilkan {{ $suratKeluar->firstItem() }}–{{ $suratKeluar->lastItem() }} dari {{ $suratKeluar->total() }} data
            </span>
            {{ $suratKeluar->links() }}
        </div>
    @endif
</div>

@endsection