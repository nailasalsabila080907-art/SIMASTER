@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
@php
    $pegawai = $user->pegawai;
    $fotoUrl = $pegawai?->foto_path ? asset('storage/'.$pegawai->foto_path) : null;
@endphp

<div class="max-w-5xl mx-auto space-y-6">
    <div>
        <p class="text-[11px] uppercase tracking-[.18em]" style="color:var(--ink-muted)">Pengaturan Akun</p>
        <h2 class="font-display text-3xl mt-1" style="color:var(--navy)">Edit Profil</h2>
        <p class="text-sm mt-1" style="color:var(--ink-muted)">Perbarui foto dan data pribadi Anda</p>
    </div>

    @if($errors->any())
        <div class="rounded-xl border px-4 py-3 text-sm" style="background:#fff3f1;border-color:#f0d1cb;color:var(--red)">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="font-display text-xl" style="color:var(--navy)">Foto Profil</h3>
                <p class="text-sm mt-1" style="color:var(--ink-muted)">Gunakan JPG, JPEG, PNG, atau WEBP dengan ukuran maksimal 2 MB.</p>
            </div>
            <div class="p-6 flex flex-col sm:flex-row items-center gap-6">
                <div class="w-28 h-28 rounded-full overflow-hidden border-4 border-white shadow-md flex items-center justify-center" style="background:#edf1f5">
                    @if($fotoUrl)
                        <img src="{{ $fotoUrl }}" alt="Foto profil" class="w-full h-full object-cover">
                    @else
                        <span class="font-display text-3xl" style="color:var(--navy)">{{ strtoupper(substr($pegawai?->nama_lengkap ?? $user->username,0,1)) }}</span>
                    @endif
                </div>
                <div class="flex-1 w-full">
                    <label class="block text-sm font-medium mb-2">Pilih foto baru</label>
                    <input type="file" name="foto" accept="image/jpeg,image/png,image/webp" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm bg-white">
                    <p class="text-xs mt-2" style="color:var(--ink-muted)">Foto akan digunakan pada halaman profil dan identitas pengguna.</p>
                </div>
            </div>
        </section>

        <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="font-display text-xl" style="color:var(--navy)">Data Identitas</h3>
                <p class="text-sm mt-1" style="color:var(--ink-muted)">Nama lengkap dan NIP dikunci untuk menjaga identitas kepegawaian.</p>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium mb-2">Nama Lengkap</label>
                    <input value="{{ $pegawai?->nama_lengkap }}" disabled class="w-full rounded-xl border border-gray-200 bg-gray-100 px-4 py-2.5 text-sm text-gray-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">NIP</label>
                    <input value="{{ $pegawai?->nip }}" disabled class="w-full rounded-xl border border-gray-200 bg-gray-100 px-4 py-2.5 text-sm text-gray-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Gelar Depan</label>
                    <input type="text" name="gelar_depan" value="{{ old('gelar_depan', $pegawai?->gelar_depan) }}" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Gelar Belakang</label>
                    <input type="text" name="gelar_belakang" value="{{ old('gelar_belakang', $pegawai?->gelar_belakang) }}" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm bg-white">
                        <option value="L" @selected(old('jenis_kelamin', $pegawai?->jenis_kelamin) === 'L')>Laki-laki</option>
                        <option value="P" @selected(old('jenis_kelamin', $pegawai?->jenis_kelamin) === 'P')>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $pegawai?->tempat_lahir) }}" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', optional($pegawai?->tanggal_lahir)->format('Y-m-d')) }}" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Pangkat / Golongan</label>
                    <input type="text" name="pangkat_golongan" value="{{ old('pangkat_golongan', $pegawai?->pangkat_golongan) }}" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">No. HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $pegawai?->no_hp) }}" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $pegawai?->email) }}" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm">
                </div>
            </div>
        </section>

        <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="font-display text-xl" style="color:var(--navy)">Akses Akun</h3>
                <p class="text-sm mt-1" style="color:var(--ink-muted)">Username dapat diperbarui. Role dan status akun dikelola oleh administrator.</p>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium mb-2">Username</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm">
                </div>
                <div></div>
                <div>
                    <label class="block text-sm font-medium mb-2">Password Baru</label>
                    <input type="password" name="password" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm" placeholder="Kosongkan jika tidak diganti">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm">
                </div>
            </div>
        </section>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('profil.index') }}" class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-medium bg-white hover:bg-gray-50">Batal</a>
            <button type="submit" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white hover:opacity-90" style="background:var(--navy)">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
