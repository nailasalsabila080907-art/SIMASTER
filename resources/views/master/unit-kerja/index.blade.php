@extends('layouts.app')

@section('title', 'Unit Kerja')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0" style="color:var(--ink)">Unit Kerja</h5>
            <p class="mb-0" style="color:var(--ink-muted);font-size:.83rem">
                Kelola unit yang digunakan pada penomoran dan data pegawai.
            </p>
        </div>
        <a href="{{ route('unit-kerja.create') }}" class="btn text-white" style="background:var(--bs-primary);border-radius:10px;font-weight:600;font-size:.87rem;padding:.6rem 1.4rem;">
            <i class="bi bi-plus-lg"></i> Tambah Unit
        </a>
    </div>

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
                            <th class="ps-4 py-3" style="color:var(--ink-muted);font-weight:600;font-size:.78rem;text-transform:uppercase;letter-spacing:.04em;">Kode</th>
                            <th class="py-3" style="color:var(--ink-muted);font-weight:600;font-size:.78rem;text-transform:uppercase;letter-spacing:.04em;">Nama Unit</th>
                            <th class="py-3" style="color:var(--ink-muted);font-weight:600;font-size:.78rem;text-transform:uppercase;letter-spacing:.04em;">Status</th>
                            <th class="pe-4 py-3 text-end" style="color:var(--ink-muted);font-weight:600;font-size:.78rem;text-transform:uppercase;letter-spacing:.04em;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($unitKerja as $u)
                            <tr style="border-top:1px solid var(--border);">
                                <td class="ps-4 py-3"><span class="font-monospace">{{ $u->kode_unit }}</span></td>
                                <td class="py-3 fw-semibold" style="color:var(--ink)">{{ $u->nama_unit }}</td>
                                <td class="py-3">
                                    @if($u->status === 'aktif')
                                        <span class="badge" style="background:#EAF7EE;color:#2E7D4F;font-weight:600;">Aktif</span>
                                    @else
                                        <span class="badge" style="background:#F1F1F3;color:#6B7280;font-weight:600;">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <a href="{{ route('unit-kerja.edit', $u) }}" class="btn-icon-ghost me-2" title="Ubah">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form class="d-inline" method="POST" action="{{ route('unit-kerja.destroy', $u) }}" onsubmit="return confirm('Hapus unit ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon-ghost text-danger" title="Hapus">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5" style="color:var(--ink-muted)">
                                    <i class="bi bi-inbox" style="font-size:1.5rem;"></i>
                                    <p class="mb-0 mt-2">Belum ada unit kerja.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $unitKerja->links() }}
    </div>

@endsection
