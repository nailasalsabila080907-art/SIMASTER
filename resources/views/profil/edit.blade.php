@extends('layouts.app')
@section('title','Edit Profil')
@section('content')
@php
    $pegawai = $user->pegawai;
    $fotoUrl = $pegawai?->foto_path ? asset('storage/'.$pegawai->foto_path) : null;
@endphp

<div style="max-width:960px">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('profil.index') }}" class="btn btn-light rounded-circle d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:38px;height:38px" title="Kembali">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <p class="text-muted mb-1" style="font-size:.82rem">Pengaturan Akun</p>
            <h2 class="mb-0" style="font-size:1.5rem">Edit Profil</h2>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger rounded-3 d-flex gap-2" style="font-size:.85rem">
            <i class="bi bi-exclamation-triangle mt-1"></i>
            <div>
                <p class="fw-semibold mb-1">Periksa kembali isian berikut:</p>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card mb-3">
            <div class="card-header d-flex align-items-center gap-3">
                <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white flex-shrink-0"
                      style="width:38px;height:38px;background:linear-gradient(135deg,#178754,#0EA5A4);font-size:1rem">
                    <i class="bi bi-camera"></i>
                </span>
                <div>
                    <h3 class="mb-1" style="font-size:1.05rem">Foto Profil</h3>
                    <p class="text-muted mb-0" style="font-size:.78rem">Gunakan JPG, JPEG, PNG, atau WEBP dengan ukuran maksimal 2 MB</p>
                </div>
            </div>
            <div class="card-body d-flex flex-column flex-sm-row align-items-center gap-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle border border-4 border-white shadow-sm overflow-hidden flex-shrink-0"
                     style="width:96px;height:96px;background:#EDF1F5">
                    @if($fotoUrl)
                        <img src="{{ $fotoUrl }}" alt="Foto profil" class="w-100 h-100" style="object-fit:cover">
                    @else
                        <span class="fw-bold" style="font-size:1.6rem;color:#178754">{{ strtoupper(substr($pegawai?->nama_lengkap ?? $user->username,0,1)) }}</span>
                    @endif
                </div>
                <div class="flex-fill w-100">
                    <label class="form-label" style="font-size:.85rem">Pilih foto baru</label>
                    <input type="file" name="foto" accept="image/jpeg,image/png,image/webp" class="form-control">
                    <p class="text-muted mb-0 mt-2" style="font-size:.76rem">Foto akan digunakan pada halaman profil dan identitas pengguna.</p>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header d-flex align-items-center gap-3">
                <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white flex-shrink-0"
                      style="width:38px;height:38px;background:linear-gradient(135deg,#D98C00,#F0A202);font-size:1rem">
                    <i class="bi bi-person-vcard"></i>
                </span>
                <div>
                    <h3 class="mb-1" style="font-size:1.05rem">Data Identitas</h3>
                    <p class="text-muted mb-0" style="font-size:.78rem">Nama lengkap dan NIP dikunci untuk menjaga identitas kepegawaian</p>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">Nama Lengkap</label>
                        <input value="{{ $pegawai?->nama_lengkap }}" disabled class="form-control" style="background:var(--surface)">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">NIP</label>
                        <input value="{{ $pegawai?->nip }}" disabled class="form-control" style="background:var(--surface)">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">Gelar Depan</label>
                        <input type="text" name="gelar_depan" value="{{ old('gelar_depan', $pegawai?->gelar_depan) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">Gelar Belakang</label>
                        <input type="text" name="gelar_belakang" value="{{ old('gelar_belakang', $pegawai?->gelar_belakang) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select">
                            <option value="L" @selected(old('jenis_kelamin', $pegawai?->jenis_kelamin) === 'L')>Laki-laki</option>
                            <option value="P" @selected(old('jenis_kelamin', $pegawai?->jenis_kelamin) === 'P')>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $pegawai?->tempat_lahir) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', optional($pegawai?->tanggal_lahir)->format('Y-m-d')) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">Pangkat / Golongan</label>
                        <input type="text" name="pangkat_golongan" value="{{ old('pangkat_golongan', $pegawai?->pangkat_golongan) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">No. HP</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $pegawai?->no_hp) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">Email</label>
                        <input type="email" name="email" value="{{ old('email', $pegawai?->email) }}" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center gap-3">
                <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white flex-shrink-0"
                      style="width:38px;height:38px;background:linear-gradient(135deg,#3E4652,#5B5D6B);font-size:1rem">
                    <i class="bi bi-shield-lock"></i>
                </span>
                <div>
                    <h3 class="mb-1" style="font-size:1.05rem">Akses Akun</h3>
                    <p class="text-muted mb-0" style="font-size:.78rem">Username dapat diperbarui. Role dan status akun dikelola oleh administrator</p>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">Username</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" required class="form-control">
                    </div>
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diganti">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('profil.index') }}" class="btn btn-light">Batal</a>
            <button type="submit" class="btn d-inline-flex align-items-center gap-2 text-white"
                    style="background:linear-gradient(135deg,#178754,#0EA5A4);border:none">
                <i class="bi bi-save"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection