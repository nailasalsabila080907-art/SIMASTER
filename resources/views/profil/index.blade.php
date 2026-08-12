@extends('layouts.app')
@section('title','Profil Saya')
@section('content')
@php
    $pegawai = $user->pegawai;
    $fotoUrl = $pegawai?->foto_path ? asset('storage/'.$pegawai->foto_path) : null;
    $nama = $pegawai?->nama_lengkap ?? $user->username;
    $jabatan = $pegawai?->jabatan?->nama_jabatan ?? 'Belum ditentukan';
    $unit = $pegawai?->unitKerja?->nama_unit ?? 'Belum ditentukan';
    $jurusan = $pegawai?->jurusan?->nama_jurusan ?? 'Belum ditentukan';
    $statusPegawai = $pegawai?->status === 'aktif' ? 'Aktif' : 'Nonaktif';
    $statusAkun = $user->status === 'aktif' ? 'Aktif' : 'Nonaktif';
@endphp

<div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
    <div>
        <p class="text-muted mb-1" style="font-size:.82rem">Akun Pengguna</p>
        <h2 class="mb-1" style="font-size:1.5rem">Profil Saya</h2>
        <p class="text-muted mb-0" style="font-size:.78rem">Informasi akun dan data kepegawaian Anda</p>
    </div>
</div>

@if(session('sukses'))
    <div class="alert alert-success rounded-3 d-flex align-items-center gap-2 mb-4" style="font-size:.85rem" role="alert">
        <i class="bi bi-check-circle"></i>
        <div>{{ session('sukses') }}</div>
    </div>
@endif

@if(session('gagal'))
    <div class="alert alert-danger rounded-3 d-flex align-items-center gap-2 mb-4" style="font-size:.85rem" role="alert">
        <i class="bi bi-x-circle"></i>
        <div>{{ session('gagal') }}</div>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger rounded-3 mb-4" style="font-size:.85rem">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-3 mb-3">
    {{-- Kartu identitas --}}
    <div class="col-lg-4">
        <div class="card h-100 overflow-hidden">
            <div style="height:88px;background:linear-gradient(135deg,#178754,#0EA5A4)"></div>
            <div class="card-body text-center" style="margin-top:-48px">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle border border-4 border-white shadow-sm overflow-hidden"
                     style="width:96px;height:96px;background:#EDF1F5">
                    @if($fotoUrl)
                        <img src="{{ $fotoUrl }}" alt="Foto {{ $nama }}" class="w-100 h-100" style="object-fit:cover">
                    @else
                        <span class="fw-bold" style="font-size:1.6rem;color:#178754">{{ strtoupper(substr($nama,0,1)) }}</span>
                    @endif
                </div>

                <h3 class="mt-3 mb-1" style="font-size:1.1rem">{{ $nama }}</h3>
                <p class="text-muted mb-3" style="font-size:.82rem">{{ $jabatan }}</p>

                <span class="badge rounded-pill text-bg-light text-muted d-inline-flex align-items-center gap-2" style="font-size:.75rem;padding:.45rem .85rem">
                    <span class="d-inline-block rounded-circle" style="width:6px;height:6px;background:#178754"></span>
                    {{ $statusAkun }}
                </span>

                <div class="mt-4 pt-3 border-top text-start" style="font-size:.85rem">
                    <div class="d-flex justify-content-between gap-3 py-1">
                        <span class="text-muted">Username</span>
                        <span class="fw-semibold">{{ $user->username }}</span>
                    </div>
                    <div class="d-flex justify-content-between gap-3 py-1">
                        <span class="text-muted">Role</span>
                        <span class="fw-semibold">{{ ucwords(str_replace('_',' ', $user->role)) }}</span>
                    </div>
                </div>

                <a href="{{ route('profil.edit') }}" class="btn w-100 mt-4 d-inline-flex align-items-center justify-content-center gap-2 text-white"
                   style="background:linear-gradient(135deg,#178754,#0EA5A4);border:none">
                    <i class="bi bi-pencil-square"></i> Edit Profil
                </a>
            </div>
        </div>
    </div>

    {{-- Data kepegawaian --}}
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center gap-3">
                <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white flex-shrink-0"
                      style="width:38px;height:38px;background:linear-gradient(135deg,#D98C00,#F0A202);font-size:1rem">
                    <i class="bi bi-person-badge"></i>
                </span>
                <div>
                    <h3 class="mb-1" style="font-size:1.05rem">Data Kepegawaian</h3>
                    <p class="text-muted mb-0" style="font-size:.78rem">Informasi utama yang terdaftar pada sistem</p>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em">Nama Lengkap</p>
                        <p class="fw-semibold mb-0" style="font-size:.9rem">{{ $nama }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em">NIP</p>
                        <p class="fw-semibold mb-0" style="font-size:.9rem">{{ $pegawai?->nip ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em">Jabatan</p>
                        <p class="fw-semibold mb-0" style="font-size:.9rem">{{ $jabatan }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em">Unit Kerja</p>
                        <p class="fw-semibold mb-0" style="font-size:.9rem">{{ $unit }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em">Jurusan</p>
                        <p class="fw-semibold mb-0" style="font-size:.9rem">{{ $jurusan }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em">Jenis Kelamin</p>
                        <p class="fw-semibold mb-0" style="font-size:.9rem">{{ $pegawai?->jenis_kelamin === 'P' ? 'Perempuan' : 'Laki-laki' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em">Status Pegawai</p>
                        <span class="badge rounded-pill text-bg-success" style="font-size:.72rem">{{ $statusPegawai }}</span>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em">Username</p>
                        <p class="fw-semibold mb-0" style="font-size:.9rem">{{ $user->username }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Informasi pribadi --}}
<div class="card">
    <div class="card-header d-flex align-items-center gap-3">
        <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white flex-shrink-0"
              style="width:38px;height:38px;background:linear-gradient(135deg,#3E4652,#5B5D6B);font-size:1rem">
            <i class="bi bi-card-list"></i>
        </span>
        <div>
            <h3 class="mb-1" style="font-size:1.05rem">Informasi Pribadi</h3>
            <p class="text-muted mb-0" style="font-size:.78rem">Data tambahan yang dapat Anda perbarui melalui menu edit profil</p>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-4">
                <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em">Tempat, Tanggal Lahir</p>
                <p class="fw-semibold mb-0" style="font-size:.9rem">
                    {{ $pegawai?->tempat_lahir ?: '-' }}{{ $pegawai?->tanggal_lahir ? ', '.$pegawai->tanggal_lahir->format('d F Y') : '' }}
                </p>
            </div>
            <div class="col-md-4">
                <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em">Pangkat / Golongan</p>
                <p class="fw-semibold mb-0" style="font-size:.9rem">{{ $pegawai?->pangkat_golongan ?: '-' }}</p>
            </div>
            <div class="col-md-4">
                <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em">No. HP</p>
                <p class="fw-semibold mb-0" style="font-size:.9rem">{{ $pegawai?->no_hp ?: '-' }}</p>
            </div>
            <div class="col-md-4">
                <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em">Email</p>
                <p class="fw-semibold mb-0" style="font-size:.9rem">{{ $pegawai?->email ?: '-' }}</p>
            </div>
            <div class="col-md-4">
                <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em">Gelar Depan</p>
                <p class="fw-semibold mb-0" style="font-size:.9rem">{{ $pegawai?->gelar_depan ?: '-' }}</p>
            </div>
            <div class="col-md-4">
                <p class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em">Gelar Belakang</p>
                <p class="fw-semibold mb-0" style="font-size:.9rem">{{ $pegawai?->gelar_belakang ?: '-' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection