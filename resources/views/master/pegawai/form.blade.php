@extends('layouts.app')

@section('title', $pegawai->exists ? 'Ubah Pegawai' : 'Tambah Pegawai')

@section('content')

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('pegawai.index') }}" class="btn-icon-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h5 class="mb-0" style="color:var(--ink)">
                {{ $pegawai->exists ? 'Ubah Pegawai' : 'Tambah Pegawai' }}
            </h5>
            <p class="mb-0" style="color:var(--ink-muted);font-size:.83rem">
                Lengkapi data pegawai di bawah ini.
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

            <form method="POST" action="{{ $pegawai->exists ? route('pegawai.update', $pegawai) : route('pegawai.store') }}">
                @csrf
                @if($pegawai->exists) @method('PUT') @endif

                <p class="text-uppercase mb-3" style="font-size:.72rem;letter-spacing:.08em;font-weight:700;color:var(--bs-primary);">
                    <i class="bi bi-person-vcard"></i> Data Pegawai
                </p>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $pegawai->nama_lengkap) }}"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Gelar Depan</label>
                        <input type="text" name="gelar_depan" value="{{ old('gelar_depan', $pegawai->gelar_depan) }}"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Gelar Belakang</label>
                        <input type="text" name="gelar_belakang" value="{{ old('gelar_belakang', $pegawai->gelar_belakang) }}"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">NIP</label>
                        <input type="text" name="nip" value="{{ old('nip', $pegawai->nip) }}"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" style="border-radius:10px;border-color:var(--border);font-size:.87rem;" required>
                            <option value="L" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Jabatan</label>
                        <select name="id_jabatan" class="form-select" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                            <option value="">-- Pilih jabatan --</option>
                            @foreach($jabatanList as $j)
                                <option value="{{ $j->id_jabatan }}" {{ (string) old('id_jabatan', $pegawai->id_jabatan) === (string) $j->id_jabatan ? 'selected' : '' }}>
                                    {{ $j->nama_jabatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Unit Kerja</label>
                        <select name="id_unit" class="form-select" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                            <option value="">-- Pilih unit --</option>
                            @foreach($unitList as $u)
                                <option value="{{ $u->id_unit }}" {{ (string) old('id_unit', $pegawai->id_unit) === (string) $u->id_unit ? 'selected' : '' }}>
                                    {{ $u->nama_unit }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Pangkat/Golongan</label>
                        <input type="text" name="pangkat_golongan" value="{{ old('pangkat_golongan', $pegawai->pangkat_golongan) }}"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">No. HP</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $pegawai->no_hp) }}"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                    </div>
                </div>

                @unless($pegawai->exists)
                    <hr class="my-4" style="border-color:var(--border);">

                    <p class="text-uppercase mb-3" style="font-size:.72rem;letter-spacing:.08em;font-weight:700;color:var(--bs-primary);">
                        <i class="bi bi-shield-lock"></i> Akun Login
                        <span class="text-normal fw-normal text-lowercase" style="color:var(--ink-muted);letter-spacing:normal;">(opsional, boleh dikosongkan)</span>
                    </p>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Username</label>
                            <input type="text" name="username" value="{{ old('username') }}"
                                   class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Password</label>
                            <input type="password" name="password"
                                   class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold">Role</label>
                            <select name="role" class="form-select" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                                @foreach(['admin_tu' => 'Admin TU', 'kepala_sekolah' => 'Kepala Sekolah', 'staff' => 'Staff', 'guru' => 'Guru', 'super_admin' => 'Super Admin'] as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endunless

                <div class="d-flex gap-2 mt-4 pt-3" style="border-top:1px solid var(--border);">
                    <button type="submit" class="btn text-white" style="background:var(--bs-primary);border-radius:10px;font-weight:600;font-size:.87rem;padding:.6rem 1.4rem;">
                        <i class="bi bi-check2"></i> Simpan
                    </button>
                    <a href="{{ route('pegawai.index') }}" class="btn" style="border:1px solid var(--border);border-radius:10px;font-weight:600;font-size:.87rem;padding:.6rem 1.4rem;color:var(--ink);">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection
