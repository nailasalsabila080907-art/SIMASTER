@extends('layouts.app')

@section('title', 'Approval Surat')

@section('content')

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h5 class="mb-1" style="color:var(--ink)">Persetujuan Surat</h5>
            <p class="mb-0" style="color:var(--ink-muted);font-size:.85rem">
                Surat yang menunggu persetujuan Anda.
            </p>
        </div>
        <span class="badge rounded-pill" style="background:var(--primary-light);color:var(--bs-primary);font-weight:700;font-size:.78rem;padding:.5rem .9rem;">
            {{ $approvalSaya->total() }} menunggu
        </span>
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

    @if($errors->any())
        <div class="alert d-flex align-items-center gap-2 border-0 mb-4" style="background:#FCEBEA;color:#C4463F;border-radius:12px;">
            <i class="bi bi-exclamation-circle-fill"></i>
            <div>{{ $errors->first() }}</div>
        </div>
    @endif

    {{-- List --}}
    @forelse($approvalSaya as $a)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div class="d-flex gap-3">
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:44px;height:44px;border-radius:12px;background:var(--primary-light);color:var(--bs-primary);">
                            <i class="bi bi-file-earmark-text fs-5"></i>
                        </div>
                        <div>
                            <p class="mb-1 fw-semibold" style="color:var(--ink)">
                                {{ $a->suratKeluar->perihal }}
                            </p>
                            <div class="d-flex flex-wrap align-items-center gap-2" style="font-size:.78rem;color:var(--ink-muted);">
                                <span class="badge" style="background:var(--surface);color:#5B5D6B;font-weight:600;border:1px solid var(--border);">
                                    {{ $a->suratKeluar->kategori->nama_kategori }}
                                </span>
                                <span>&middot;</span>
                                <span>Diajukan oleh {{ $a->suratKeluar->pembuat->pegawai->nama_lengkap ?? $a->suratKeluar->pembuat->username }}</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('surat-keluar.show', $a->suratKeluar) }}"
                       class="btn btn-sm flex-shrink-0"
                       style="border:1px solid var(--border);border-radius:8px;color:var(--bs-primary);font-weight:600;background:#fff;">
                        Lihat detail <i class="bi bi-arrow-up-right"></i>
                    </a>
                </div>

                <div class="d-flex gap-2 mt-3 pt-3" style="border-top:1px solid var(--border);">
                    <form method="POST" action="{{ route('approval.setujui', ['approval' => $a->id_approval]) }}">
                        @csrf
                        <button type="submit" class="btn btn-sm text-white"
                                style="background:var(--bs-primary);border-radius:8px;font-weight:600;">
                            <i class="bi bi-check2"></i> Setujui
                        </button>
                    </form>

                    <button type="button" class="btn btn-sm btn-outline-danger"
                            style="border-radius:8px;font-weight:600;"
                            data-bs-toggle="collapse" data-bs-target="#tolak-{{ $a->id_approval }}">
                        <i class="bi bi-x-lg"></i> Tolak
                    </button>
                </div>

                {{-- Form Tolak --}}
                <div class="collapse mt-3" id="tolak-{{ $a->id_approval }}">
                    <form method="POST" action="{{ route('approval.tolak', ['approval' => $a->id_approval]) }}">
                        @csrf
                        <textarea name="catatan" rows="3" required
                                  class="form-control mb-2"
                                  style="border-radius:10px;border-color:var(--border);font-size:.85rem;"
                                  placeholder="Masukkan alasan penolakan..."></textarea>
                        <button type="submit" class="btn btn-sm btn-danger" style="border-radius:8px;font-weight:600;">
                            Konfirmasi Tolak
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:56px;height:56px;border-radius:14px;background:var(--primary-light);color:var(--bs-primary);">
                    <i class="bi bi-inbox fs-4"></i>
                </div>
                <p class="mb-0" style="color:var(--ink-muted);">
                    Tidak ada surat yang menunggu persetujuan Anda saat ini.
                </p>
            </div>
        </div>
    @endforelse

    @if($approvalSaya->hasPages())
        <div class="mt-4">
            {{ $approvalSaya->links('pagination::bootstrap-5') }}
        </div>
    @endif

@endsection