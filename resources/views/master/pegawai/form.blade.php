@extends('layouts.app')
@section('title', $pegawai->exists ? 'Ubah Pegawai' : 'Tambah Pegawai')

@section('content')
<div class="max-w-xl">
    <form method="POST" action="{{ $pegawai->exists ? route('pegawai.update', $pegawai) : route('pegawai.store') }}" class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
        @csrf
        @if($pegawai->exists) @method('PUT') @endif

        @if($errors->any())
            <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <p class="text-sm font-medium" style="color: var(--navy);">Data Pegawai</p>

        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <label class="block text-sm font-medium mb-1.5">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $pegawai->nama_lengkap) }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Gelar Depan</label>
                <input type="text" name="gelar_depan" value="{{ old('gelar_depan', $pegawai->gelar_depan) }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Gelar Belakang</label>
                <input type="text" name="gelar_belakang" value="{{ old('gelar_belakang', $pegawai->gelar_belakang) }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">NIP</label>
                <input type="text" name="nip" value="{{ old('nip', $pegawai->nip) }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm" required>
                    <option value="L" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Jabatan</label>
                <select name="id_jabatan" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">
                    <option value="">-- Pilih jabatan --</option>
                    @foreach($jabatanList as $j)
                        <option value="{{ $j->id_jabatan }}" {{ (string) old('id_jabatan', $pegawai->id_jabatan) === (string) $j->id_jabatan ? 'selected' : '' }}>{{ $j->nama_jabatan }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Unit Kerja</label>
                <select name="id_unit" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">
                    <option value="">-- Pilih unit --</option>
                    @foreach($unitList as $u)
                        <option value="{{ $u->id_unit }}" {{ (string) old('id_unit', $pegawai->id_unit) === (string) $u->id_unit ? 'selected' : '' }}>{{ $u->nama_unit }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Pangkat/Golongan</label>
                <input type="text" name="pangkat_golongan" value="{{ old('pangkat_golongan', $pegawai->pangkat_golongan) }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">No. HP</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $pegawai->no_hp) }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">
            </div>
        </div>

        @unless($pegawai->exists)
            <hr class="border-gray-100">
            <p class="text-sm font-medium" style="color: var(--navy);">Akun Login (opsional, boleh dikosongkan)</p>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1.5">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Password</label>
                    <input type="password" name="password" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium mb-1.5">Role</label>
                    <select name="role" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">
                        @foreach(['admin_tu' => 'Admin TU', 'kepala_sekolah' => 'Kepala Sekolah', 'staff' => 'Staff', 'guru' => 'Guru', 'super_admin' => 'Super Admin'] as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endunless

        <div class="flex gap-3">
            <button type="submit" class="px-5 py-2.5 rounded-lg text-white text-sm font-medium" style="background: var(--navy);">Simpan</button>
            <a href="{{ route('pegawai.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm">Batal</a>
        </div>
    </form>
</div>
@endsection
