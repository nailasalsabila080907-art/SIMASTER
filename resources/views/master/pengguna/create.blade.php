@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('pengguna.index') }}" class="btn-icon-ghost">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h5 class="mb-0" style="color:var(--ink)">Tambah Pengguna</h5>
        <p class="mb-0" style="color:var(--ink-muted);font-size:.83rem">
            Buat akun login untuk pegawai yang belum memiliki akun.
        </p>
    </div>
</div>

<div class="card" style="max-width:760px;">
    <div class="card-body p-4">

        @if($errors->any())
            <div class="alert border-0 mb-4" style="background:#FCEBEA;color:#C4463F;border-radius:12px;font-size:.85rem;">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if(session('gagal'))
            <div class="alert border-0 mb-4" style="background:#FCEBEA;color:#C4463F;border-radius:12px;font-size:.85rem;">
                {{ session('gagal') }}
            </div>
        @endif

        <form method="POST" action="{{ route('pengguna.store') }}">
            @csrf

            <p class="text-uppercase mb-3" style="font-size:.72rem;letter-spacing:.08em;font-weight:700;color:var(--bs-primary);">
                <i class="bi bi-person-plus"></i> Data Akun
            </p>

            <div class="row g-3">

                <div class="col-12">
                    <label class="form-label small fw-semibold">Pegawai</label>
                    <select name="id_pegawai" class="form-select" required
                            style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                        <option value="">-- Pilih Pegawai --</option>

                        @foreach($pegawai as $p)
                            <option value="{{ $p->id_pegawai }}"
                                {{ old('id_pegawai') == $p->id_pegawai ? 'selected' : '' }}>
                                {{ $p->nama_lengkap }}
                                — {{ $p->jabatan->nama_jabatan ?? 'Tanpa Jabatan' }}
                                — NIP: {{ $p->nip ?? '-' }}
                            </option>
                        @endforeach
                    </select>

                    @if($pegawai->isEmpty())
                        <small class="text-danger">
                            Semua pegawai aktif sudah memiliki akun.
                        </small>
                    @endif
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Username</label>
                    <input type="text"
                           name="username"
                           value="{{ old('username') }}"
                           class="form-control"
                           style="border-radius:10px;border-color:var(--border);font-size:.87rem;"
                           placeholder="Masukkan username"
                           required>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Password</label>
                    <input type="password"
                           name="password"
                           class="form-control"
                           style="border-radius:10px;border-color:var(--border);font-size:.87rem;"
                           placeholder="Minimal 6 karakter"
                           required>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Role</label>
                    <select name="role" class="form-select" required
                            style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                        @foreach([
                            'super_admin',
                            'admin_tu',
                            'kepala_sekolah',
                            'staff',
                            'guru',
                            'operator'
                        ] as $r)
                            <option value="{{ $r }}"
                                {{ old('role') === $r ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $r)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Status</label>
                    <select name="status" class="form-select" required
                            style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                        <option value="aktif"
                            {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>
                            Aktif
                        </option>
                        <option value="nonaktif"
                            {{ old('status') === 'nonaktif' ? 'selected' : '' }}>
                            Nonaktif
                        </option>
                    </select>
                </div>

            </div>

            <div class="d-flex gap-2 mt-4 pt-3"
                 style="border-top:1px solid var(--border);">

                <button type="submit"
                        class="btn text-white"
                        style="background:var(--bs-primary);border-radius:10px;font-weight:600;font-size:.87rem;padding:.6rem 1.4rem;">
                    <i class="bi bi-check2"></i>
                    Simpan Pengguna
                </button>

                <a href="{{ route('pengguna.index') }}"
                   class="btn"
                   style="border:1px solid var(--border);border-radius:10px;font-weight:600;font-size:.87rem;padding:.6rem 1.4rem;color:var(--ink);">
                    Batal
                </a>

            </div>

        </form>

    </div>
</div>

@endsection