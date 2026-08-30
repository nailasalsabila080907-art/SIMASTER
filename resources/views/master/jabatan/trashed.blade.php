@extends('layouts.app')

@section('title', 'Sampah Jabatan')

@section('content')

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h5 class="mb-1" style="color:var(--ink)">Sampah Jabatan</h5>
            <p class="mb-0" style="color:var(--ink-muted);font-size:.85rem">
                Jabatan yang sudah dihapus. Bisa dipulihkan atau dihapus permanen.
            </p>
        </div>
    </div>

    {{-- Tab navigasi --}}
    <ul class="nav mb-4" style="border-bottom:1px solid var(--border);">
        <li class="nav-item">
            <a class="nav-link px-3 py-2" href="{{ route('jabatan.index') }}"
               style="color:var(--ink-muted);font-weight:600;">
                Data Aktif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active px-3 py-2" href="{{ route('jabatan.trashed') }}"
               style="color:var(--bs-primary);font-weight:600;border-bottom:2px solid var(--bs-primary);">
                Sampah
            </a>
        </li>
    </ul>

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
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Nama Jabatan</th>
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Level</th>
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Dihapus pada</th>
                        <th class="px-4 py-3 border-0 text-end" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jabatan as $j)
                        <tr style="border-top:1px solid var(--border);">
                            <td class="px-4 py-3 fw-semibold" style="color:var(--ink);">{{ $j->nama_jabatan }}</td>
                            <td class="px-4 py-3">
                                <span class="badge" style="background:var(--primary-light);color:var(--bs-primary);font-weight:600;">
                                    Level {{ $j->level_jabatan }}
                                </span>
                            </td>
                            <td class="px-4 py-3" style="color:var(--ink-muted);">
                                {{ $j->deleted_at->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="d-inline-flex gap-2">
                                    <form action="{{ route('jabatan.restore', $j->uuid) }}" method="POST"
                                          onsubmit="return confirm('Pulihkan jabatan ini?')">
                                        @csrf @method('PUT')
                                        <button type="submit" class="btn btn-sm" style="border:1px solid var(--border);border-radius:8px;color:#1B8A5A;font-weight:600;background:#fff;">
                                            <i class="bi bi-arrow-counterclockwise"></i> Pulihkan
                                        </button>
                                    </form>
                                    <form action="{{ route('jabatan.forceDelete', $j->uuid) }}" method="POST"
                                          onsubmit="return confirm('Hapus PERMANEN jabatan ini? Data tidak bisa dikembalikan lagi.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px;font-weight:600;">
                                            <i class="bi bi-trash3-fill"></i> Hapus Permanen
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
                                    <i class="bi bi-trash3 fs-4"></i>
                                </div>
                                <p class="mb-0">Sampah kosong.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($jabatan->hasPages())
        <div class="mt-4">
            {{ $jabatan->links('pagination::bootstrap-5') }}
        </div>
    @endif

@endsection