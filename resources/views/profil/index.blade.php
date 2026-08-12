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

<div class="max-w-6xl mx-auto space-y-6">
    @if(session('sukses'))
        <div class="rounded-xl border px-4 py-3 text-sm" style="background:#eef7f1;border-color:#cfe6d7;color:var(--green)">{{ session('sukses') }}</div>
    @endif

    @if(session('gagal'))
        <div class="rounded-xl border px-4 py-3 text-sm" style="background:#fff3f1;border-color:#f0d1cb;color:var(--red)">{{ session('gagal') }}</div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border px-4 py-3 text-sm" style="background:#fff3f1;border-color:#f0d1cb;color:var(--red)">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div>
        <p class="text-[11px] uppercase tracking-[.18em]" style="color:var(--ink-muted)">Akun Pengguna</p>
        <h2 class="font-display text-3xl mt-1" style="color:var(--navy)">Profil Saya</h2>
        <p class="text-sm mt-1" style="color:var(--ink-muted)">Informasi akun dan data kepegawaian Anda</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[340px_1fr] gap-6">
        <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="h-24" style="background:var(--navy-deep)"></div>
            <div class="px-6 pb-6">
                <div class="-mt-12 flex justify-center">
                    <div class="w-24 h-24 rounded-full border-4 border-white shadow-md overflow-hidden flex items-center justify-center" style="background:#edf1f5">
                        @if($fotoUrl)
                            <img src="{{ $fotoUrl }}" alt="Foto {{ $nama }}" class="w-full h-full object-cover">
                        @else
                            <span class="font-display text-2xl" style="color:var(--navy)">{{ strtoupper(substr($nama,0,1)) }}</span>
                        @endif
                    </div>
                </div>
                <div class="text-center mt-4">
                    <h3 class="font-display text-xl" style="color:var(--navy)">{{ $nama }}</h3>
                    <p class="text-sm mt-1" style="color:var(--ink-muted)">{{ $jabatan }}</p>
                    <span class="inline-flex items-center gap-2 mt-4 px-3 py-1.5 rounded-full text-xs font-medium" style="background:#edf6f0;color:var(--green)">
                        <span class="w-1.5 h-1.5 rounded-full" style="background:var(--green)"></span>{{ $statusAkun }}
                    </span>
                </div>
                <div class="mt-6 pt-5 border-t border-gray-100 space-y-3 text-sm">
                    <div class="flex justify-between gap-4"><span style="color:var(--ink-muted)">Username</span><span class="font-medium">{{ $user->username }}</span></div>
                    <div class="flex justify-between gap-4"><span style="color:var(--ink-muted)">Role</span><span class="font-medium">{{ ucwords(str_replace('_',' ', $user->role)) }}</span></div>
                </div>
                <a href="{{ route('profil.edit') }}" class="mt-6 w-full inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold text-white transition hover:opacity-90" style="background:var(--navy)">Edit Profil</a>
            </div>
        </section>

        <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between gap-4">
                <div>
                    <h3 class="font-display text-xl" style="color:var(--navy)">Data Kepegawaian</h3>
                    <p class="text-sm mt-1" style="color:var(--ink-muted)">Informasi utama yang terdaftar pada sistem</p>
                </div>
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background:#fbf5e7;color:var(--gold)">♙</div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-6">
                <div><p class="text-[11px] uppercase tracking-wider" style="color:var(--ink-muted)">Nama Lengkap</p><p class="font-semibold mt-1" style="color:var(--navy)">{{ $nama }}</p></div>
                <div><p class="text-[11px] uppercase tracking-wider" style="color:var(--ink-muted)">NIP</p><p class="font-semibold mt-1" style="color:var(--navy)">{{ $pegawai?->nip ?? '-' }}</p></div>
                <div><p class="text-[11px] uppercase tracking-wider" style="color:var(--ink-muted)">Jabatan</p><p class="font-semibold mt-1" style="color:var(--navy)">{{ $jabatan }}</p></div>
                <div><p class="text-[11px] uppercase tracking-wider" style="color:var(--ink-muted)">Unit Kerja</p><p class="font-semibold mt-1" style="color:var(--navy)">{{ $unit }}</p></div>
                <div><p class="text-[11px] uppercase tracking-wider" style="color:var(--ink-muted)">Jurusan</p><p class="font-semibold mt-1" style="color:var(--navy)">{{ $jurusan }}</p></div>
                <div><p class="text-[11px] uppercase tracking-wider" style="color:var(--ink-muted)">Jenis Kelamin</p><p class="font-semibold mt-1" style="color:var(--navy)">{{ $pegawai?->jenis_kelamin === 'P' ? 'Perempuan' : 'Laki-laki' }}</p></div>
                <div><p class="text-[11px] uppercase tracking-wider" style="color:var(--ink-muted)">Status Pegawai</p><span class="inline-flex mt-1 px-2.5 py-1 rounded-md text-xs font-medium" style="background:#edf6f0;color:var(--green)">{{ $statusPegawai }}</span></div>
                <div><p class="text-[11px] uppercase tracking-wider" style="color:var(--ink-muted)">Username</p><p class="font-semibold mt-1" style="color:var(--navy)">{{ $user->username }}</p></div>
            </div>
        </section>
    </div>

    <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <h3 class="font-display text-xl" style="color:var(--navy)">Informasi Pribadi</h3>
            <p class="text-sm mt-1" style="color:var(--ink-muted)">Data tambahan yang dapat Anda perbarui melalui menu edit profil</p>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div><p class="text-[11px] uppercase tracking-wider" style="color:var(--ink-muted)">Tempat, Tanggal Lahir</p><p class="font-medium mt-1">{{ $pegawai?->tempat_lahir ?: '-' }}{{ $pegawai?->tanggal_lahir ? ', '.$pegawai->tanggal_lahir->format('d F Y') : '' }}</p></div>
            <div><p class="text-[11px] uppercase tracking-wider" style="color:var(--ink-muted)">Pangkat / Golongan</p><p class="font-medium mt-1">{{ $pegawai?->pangkat_golongan ?: '-' }}</p></div>
            <div><p class="text-[11px] uppercase tracking-wider" style="color:var(--ink-muted)">No. HP</p><p class="font-medium mt-1">{{ $pegawai?->no_hp ?: '-' }}</p></div>
            <div><p class="text-[11px] uppercase tracking-wider" style="color:var(--ink-muted)">Email</p><p class="font-medium mt-1">{{ $pegawai?->email ?: '-' }}</p></div>
            <div><p class="text-[11px] uppercase tracking-wider" style="color:var(--ink-muted)">Gelar Depan</p><p class="font-medium mt-1">{{ $pegawai?->gelar_depan ?: '-' }}</p></div>
            <div><p class="text-[11px] uppercase tracking-wider" style="color:var(--ink-muted)">Gelar Belakang</p><p class="font-medium mt-1">{{ $pegawai?->gelar_belakang ?: '-' }}</p></div>
        </div>
    </section>

    <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mt-6">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between gap-4">
        <div>
            <h3 class="font-display text-xl" style="color:var(--navy)">Keamanan Akun</h3>
            <p class="text-sm mt-1" style="color:var(--ink-muted)">
                Kelola username dan password akun SIMASTER Anda
            </p>
        </div>

        <div class="w-11 h-11 rounded-xl flex items-center justify-center"
             style="background:#fbf5e7;color:var(--gold)">
            🔐
        </div>
    </div>

    @if(session('success'))
        <div class="mx-6 mt-5 rounded-xl px-4 py-3 text-sm"
             style="background:#edf7f1;color:#2f6b4f">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mx-6 mt-5 rounded-xl px-4 py-3 text-sm"
             style="background:#fff1ef;color:#b0432e">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('profil.keamanan.update') }}" class="p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-2"> Username</label>
                <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200" required>
                <p class="text-xs mt-2" style="color:var(--ink-muted)">Username digunakan untuk masuk ke SIMASTER.</p>

                @error('username')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div></div>

            <div class="md:col-span-2">
                <div class="border-t border-gray-100 pt-6">
                    <h4 class="font-semibold text-sm" style="color:var(--navy)"> Ganti Password</h4>

                    <p class="text-xs mt-1" style="color:var(--ink-muted)"> Kosongkan bagian password jika Anda tidak ingin menggantinya.</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2"> Password Lama</label>
                <input type="password" name="password_lama" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200" autocomplete="current-password">

                @error('password_lama')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div></div>

            <div>
                <label class="block text-sm font-medium mb-2"> Password Baru </label>

                <input type="password" name="password_baru" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200" autocomplete="new-password">

                @error('password_baru')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2"> Konfirmasi Password Baru</label>
                <input type="password" name="password_baru_confirmation" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200" autocomplete="new-password">
            </div>
        </div>

        <div class="mt-6 pt-5 border-t border-gray-100 flex justify-end">
            <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition hover:opacity-90" style="background:var(--navy)">
                Simpan Perubahan
            </button>
        </div>
    </form>
</section>
</div>
@endsection
