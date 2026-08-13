@extends('layouts.app')

@section('title', 'Arsip Surat')

@section('content')

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h5 class="mb-1" style="color:var(--ink)">Arsip Surat</h5>
            <p class="mb-0" style="color:var(--ink-muted);font-size:.85rem">
                Daftar surat yang telah selesai diproses dan diarsipkan.
            </p>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('sukses'))
        <div class="alert d-flex align-items-center gap-2 border-0 mb-4" style="background:var(--primary-light);color:var(--primary-dark);border-radius:12px;">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('sukses') }}</div>
        </div>
    @endif

    {{-- Table --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size:.85rem;">
                <thead>
                    <tr style="background:var(--surface);">
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Jenis</th>
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Nomor / ID</th>
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Diarsipkan</th>
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Pengarsip</th>
                        <th class="px-4 py-3 border-0 text-end" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($arsip as $a)
                        @php $surat = $a->suratTerkait(); @endphp
                        <tr style="border-top:1px solid var(--border);">
                            <td class="px-4 py-3">
                                @if($a->tipe_surat === 'keluar')
                                    <span class="badge" style="background:var(--primary-light);color:var(--bs-primary);font-weight:600;">
                                        <i class="bi bi-send"></i> Keluar
                                    </span>
                                @else
                                    <span class="badge" style="background:#EAF4FB;color:var(--info);font-weight:600;">
                                        <i class="bi bi-envelope-arrow-down"></i> Masuk
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-monospace" style="font-size:.8rem;color:var(--ink);">
                                {{ $surat->nomor_surat ?? $surat->nomor_surat_masuk ?? ('#'.$a->id_surat) }}
                            </td>
                            <td class="px-4 py-3" style="color:var(--ink-muted);">
                                {{ $a->tanggal_diarsipkan?->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $a->pengarsip->pegawai->nama_lengkap ?? $a->pengarsip->username ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-end">
                                @if($surat)
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ $a->tipe_surat === 'keluar' ? route('surat-keluar.show', $surat) : route('surat-masuk.show', $surat) }}"
                                           class="btn btn-sm"
                                           style="border:1px solid var(--border);border-radius:8px;color:var(--bs-primary);font-weight:600;background:#fff;">
                                            <i class="bi bi-eye"></i> Lihat
                                        </a>
                                        @if($a->tipe_surat === 'keluar' && in_array($surat->status, ['terkirim', 'diarsipkan'], true))
                                            <a href="{{ route('surat-keluar.cetak-pdf', $surat) }}"
                                               class="btn btn-sm"
                                               target="_blank"
                                               style="border:1px solid #2F6B4F;border-radius:8px;color:#2F6B4F;font-weight:600;background:#fff;">
                                                <i class="bi bi-file-earmark-pdf"></i> PDF
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5" style="color:var(--ink-muted);">
                                <div class="d-inline-flex align-items-center justify-content-center mb-3"
                                     style="width:56px;height:56px;border-radius:14px;background:var(--primary-light);color:var(--bs-primary);">
                                    <i class="bi bi-archive fs-4"></i>
                                </div>
                                <p class="mb-0">Belum ada arsip.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($arsip->hasPages())
        <div class="mt-4">
            {{ $arsip->links('pagination::bootstrap-5') }}
        </div>
    @endif

@endsection