@extends('layouts.app')

@section('title', 'Profil Saya')

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

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h5 class="mb-1" style="color:var(--ink)">Profil Saya</h5>
            <p class="mb-0" style="color:var(--ink-muted);font-size:.85rem">
                Informasi akun dan data kepegawaian Anda.
            </p>
        </div>
        <a href="{{ route('profil.edit') }}" class="btn btn-sm text-white d-inline-flex align-items-center gap-1"
           style="background:var(--bs-primary);border-radius:8px;font-weight:600;padding:.55rem 1rem;">
            <i class="bi bi-pencil-square"></i> Edit Profil
        </a>
    </div>

    @if(session('sukses'))
        <div class="alert d-flex align-items-center gap-2 border-0 mb-4" style="background:var(--primary-light);color:var(--primary-dark);border-radius:12px;">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('sukses') }}</div>
        </div>
    @endif

    @if(session('gagal'))
        <div class="alert d-flex align-items-center gap-2 border-0 mb-4" style="background:#FCEBEA;color:#C4463F;border-radius:12px;">
            <i class="bi bi-exclamation-circle-fill"></i>
            <div>{{ session('gagal') }}</div>
        </div>
    @endif

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

    <div class="row g-3 mb-3">
        {{-- Kartu identitas --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body p-4 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                         style="width:88px;height:88px;background:var(--primary-light);overflow:hidden;">
                        @if($fotoUrl)
                            <img src="{{ $fotoUrl }}" alt="Foto {{ $nama }}" class="w-100 h-100" style="object-fit:cover">
                        @else
                            <span class="fw-bold" style="font-size:1.6rem;color:var(--bs-primary)">{{ strtoupper(substr($nama,0,1)) }}</span>
                        @endif
                    </div>

                    <h6 class="mb-1" style="color:var(--ink)">{{ $nama }}</h6>
                    <p class="mb-3" style="color:var(--ink-muted);font-size:.82rem">{{ $jabatan }}</p>

                    <span class="badge d-inline-flex align-items-center gap-2" style="background:var(--primary-light);color:var(--primary-dark);font-weight:600;font-size:.75rem;padding:.45rem .85rem;border-radius:20px;">
                        <span class="d-inline-block rounded-circle" style="width:6px;height:6px;background:var(--bs-primary)"></span>
                        {{ $statusAkun }}
                    </span>

                    <div class="mt-4 pt-3 text-start" style="border-top:1px solid var(--border);font-size:.85rem">
                        <div class="d-flex justify-content-between gap-3 py-1">
                            <span style="color:var(--ink-muted)">Username</span>
                            <span class="fw-semibold" style="color:var(--ink)">{{ $user->username }}</span>
                        </div>
                        <div class="d-flex justify-content-between gap-3 py-1">
                            <span style="color:var(--ink-muted)">Role</span>
                            <span class="fw-semibold" style="color:var(--ink)">{{ ucwords(str_replace('_',' ', $user->role)) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Data kepegawaian --}}
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-body p-4">
                    <p class="text-uppercase mb-3" style="font-size:.72rem;letter-spacing:.08em;font-weight:700;color:var(--bs-primary);">
                        <i class="bi bi-person-badge"></i> Data Kepegawaian
                    </p>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <p class="mb-1" style="color:var(--ink-muted);font-size:.7rem;text-transform:uppercase;letter-spacing:.05em">Nama Lengkap</p>
                            <p class="fw-semibold mb-0" style="font-size:.9rem;color:var(--ink)">{{ $nama }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1" style="color:var(--ink-muted);font-size:.7rem;text-transform:uppercase;letter-spacing:.05em">NIP</p>
                            <p class="fw-semibold mb-0 font-monospace" style="font-size:.85rem;color:var(--ink)">{{ $pegawai?->nip ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1" style="color:var(--ink-muted);font-size:.7rem;text-transform:uppercase;letter-spacing:.05em">Jabatan</p>
                            <p class="fw-semibold mb-0" style="font-size:.9rem;color:var(--ink)">{{ $jabatan }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1" style="color:var(--ink-muted);font-size:.7rem;text-transform:uppercase;letter-spacing:.05em">Unit Kerja</p>
                            <p class="fw-semibold mb-0" style="font-size:.9rem;color:var(--ink)">{{ $unit }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1" style="color:var(--ink-muted);font-size:.7rem;text-transform:uppercase;letter-spacing:.05em">Jurusan</p>
                            <p class="fw-semibold mb-0" style="font-size:.9rem;color:var(--ink)">{{ $jurusan }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1" style="color:var(--ink-muted);font-size:.7rem;text-transform:uppercase;letter-spacing:.05em">Jenis Kelamin</p>
                            <p class="fw-semibold mb-0" style="font-size:.9rem;color:var(--ink)">{{ $pegawai?->jenis_kelamin === 'P' ? 'Perempuan' : 'Laki-laki' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1" style="color:var(--ink-muted);font-size:.7rem;text-transform:uppercase;letter-spacing:.05em">Status Pegawai</p>
                            <span class="badge" style="background:var(--primary-light);color:var(--primary-dark);font-weight:600;font-size:.72rem;border-radius:20px;">{{ $statusPegawai }}</span>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1" style="color:var(--ink-muted);font-size:.7rem;text-transform:uppercase;letter-spacing:.05em">Username</p>
                            <p class="fw-semibold mb-0" style="font-size:.9rem;color:var(--ink)">{{ $user->username }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Informasi pribadi --}}
    <div class="card">
        <div class="card-body p-4">
            <p class="text-uppercase mb-3" style="font-size:.72rem;letter-spacing:.08em;font-weight:700;color:var(--bs-primary);">
                <i class="bi bi-card-list"></i> Informasi Pribadi
            </p>

            <div class="row g-4">
                <div class="col-md-4">
                    <p class="mb-1" style="color:var(--ink-muted);font-size:.7rem;text-transform:uppercase;letter-spacing:.05em">Tempat, Tanggal Lahir</p>
                    <p class="fw-semibold mb-0" style="font-size:.9rem;color:var(--ink)">
                        {{ $pegawai?->tempat_lahir ?: '-' }}{{ $pegawai?->tanggal_lahir ? ', '.$pegawai->tanggal_lahir->format('d F Y') : '' }}
                    </p>
                </div>
                <div class="col-md-4">
                    <p class="mb-1" style="color:var(--ink-muted);font-size:.7rem;text-transform:uppercase;letter-spacing:.05em">Pangkat / Golongan</p>
                    <p class="fw-semibold mb-0" style="font-size:.9rem;color:var(--ink)">{{ $pegawai?->pangkat_golongan ?: '-' }}</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-1" style="color:var(--ink-muted);font-size:.7rem;text-transform:uppercase;letter-spacing:.05em">No. HP</p>
                    <p class="fw-semibold mb-0" style="font-size:.9rem;color:var(--ink)">{{ $pegawai?->no_hp ?: '-' }}</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-1" style="color:var(--ink-muted);font-size:.7rem;text-transform:uppercase;letter-spacing:.05em">Email</p>
                    <p class="fw-semibold mb-0" style="font-size:.9rem;color:var(--ink)">{{ $pegawai?->email ?: '-' }}</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-1" style="color:var(--ink-muted);font-size:.7rem;text-transform:uppercase;letter-spacing:.05em">Gelar Depan</p>
                    <p class="fw-semibold mb-0" style="font-size:.9rem;color:var(--ink)">{{ $pegawai?->gelar_depan ?: '-' }}</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-1" style="color:var(--ink-muted);font-size:.7rem;text-transform:uppercase;letter-spacing:.05em">Gelar Belakang</p>
                    <p class="fw-semibold mb-0" style="font-size:.9rem;color:var(--ink)">{{ $pegawai?->gelar_belakang ?: '-' }}</p>
                </div>
            </div>
        </div>
    </div>

@endsection
