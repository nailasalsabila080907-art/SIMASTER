@extends('layouts.app')

@section('title', 'Master Pegawai')

@section('content')

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h5 class="mb-1" style="color:var(--ink)">Master Pegawai</h5>
            <p class="mb-0" style="color:var(--ink-muted);font-size:.85rem">
                Kelola data pegawai dan akun login.
            </p>
        </div>
        <a href="{{ route('pegawai.create') }}" class="btn btn-sm text-white d-inline-flex align-items-center gap-1"
           style="background:var(--bs-primary);border-radius:8px;font-weight:600;padding:.55rem 1rem;">
            <i class="bi bi-plus-lg"></i> Tambah Pegawai
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
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Nama</th>
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">NIP</th>
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Jabatan</th>
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Unit</th>
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Akun</th>
                        <th class="px-4 py-3 border-0 text-end" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pegawai as $p)
                        <tr style="border-top:1px solid var(--border);">
                            <td class="px-4 py-3 fw-semibold" style="color:var(--ink);">{{ $p->nama_lengkap }}</td>
                            <td class="px-4 py-3 font-monospace" style="font-size:.8rem;color:var(--ink-muted);">{{ $p->nip }}</td>
                            <td class="px-4 py-3">{{ $p->jabatan->nama_jabatan ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $p->unitKerja->nama_unit ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($p->user)
                                    <span class="badge" style="background:var(--primary-light);color:var(--bs-primary);font-weight:600;">
                                        {{ $p->user->username }}
                                    </span>
                                @else
                                    <span style="color:var(--ink-muted);font-size:.78rem;">Belum ada</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('pegawai.edit', $p) }}"
                                       class="btn btn-sm" style="border:1px solid var(--border);border-radius:8px;color:var(--bs-primary);font-weight:600;background:#fff;">
                                        <i class="bi bi-pencil"></i> Ubah
                                    </a>
                                    <form action="{{ route('pegawai.destroy', $p) }}" method="POST" onsubmit="return confirm('Yakin hapus pegawai ini?')">
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
                            <td colspan="6" class="text-center py-5" style="color:var(--ink-muted);">
                                <div class="d-inline-flex align-items-center justify-content-center mb-3"
                                     style="width:56px;height:56px;border-radius:14px;background:var(--primary-light);color:var(--bs-primary);">
                                    <i class="bi bi-people fs-4"></i>
                                </div>
                                <p class="mb-0">Belum ada data pegawai.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($pegawai->hasPages())
        <div class="mt-4">
            {{ $pegawai->links('pagination::bootstrap-5') }}
        </div>
    @endif

@endsection
