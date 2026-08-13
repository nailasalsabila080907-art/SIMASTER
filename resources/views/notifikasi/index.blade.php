@extends('layouts.app')
@section('title', 'Notifikasi')

@section('content')

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h5 class="mb-1" style="color:var(--ink)">Notifikasi</h5>
            <p class="mb-0" style="color:var(--ink-muted);font-size:.85rem">
                Semua notifikasi untuk Anda.
            </p>
        </div>
        <form method="POST" action="{{ route('notifikasi.tandai-semua') }}">
            @csrf
            <button type="submit" class="btn btn-sm d-inline-flex align-items-center gap-1"
                    style="border:1px solid var(--border);border-radius:8px;color:var(--bs-primary);font-weight:600;background:#fff;">
                <i class="bi bi-check2-all"></i> Tandai Semua Sudah Dibaca
            </button>
        </form>
    </div>

    {{-- Alerts --}}
    @if(session('sukses'))
        <div class="alert d-flex align-items-center gap-2 border-0 mb-4" style="background:var(--primary-light);color:var(--primary-dark);border-radius:12px;">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('sukses') }}</div>
        </div>
    @endif

    {{-- List --}}
    <div class="d-flex flex-column gap-2">
        @forelse($notifikasi as $n)
            <form method="POST" action="{{ route('notifikasi.tandai-dibaca', $n) }}">
                @csrf
                <button type="submit" class="card w-100 text-start border-0"
                        style="border-radius:12px;background:{{ $n->sudah_dibaca ? '#fff' : 'var(--primary-light)' }};">
                    <div class="card-body d-flex align-items-start gap-3 py-3 px-4">
                        @unless($n->sudah_dibaca)
                            <span class="rounded-circle flex-shrink-0 mt-1" style="width:8px;height:8px;background:var(--bs-primary);"></span>
                        @else
                            <span class="flex-shrink-0" style="width:8px;height:8px;"></span>
                        @endunless
                        <div class="flex-fill">
                            <p class="mb-0 fw-semibold" style="font-size:.87rem;color:var(--ink);">{{ $n->judul }}</p>
                            <p class="mb-0 mt-1" style="font-size:.83rem;color:var(--ink-muted);">{{ $n->pesan }}</p>
                            <p class="mb-0 mt-2" style="font-size:.72rem;color:var(--ink-muted);">{{ $n->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </button>
            </form>
        @empty
            <div class="card">
                <div class="card-body text-center py-5" style="color:var(--ink-muted);">
                    <div class="d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:56px;height:56px;border-radius:14px;background:var(--primary-light);color:var(--bs-primary);">
                        <i class="bi bi-bell fs-4"></i>
                    </div>
                    <p class="mb-0">Belum ada notifikasi.</p>
                </div>
            </div>
        @endforelse
    </div>

    @if($notifikasi->hasPages())
        <div class="mt-4">
            {{ $notifikasi->links('pagination::bootstrap-5') }}
        </div>
    @endif

@endsection
