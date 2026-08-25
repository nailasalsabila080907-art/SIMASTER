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
        <div class="position-relative" style="width:260px;">
            <i class="bi bi-search position-absolute" style="left:12px;top:50%;transform:translateY(-50%);color:var(--ink-muted);font-size:.85rem;"></i>
            <input
                type="text"
                name="cari"
                value="{{ $filterCari }}"
                placeholder="Cari pengguna, modul, deskripsi..."
                class="form-control form-control-sm"
                style="border-radius:10px;border-color:var(--border);font-size:.85rem;padding-left:32px;"
            >
        </div>

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

        @if($filterUserId || $filterAktivitas || $filterCari)
            <a href="{{ route('log-aktivitas.index') }}" class="d-inline-flex align-items-center gap-1" style="color:var(--ink-muted);font-size:.83rem;">
                <i class="bi bi-x-circle"></i> Reset filter
            </a>
        @endif

        <button type="submit" class="btn btn-sm" style="border-radius:10px;background:var(--bs-primary);color:#fff;font-size:.85rem;">
            <i class="bi bi-search me-1"></i> Cari
        </button>
    </form>

    {{-- Table --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size:.85rem;">
                <thead>
                    <tr style="background:var(--surface);">
                        <th class="px-4 py-3 border-0 text-nowrap" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Waktu</th>
                        @if($bolehLihatSemua)
                            <th class="py-3 border-0 text-nowrap" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Pengguna</th>
                            <th class="py-3 border-0 text-nowrap" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Role</th>
                        @endif
                        <th class="py-3 border-0 text-nowrap" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Aksi</th>
                        <th class="py-3 border-0" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Modul / Deskripsi</th>
                        <th class="py-3 border-0 text-nowrap" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Alamat IP</th>
                        <th class="px-4 py-3 border-0 text-nowrap" style="color:var(--ink-muted);font-weight:700;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;">Perangkat / Browser</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr style="border-top:1px solid var(--border);">
                            {{-- Waktu --}}
                            <td class="px-4 py-3 text-nowrap" style="color:var(--ink-muted);font-size:.8rem;">
                                {{ $log->created_at->diffForHumans() }}
                            </td>

                            @if($bolehLihatSemua)
                                {{-- Pengguna --}}
                                <td class="py-3 fw-semibold text-nowrap" style="color:var(--ink);">
                                    {{ $log->user->pegawai->nama_lengkap ?? $log->user->username ?? 'User Terhapus' }}
                                </td>

                                {{-- Role --}}
                                <td class="py-3 text-nowrap">
                                    @php
                                        $roleColors = [
                                            'super_admin'     => 'background:#fde2e2;color:#9c1c1c;',
                                            'admin_tu'        => 'background:#dbeafe;color:#1d4ed8;',
                                            'kepala_sekolah'  => 'background:#ede9fe;color:#5b21b6;',
                                        ];
                                        $roleStyle = $roleColors[$log->role] ?? 'background:#f1f5f9;color:#475569;';
                                    @endphp
                                    <span class="badge" style="{{ $roleStyle }}font-weight:600;font-size:.7rem;border-radius:20px;padding:.35em .7em;">
                                        {{ $log->role ? ucwords(str_replace('_', ' ', $log->role)) : '-' }}
                                    </span>
                                </td>
                            @endif

                            {{-- Aksi --}}
                            <td class="py-3 text-nowrap">
                                @php
                                    $aksiLabel = match($log->aktivitas) {
                                        'login' => 'Login',
                                        'logout' => 'Logout',
                                        'lihat_halaman' => 'View',
                                        'tambah_data' => 'Create',
                                        'ubah_data' => 'Update',
                                        'hapus_data' => 'Delete',
                                        default => ucwords(str_replace('_', ' ', $log->aktivitas)),
                                    };
                                    $aksiColors = [
                                        'Login' => 'background:#dcfce7;color:#166534;',
                                        'Logout' => 'background:#f1f5f9;color:#475569;',
                                        'View' => 'background:#e2d1f9;color:#5a3791;',
                                        'Create' => 'background:#dbeafe;color:#1d4ed8;',
                                        'Update' => 'background:#fef3c7;color:#92400e;',
                                        'Delete' => 'background:#fde2e2;color:#9c1c1c;',
                                    ];
                                    $aksiStyle = $aksiColors[$aksiLabel] ?? 'background:var(--primary-light);color:var(--primary-dark);';
                                @endphp
                                <span class="badge text-uppercase" style="{{ $aksiStyle }}font-weight:700;font-size:.68rem;letter-spacing:.03em;border-radius:6px;padding:.35em .7em;">
                                    {{ $aksiLabel }}
                                </span>
                            </td>

                            {{-- Modul / Deskripsi --}}
                            <td class="py-3" style="color:var(--ink);">
                                @if($log->modul)
                                    <strong class="d-block small text-muted mb-1">[{{ $log->modul }}]</strong>
                                @endif
                                <span style="white-space:normal;word-break:break-word;">
                                    {{ $log->deskripsi ?? '-' }}
                                </span>
                            </td>

                            {{-- Alamat IP --}}
                            <td class="py-3 text-nowrap">
                                <code style="font-size:.78rem;font-weight:600;color:var(--bs-primary);background:var(--surface);padding:.2em .5em;border-radius:6px;">
                                    {{ $log->ip_address ?? '-' }}
                                </code>
                            </td>

                            {{-- Perangkat / Browser --}}
                            <td class="px-4 py-3" style="color:var(--ink);font-size:.8rem;white-space:normal;word-break:break-word;min-width:240px;">
                                <div class="fw-semibold">{{ $log->perangkat }}</div>
                                <div class="text-muted" style="font-size:.7rem;word-break:break-all;">{{ $log->user_agent ?? '-' }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $bolehLihatSemua ? 7 : 5 }}" class="text-center py-5" style="color:var(--ink-muted);">
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