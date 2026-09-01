@extends('layouts.app')
@section('title', 'Sampah Surat Keluar')
@section('content')

<div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
    <div>
        <p class="text-muted mb-1" style="font-size:.82rem">Persuratan</p>
        <h2 class="mb-1" style="font-size:1.5rem">Sampah Surat Keluar</h2>
        <p class="text-muted mb-0" style="font-size:.78rem">Surat yang sudah dihapus. Bisa dipulihkan atau dihapus permanen.</p>
    </div>
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

{{-- Tab navigasi --}}
<ul class="nav mb-4" style="border-bottom:1px solid var(--border);">
    <li class="nav-item">
        <a class="nav-link px-3 py-2" href="{{ route('surat-keluar.index') }}"
           style="color:var(--ink-muted);font-weight:600;">
            Data Aktif
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active px-3 py-2" href="{{ route('surat-keluar.trashed') }}"
           style="color:var(--bs-primary);font-weight:600;border-bottom:2px solid var(--bs-primary);">
            Sampah
        </a>
    </li>
</ul>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr class="text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em">
                    <th class="ps-3">Nomor</th>
                    <th>Perihal</th>
                    <th>Kategori</th>
                    <th>Pembuat</th>
                    <th>Dihapus pada</th>
                    <th class="pe-3 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suratKeluar as $s)
                    <tr>
                        <td class="ps-3">
                            <span class="font-monospace text-muted" style="font-size:.78rem">{{ $s->nomor_surat ?? '—' }}</span>
                        </td>
                        <td class="fw-semibold" style="font-size:.85rem;color:var(--ink)">{{ $s->perihal }}</td>
                        <td class="text-muted" style="font-size:.82rem">{{ $s->kategori->nama_kategori ?? '-' }}</td>
                        <td class="text-muted" style="font-size:.82rem">{{ $s->pembuat->pegawai->nama_lengkap ?? $s->pembuat->username ?? '-' }}</td>
                        <td class="text-muted text-nowrap" style="font-size:.78rem">{{ $s->deleted_at?->translatedFormat('d M Y, H:i') }}</td>
                        <td class="pe-3 text-end">
                            <div class="d-inline-flex gap-2 justify-content-end">
                                <form action="{{ route('surat-keluar.restore', $s->uuid) }}" method="POST"
                                      onsubmit="return confirm('Pulihkan surat ini?')">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn btn-sm" style="border:1px solid var(--border);border-radius:8px;color:#1B8A5A;font-weight:600;background:#fff;font-size:.78rem;">
                                        <i class="bi bi-arrow-counterclockwise"></i> Pulihkan
                                    </button>
                                </form>
                                @if(in_array(Auth::user()->role, ['admin_tu', 'super_admin'], true))
                                    <form action="{{ route('surat-keluar.forceDelete', $s->uuid) }}" method="POST"
                                          onsubmit="return confirm('Hapus PERMANEN surat ini? Data tidak bisa dikembalikan lagi.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px;font-weight:600;font-size:.78rem;">
                                            <i class="bi bi-trash3-fill"></i> Hapus Permanen
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5" style="font-size:.85rem">
                            <i class="bi bi-trash3 fs-3 d-block mb-2"></i>
                            Sampah kosong.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($suratKeluar->hasPages())
        <div class="card-footer bg-transparent d-flex flex-wrap align-items-center justify-content-between gap-2">
            {{ $suratKeluar->links() }}
        </div>
    @endif
</div>

@endsection