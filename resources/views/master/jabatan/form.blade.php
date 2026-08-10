@extends('layouts.app')
@section('title', $jabatan->exists ? 'Ubah Jabatan' : 'Tambah Jabatan')

@section('content')
<div class="max-w-lg">
    <form method="POST" action="{{ $jabatan->exists ? route('jabatan.update', $jabatan) : route('jabatan.store') }}" class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
        @csrf
        @if($jabatan->exists) @method('PUT') @endif

        @if($errors->any())
            <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <div>
            <label class="block text-sm font-medium mb-1.5">Nama Jabatan</label>
            <input type="text" name="nama_jabatan" value="{{ old('nama_jabatan', $jabatan->nama_jabatan) }}"
                class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm" placeholder="mis. Kepala Sekolah" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1.5">Level (untuk urutan approval)</label>
            <input type="number" name="level_jabatan" value="{{ old('level_jabatan', $jabatan->level_jabatan) }}"
                class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm" placeholder="mis. 1, 2, 3 - makin besar makin tinggi" required min="1">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1.5">Keterangan (opsional)</label>
            <textarea name="keterangan" rows="3" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">{{ old('keterangan', $jabatan->keterangan) }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-5 py-2.5 rounded-lg text-white text-sm font-medium" style="background: var(--navy);">Simpan</button>
            <a href="{{ route('jabatan.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm">Batal</a>
        </div>
    </form>
</div>
@endsection
