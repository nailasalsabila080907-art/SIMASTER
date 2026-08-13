@extends('layouts.app')

@section('title', 'Ubah Pengguna')

@section('content')

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('pengguna.index') }}" class="btn-icon-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h5 class="mb-0" style="color:var(--ink)">Ubah Pengguna</h5>
            <p class="mb-0" style="color:var(--ink-muted);font-size:.83rem">
                Ubah username, role, status, atau reset password akun.
            </p>
        </div>
    </div>

    <div class="card" style="max-width:760px;">
        <div class="card-body p-4">

            @if($errors->any())
                <div class="alert d-flex align-items-start gap-2 border-0 mb-4" style="background:#FCEBEA;color:#C4463F;border-radius:12px;font-size:.85rem;">
                    <i class="bi bi-exclamation-circle-fill mt-1"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="d-flex align-items-center gap-3 rounded-3 px-3 py-3 mb-4" style="background:var(--bs-light,#f8f9fa);">
                <div class="d-flex align-items-center justify-content-center rounded-circle"
                     style="width:42px;height:42px;background:#EEF1FF;color:var(--bs-primary);font-weight:700;">
                    {{ strtoupper(substr($pengguna->username, 0, 1)) }}
                </div>
                <div>
                    <p class="fw-semibold mb-0" style="color:var(--ink)">{{ $pengguna->pegawai->nama_lengkap ?? '-' }}</p>
                    <p class="mb-0" style="color:var(--ink-muted);font-size:.78rem">NIP: {{ $pengguna->pegawai->nip ?? '-' }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('pengguna.update', $pengguna) }}">
                @csrf
                @method('PUT')

                <p class="text-uppercase mb-3" style="font-size:.72rem;letter-spacing:.08em;font-weight:700;color:var(--bs-primary);">
                    <i class="bi bi-shield-lock"></i> Akun Login
                </p>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Username</label>
                        <input type="text" name="username" value="{{ old('username', $pengguna->username) }}"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Role</label>
                        <select name="role" class="form-select" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                            @foreach(['super_admin', 'admin_tu', 'kepala_sekolah', 'staff', 'guru', 'operator'] as $r)
                                <option value="{{ $r }}" {{ old('role', $pengguna->role) === $r ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $r)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                            <option value="aktif" {{ old('status', $pengguna->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $pengguna->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Password Baru</label>
                        <input type="password" name="password"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;"
                               placeholder="Kosongkan jika tidak diubah">
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4 pt-3" style="border-top:1px solid var(--border);">
                    <button type="submit" class="btn text-white" style="background:var(--bs-primary);border-radius:10px;font-weight:600;font-size:.87rem;padding:.6rem 1.4rem;">
                        <i class="bi bi-check2"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('pengguna.index') }}" class="btn" style="border:1px solid var(--border);border-radius:10px;font-weight:600;font-size:.87rem;padding:.6rem 1.4rem;color:var(--ink);">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection
