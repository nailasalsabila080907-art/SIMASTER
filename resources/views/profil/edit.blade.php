@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
@php
    $pegawai = $user->pegawai;
    $fotoUrl = $pegawai?->foto_path ? asset('storage/'.$pegawai->foto_path) : null;
@endphp

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('profil.index') }}" class="btn-icon-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h5 class="mb-0" style="color:var(--ink)">Edit Profil</h5>
            <p class="mb-0" style="color:var(--ink-muted);font-size:.83rem">
                Perbarui data pribadi dan akun login Anda.
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

            <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <p class="text-uppercase mb-3" style="font-size:.72rem;letter-spacing:.08em;font-weight:700;color:var(--bs-primary);">
                    <i class="bi bi-camera"></i> Foto Profil
                </p>

                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center flex-shrink-0 rounded-circle"
                         style="width:64px;height:64px;background:var(--primary-light);overflow:hidden;">
                        @if($fotoUrl)
                            <img src="{{ $fotoUrl }}" alt="Foto profil" class="w-100 h-100" style="object-fit:cover">
                        @else
                            <span class="fw-bold" style="font-size:1.3rem;color:var(--bs-primary)">
                                {{ strtoupper(substr($pegawai?->nama_lengkap ?? $user->username,0,1)) }}
                            </span>
                        @endif
                    </div>
                    <div class="flex-fill">
                        <input type="file" name="foto" accept="image/jpeg,image/png,image/webp" class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                        <p class="mb-0 mt-1" style="color:var(--ink-muted);font-size:.76rem">
                            Gunakan JPG, JPEG, PNG, atau WEBP maksimal 2 MB.
                        </p>
                    </div>
                </div>

                <hr class="my-4" style="border-color:var(--border);">

                <p class="text-uppercase mb-3" style="font-size:.72rem;letter-spacing:.08em;font-weight:700;color:var(--bs-primary);">
                    <i class="bi bi-person-vcard"></i> Data Identitas
                    <span class="text-normal fw-normal text-lowercase" style="color:var(--ink-muted);letter-spacing:normal;">(nama &amp; NIP dikunci)</span>
                </p>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nama Lengkap</label>
                        <input value="{{ $pegawai?->nama_lengkap }}" disabled class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;background:var(--surface);">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">NIP</label>
                        <input value="{{ $pegawai?->nip }}" disabled class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;background:var(--surface);">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Gelar Depan</label>
                        <input type="text" name="gelar_depan" value="{{ old('gelar_depan', $pegawai?->gelar_depan) }}"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Gelar Belakang</label>
                        <input type="text" name="gelar_belakang" value="{{ old('gelar_belakang', $pegawai?->gelar_belakang) }}"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                            <option value="L" @selected(old('jenis_kelamin', $pegawai?->jenis_kelamin) === 'L')>Laki-laki</option>
                            <option value="P" @selected(old('jenis_kelamin', $pegawai?->jenis_kelamin) === 'P')>Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $pegawai?->tempat_lahir) }}"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', optional($pegawai?->tanggal_lahir)->format('Y-m-d')) }}"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Pangkat/Golongan</label>
                        <input type="text" name="pangkat_golongan" value="{{ old('pangkat_golongan', $pegawai?->pangkat_golongan) }}"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">No. HP</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $pegawai?->no_hp) }}"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Email</label>
                        <input type="email" name="email" value="{{ old('email', $pegawai?->email) }}"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                    </div>
                </div>

                <hr class="my-4" style="border-color:var(--border);">

                <p class="text-uppercase mb-3" style="font-size:.72rem;letter-spacing:.08em;font-weight:700;color:var(--bs-primary);">
                    <i class="bi bi-shield-lock"></i> Akses Akun
                    <span class="text-normal fw-normal text-lowercase" style="color:var(--ink-muted);letter-spacing:normal;">(role &amp; status dikelola admin)</span>
                </p>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Username</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;" required>
                    </div>

                    <div class="col-md-6"></div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Password Baru</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak diganti"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                               class="form-control" style="border-radius:10px;border-color:var(--border);font-size:.87rem;">
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4 pt-3" style="border-top:1px solid var(--border);">
                    <button type="submit" class="btn text-white" style="background:var(--bs-primary);border-radius:10px;font-weight:600;font-size:.87rem;padding:.6rem 1.4rem;">
                        <i class="bi bi-check2"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('profil.index') }}" class="btn" style="border:1px solid var(--border);border-radius:10px;font-weight:600;font-size:.87rem;padding:.6rem 1.4rem;color:var(--ink);">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection
