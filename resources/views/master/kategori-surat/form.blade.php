@extends('layouts.app')
@section('title', $kategori->exists ? 'Ubah Kategori' : 'Tambah Kategori')

@section('content')
<div class="max-w-lg">
    <form method="POST" action="{{ $kategori->exists ? route('kategori-surat.update', $kategori) : route('kategori-surat.store') }}" class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
        @csrf
        @if($kategori->exists) @method('PUT') @endif

        @if($errors->any())
            <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <div>
            <label class="block text-sm font-medium mb-1.5">Nama Kategori</label>
            <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1.5">Jenis</label>
            <select name="jenis" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm" required>
                @foreach(['keluar' => 'Surat Keluar', 'masuk' => 'Surat Masuk', 'umum' => 'Umum'] as $val => $label)
                    <option value="{{ $val }}" {{ old('jenis', $kategori->jenis) === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1.5">Keterangan</label>
            <textarea name="keterangan" rows="3" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">{{ old('keterangan', $kategori->keterangan) }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-5 py-2.5 rounded-lg text-white text-sm font-medium" style="background: var(--navy);">Simpan</button>
            <a href="{{ route('kategori-surat.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm">Batal</a>
        </div>
    </form>
</div>
@endsection
