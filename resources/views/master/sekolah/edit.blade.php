@extends('layouts.app')
@section('title', 'Profil Sekolah')

@section('content')
<div class="max-w-xl">
    @if(session('sukses'))<div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">{{ session('sukses') }}</div>@endif

    <form method="POST" action="{{ route('sekolah.update') }}" enctype="multipart/form-data" class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
        @csrf @method('PUT')

        @if($errors->any())
            <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <div class="flex items-center gap-4">
            @if($sekolah->logo_path)
                <img src="{{ asset('storage/'.$sekolah->logo_path) }}" class="w-16 h-16 rounded-lg object-contain border border-gray-200 p-1">
            @else
                <div class="w-16 h-16 rounded-lg border border-dashed border-gray-300 flex items-center justify-center text-xs text-center" style="color: var(--ink-muted);">Belum ada logo</div>
            @endif
            <div class="flex-1">
                <label class="block text-sm font-medium mb-1.5">Logo Sekolah</label>
                <input type="file" name="logo" accept="image/*" class="w-full text-sm">
                <p class="text-xs mt-1" style="color: var(--ink-muted);">Dipakai di kop surat PDF. Format PNG/JPG, latar transparan lebih bagus.</p>
            </div>
        </div>

        <hr class="border-gray-100">

        <div>
            <label class="block text-sm font-medium mb-1.5">Nama Sekolah</label>
            <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah', $sekolah->nama_sekolah) }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Alamat</label>
            <textarea name="alamat" rows="2" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">{{ old('alamat', $sekolah->alamat) }}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1.5">Kota</label>
                <input type="text" name="kota" value="{{ old('kota', $sekolah->kota) }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Provinsi</label>
                <input type="text" name="provinsi" value="{{ old('provinsi', $sekolah->provinsi) }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Telepon</label>
                <input type="text" name="telepon" value="{{ old('telepon', $sekolah->telepon) }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', $sekolah->email) }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">
            </div>
        </div>

        <hr class="border-gray-100">
        <div>
            <label class="block text-sm font-medium mb-1.5">Nama Kepala Sekolah</label>
            <input type="text" name="nama_kepala_sekolah" value="{{ old('nama_kepala_sekolah', $sekolah->nama_kepala_sekolah) }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">NIP Kepala Sekolah</label>
            <input type="text" name="nip_kepala_sekolah" value="{{ old('nip_kepala_sekolah', $sekolah->nip_kepala_sekolah) }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">
        </div>

        <button type="submit" class="px-5 py-2.5 rounded-lg text-white text-sm font-medium" style="background: var(--navy);">Simpan</button>
    </form>
</div>
@endsection
