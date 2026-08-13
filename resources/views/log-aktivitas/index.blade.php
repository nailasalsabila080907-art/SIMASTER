@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')

    <div class="mb-4">
        <h5 class="mb-0" style="color:var(--ink)">Log Aktivitas</h5>
        <p class="mb-0" style="color:var(--ink-muted);font-size:.85rem">
            @if($bolehLihatSemua)
                Riwayat aktivitas seluruh pengguna sistem.
            @else
                Riwayat aktivitas Anda di sistem.
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

        <select name="aktivitas" onchange="this.form.submit()" class="form-select form-select-sm w-auto" style="border-radius:10px;border-color:var(--border);font-size:.85rem;">
            <option value="">Semua aktivitas</option>
            @foreach(['login', 'logout', 'lihat_halaman', 'tambah_data', 'ubah_data', 'hapus_data'] as $opsi)
                <option value="{{ $opsi }}" {{ $filterAktivitas === $opsi ? 'selected' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $opsi)) }}
                </option>
            @endforeach
        </select>

        @if($filterUserId || $filterAktivitas)
            <a href="{{ route('log-aktivitas.index') }}" class="d-inline-flex align-items-center gap-1" style="color:var(--ink-muted);font-size:.83rem;">
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
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Aktivitas</th>
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Deskripsi</th>
                        <th class="px-4 py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr style="border-top:1px solid var(--border);">
                            @if($bolehLihatSemua)
                                <td class="px-4 py-3 fw-semibold" style="color:var(--ink);">{{ $log->user->pegawai->nama_lengkap ?? $log->user->username ?? '-' }}</td>
                            @endif
                            <td class="px-4 py-3">
                                <span class="badge" style="background:var(--primary-light);color:var(--primary-dark);font-weight:600;font-size:.72rem;border-radius:20px;">
                                    {{ ucwords(str_replace('_', ' ', $log->aktivitas)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3" style="color:var(--ink);">{{ $log->deskripsi ?? '-' }}</td>
                            <td class="px-4 py-3 text-nowrap" style="color:var(--ink-muted);font-size:.8rem;">{{ $log->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $bolehLihatSemua ? 4 : 3 }}" class="text-center py-5" style="color:var(--ink-muted);">
                                <div class="d-inline-flex align-items-center justify-content-center mb-3"
                                     style="width:56px;height:56px;border-radius:14px;background:var(--primary-light);color:var(--bs-primary);">
                                    <i class="bi bi-clock-history fs-4"></i>
                                </div>
                                <p class="mb-0">Belum ada aktivitas tercatat.</p>
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
