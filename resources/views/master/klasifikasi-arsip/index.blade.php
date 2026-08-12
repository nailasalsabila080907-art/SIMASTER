@extends('layouts.app')

@section('title', 'Klasifikasi Arsip')

@section('content')

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h5 class="mb-1" style="color:var(--ink)">Klasifikasi Arsip</h5>
            <p class="mb-0" style="color:var(--ink-muted);font-size:.85rem">
                Kode klasifikasi untuk surat masuk dan surat keluar.
            </p>
        </div>
        <a href="{{ route('klasifikasi-arsip.create') }}" class="btn btn-sm text-white d-inline-flex align-items-center gap-1"
           style="background:var(--bs-primary);border-radius:8px;font-weight:600;padding:.55rem 1rem;">
            <i class="bi bi-plus-lg"></i> Tambah Klasifikasi
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('sukses'))
        <div class="alert d-flex align-items-center gap-2 border-0 mb-4" style="background:var(--primary-light);color:var(--primary-dark);border-radius:12px;">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('sukses') }}</div>
        </div>
    @endif

    @if(session('gagal'))
        <div class="alert d-flex align-items-center gap-2 border-0 mb-4" style="background:#FCEBEA;color:#C4463F;border-radius:12px;">
            <i class="bi bi-exclamation-circle-fill"></i>
            <div>{{ session('gagal') }}</div>
        </div>
    @endif

    {{-- Table --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size:.85rem;">
                <thead>
                    <tr style="background:var(--surface);">
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Kode</th>
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Nama</th>
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Parent</th>
                        <th class="px-4 py-3 border-0 text-end" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($klasifikasi as $k)
                        <tr style="border-top:1px solid var(--border);">
                            <td class="px-4 py-3 font-monospace fw-semibold" style="font-size:.8rem;color:var(--ink);">{{ $k->kode_klasifikasi }}</td>
                            <td class="px-4 py-3" style="color:var(--ink);">{{ $k->nama_klasifikasi }}</td>
                            <td class="px-4 py-3" style="color:var(--ink-muted);">{{ $k->parent->nama_klasifikasi ?? '-' }}</td>
                            <td class="px-4 py-3 text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('klasifikasi-arsip.edit', $k) }}"
                                       class="btn btn-sm" style="border:1px solid var(--border);border-radius:8px;color:var(--bs-primary);font-weight:600;background:#fff;">
                                        <i class="bi bi-pencil"></i> Ubah
                                    </a>
                                    <form action="{{ route('klasifikasi-arsip.destroy', $k) }}" method="POST" onsubmit="return confirm('Hapus klasifikasi ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px;font-weight:600;">
                                            <i class="bi bi-trash3"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5" style="color:var(--ink-muted);">
                                <div class="d-inline-flex align-items-center justify-content-center mb-3"
                                     style="width:56px;height:56px;border-radius:14px;background:var(--primary-light);color:var(--bs-primary);">
                                    <i class="bi bi-folder2 fs-4"></i>
                                </div>
                                <p class="mb-0">Belum ada klasifikasi.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($klasifikasi->hasPages())
        <div class="mt-4">
            {{ $klasifikasi->links('pagination::bootstrap-5') }}
        </div>
    @endif

@endsection
