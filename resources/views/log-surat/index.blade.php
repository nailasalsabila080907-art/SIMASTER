@extends('layouts.app')

@section('title', 'Riwayat Surat')

@section('content')

    <div class="mb-4">
        <h5 class="mb-0" style="color:var(--ink)">Riwayat Surat</h5>
        <p class="mb-0" style="color:var(--ink-muted);font-size:.85rem">
            @if($bolehLihatSemua)
                Riwayat aktivitas seluruh surat masuk & surat keluar.
            @else
                Riwayat aktivitas surat yang Anda kelola.
            @endif
        </p>
    </div>

    {{-- Filter --}}
    <form method="GET" class="d-flex flex-wrap align-items-center gap-2 mb-3">
        @if($bolehLihatSemua)
            <select name="user_id" onchange="this.form.submit()" class="form-select form-select-sm w-auto" style="border-radius:10px;border-color:var(--border);font-size:.85rem;">
                <option value="">Semua pengguna</option>
                @foreach($daftarUser as $u)
                    <option value="{{ $u->id_user }}" {{ (string) $filterUserId === (string) $u->id_user ? 'selected' : '' }}>
                        {{ $u->pegawai->nama_lengkap ?? $u->username }}
                    </option>
                @endforeach
            </select>
        @endif

        <select name="tipe_surat" onchange="this.form.submit()" class="form-select form-select-sm w-auto" style="border-radius:10px;border-color:var(--border);font-size:.85rem;">
            <option value="">Semua jenis surat</option>
            <option value="masuk" {{ $filterTipeSurat === 'masuk' ? 'selected' : '' }}>Surat Masuk</option>
            <option value="keluar" {{ $filterTipeSurat === 'keluar' ? 'selected' : '' }}>Surat Keluar</option>
        </select>

        <select name="aktivitas" onchange="this.form.submit()" class="form-select form-select-sm w-auto" style="border-radius:10px;border-color:var(--border);font-size:.85rem;">
            <option value="">Semua aktivitas</option>
            @foreach(['dibuat','diedit','diajukan','disposisi','tindak_lanjut','disposisi_selesai','selesai','approve','tolak','terbit','arsip','dihapus','cetak_pdf'] as $opsi)
                <option value="{{ $opsi }}" {{ $filterAktivitas === $opsi ? 'selected' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $opsi)) }}
                </option>
            @endforeach
        </select>

        @if($filterUserId || $filterTipeSurat || $filterAktivitas)
            <a href="{{ route('log-surat.index') }}" class="d-inline-flex align-items-center gap-1" style="color:var(--ink-muted);font-size:.83rem;">
                <i class="bi bi-x-circle"></i> Reset filter
            </a>
        @endif
    </form>

    {{-- Table --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size:.85rem;">
                <thead>
                    <tr style="background:var(--surface);">
                        @if($bolehLihatSemua)
                            <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Pengguna</th>
                        @endif
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Surat</th>
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Aktivitas</th>
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Keterangan</th>
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr style="border-top:1px solid var(--border);">
                            @if($bolehLihatSemua)
                                <td class="px-4 py-3 fw-semibold" style="color:var(--ink);">{{ $log->user->pegawai->nama_lengkap ?? $log->user->username ?? '-' }}</td>
                            @endif
                            <td class="px-4 py-3" style="color:var(--ink);">
                                <span class="badge" style="background:{{ $log->tipe_surat === 'masuk' ? '#E6F0FA' : '#FDF3E3' }};color:{{ $log->tipe_surat === 'masuk' ? '#1B5FA8' : '#B8760A' }};font-weight:600;font-size:.72rem;border-radius:20px;">
                                    {{ $log->tipe_surat === 'masuk' ? 'Surat Masuk' : 'Surat Keluar' }}
                                </span>
                                <div style="font-size:.78rem;color:var(--ink-muted);margin-top:.15rem;">
                                    {{ $log->surat?->perihal ?? '-' }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge" style="background:var(--primary-light);color:var(--primary-dark);font-weight:600;font-size:.72rem;border-radius:20px;">
                                    {{ ucwords(str_replace('_', ' ', $log->aktivitas)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3" style="color:var(--ink);">{{ $log->keterangan ?? '-' }}</td>
                            <td class="px-4 py-3 text-nowrap" style="color:var(--ink-muted);font-size:.8rem;">{{ $log->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $bolehLihatSemua ? 5 : 4 }}" class="text-center py-5" style="color:var(--ink-muted);">
                                <div class="d-inline-flex align-items-center justify-content-center mb-3"
                                     style="width:56px;height:56px;border-radius:14px;background:var(--primary-light);color:var(--bs-primary);">
                                    <i class="bi bi-clock-history fs-4"></i>
                                </div>
                                <p class="mb-0">Belum ada riwayat surat tercatat.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($logs->hasPages())
        <div class="mt-4">
            {{ $logs->links('pagination::bootstrap-5') }}
        </div>
    @endif

@endsection