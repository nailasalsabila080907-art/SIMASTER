@extends('layouts.app')

@section('title', 'Master Jurusan')

@section('content')

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h5 class="mb-1" style="color:var(--ink)">Master Jurusan</h5>
            <p class="mb-0" style="color:var(--ink-muted);font-size:.85rem">
                Kelola jurusan / program keahlian sekolah.
            </p>
        </div>
        <a href="{{ route('jurusan.create') }}" class="btn btn-sm text-white d-inline-flex align-items-center gap-1"
           style="background:var(--bs-primary);border-radius:8px;font-weight:600;padding:.55rem 1rem;">
            <i class="bi bi-plus-lg"></i> Tambah Jurusan
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
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Nama Jurusan</th>
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Status</th>
                        <th class="px-4 py-3 border-0 text-end" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jurusan as $j)
                        <tr style="border-top:1px solid var(--border);">
                            <td class="px-4 py-3 font-monospace fw-semibold" style="font-size:.8rem;color:var(--ink);">{{ $j->kode_jurusan }}</td>
                            <td class="px-4 py-3" style="color:var(--ink);">{{ $j->nama_jurusan }}</td>
                            <td class="px-4 py-3">
                                @if($j->status === 'aktif')
                                    <span class="badge" style="background:var(--primary-light);color:var(--bs-primary);font-weight:600;">Aktif</span>
                                @else
                                    <span class="badge" style="background:#F1F1F1;color:#6B7280;font-weight:600;">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('jurusan.edit', $j) }}"
                                       class="btn btn-sm" style="border:1px solid var(--border);border-radius:8px;color:var(--bs-primary);font-weight:600;background:#fff;">
                                        <i class="bi bi-pencil"></i> Ubah
                                    </a>
                                    <form action="{{ route('jurusan.destroy', $j) }}" method="POST" onsubmit="return confirm('Yakin hapus jurusan ini?')">
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
                                    <i class="bi bi-diagram-3 fs-4"></i>
                                </div>
                                <p class="mb-0">Belum ada jurusan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($jurusan->hasPages())
        <div class="mt-4">
            {{ $jurusan->links('pagination::bootstrap-5') }}
        </div>
    @endif

@endsection
