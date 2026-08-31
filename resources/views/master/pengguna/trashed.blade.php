@extends('layouts.app')

@section('title', 'Sampah Pengguna')

@section('content')

    <div class="mb-4">
        <h5 class="mb-0" style="color:var(--ink)">Sampah Pengguna</h5>
        <p class="mb-0" style="color:var(--ink-muted);font-size:.83rem">
            Akun pengguna yang sudah dihapus. Bisa dipulihkan atau dihapus permanen.
        </p>
    </div>

    <ul class="nav mb-4" style="border-bottom:1px solid var(--border);">
        <li class="nav-item">
            <a class="nav-link px-3 py-2" href="{{ route('pengguna.index') }}"
               style="color:var(--ink-muted);font-weight:600;">
                Data Aktif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active px-3 py-2" href="{{ route('pengguna.trashed') }}"
               style="color:var(--bs-primary);font-weight:600;border-bottom:2px solid var(--bs-primary);">
                Sampah
            </a>
        </li>
    </ul>

    @if(session('sukses'))
        <div class="alert d-flex align-items-start gap-2 border-0 mb-4" style="background:#EAF7EE;color:#2E7D4F;border-radius:12px;font-size:.85rem;">
            <i class="bi bi-check-circle-fill mt-1"></i>
            <div>{{ session('sukses') }}</div>
        </div>
    @endif

    @if(session('gagal'))
        <div class="alert d-flex align-items-start gap-2 border-0 mb-4" style="background:#FCEBEA;color:#C4463F;border-radius:12px;font-size:.85rem;">
            <i class="bi bi-exclamation-circle-fill mt-1"></i>
            <div>{{ session('gagal') }}</div>
        </div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0" style="font-size:.87rem;">
                    <thead style="background:var(--bs-light,#f8f9fa);">
                        <tr>
                            <th class="ps-4 py-3" style="color:var(--ink-muted);font-weight:600;font-size:.78rem;text-transform:uppercase;letter-spacing:.04em;">Pengguna</th>
                            <th class="py-3" style="color:var(--ink-muted);font-weight:600;font-size:.78rem;text-transform:uppercase;letter-spacing:.04em;">Jabatan</th>
                            <th class="py-3" style="color:var(--ink-muted);font-weight:600;font-size:.78rem;text-transform:uppercase;letter-spacing:.04em;">Role</th>
                            <th class="py-3" style="color:var(--ink-muted);font-weight:600;font-size:.78rem;text-transform:uppercase;letter-spacing:.04em;">Dihapus pada</th>
                            <th class="pe-4 py-3 text-end" style="color:var(--ink-muted);font-weight:600;font-size:.78rem;text-transform:uppercase;letter-spacing:.04em;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengguna as $u)
                            <tr style="border-top:1px solid var(--border);">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle"
                                             style="width:34px;height:34px;background:#EEF1FF;color:var(--bs-primary);font-weight:700;font-size:.8rem;">
                                            {{ strtoupper(substr($u->username, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold" style="color:var(--ink)">{{ $u->username }}</div>
                                            <div style="color:var(--ink-muted);font-size:.78rem">{{ $u->pegawai->nama_lengkap ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">{{ $u->pegawai->jabatan->nama_jabatan ?? '-' }}</td>
                                <td class="py-3">
                                    <span class="badge" style="background:#EEF1FF;color:var(--bs-primary);font-weight:600;">
                                        {{ ucwords(str_replace('_', ' ', $u->role)) }}
                                    </span>
                                </td>
                                <td class="py-3" style="color:var(--ink-muted);">
                                    {{ $u->deleted_at->translatedFormat('d M Y, H:i') }}
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <div class="d-inline-flex gap-2">
                                        <form action="{{ route('pengguna.restore', $u->uuid) }}" method="POST"
                                              onsubmit="return confirm('Pulihkan akun ini?')">
                                            @csrf @method('PUT')
                                            <button type="submit" class="btn-icon-ghost" style="color:#1B8A5A;" title="Pulihkan">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('pengguna.forceDelete', $u->uuid) }}" method="POST"
                                              onsubmit="return confirm('Hapus PERMANEN akun ini? Data tidak bisa dikembalikan lagi.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-icon-ghost text-danger" title="Hapus Permanen">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5" style="color:var(--ink-muted)">
                                    <i class="bi bi-inbox" style="font-size:1.5rem;"></i>
                                    <p class="mb-0 mt-2">Sampah kosong.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $pengguna->links() }}
    </div>

@endsection