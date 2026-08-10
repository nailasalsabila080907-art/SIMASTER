@extends('layouts.app')
@section('title', 'Catat Surat Masuk')

@section('content')
<div class="max-w-xl">
    <form method="POST" action="{{ route('surat-masuk.store') }}" class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
        @csrf
        @if($errors->any())
            <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <div>
            <label class="block text-sm font-medium mb-1.5">Asal Instansi</label>
            <input type="text" name="asal_instansi" value="{{ old('asal_instansi') }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1.5">Nomor Surat Asal</label>
            <input type="text" name="nomor_surat_asal" value="{{ old('nomor_surat_asal') }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1.5">Perihal</label>
            <input type="text" name="perihal" value="{{ old('perihal') }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm" required>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1.5">Kategori</label>
                <select name="id_kategori" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm" required>
                    <option value="">-- Pilih --</option>
                    @foreach($kategoriList as $k)<option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Klasifikasi</label>
                <select name="id_klasifikasi" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm" required>
                    <option value="">-- Pilih --</option>
                    @foreach($klasifikasiList as $k)<option value="{{ $k->id_klasifikasi }}">{{ $k->kode_klasifikasi }} - {{ $k->nama_klasifikasi }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Tanggal Surat</label>
                <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat') }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Tanggal Diterima</label>
                <input type="date" name="tanggal_diterima" value="{{ old('tanggal_diterima', now()->format('Y-m-d')) }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm" required>
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium mb-1.5">Sifat Surat</label>
                <select name="sifat_surat" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">
                    @foreach(['biasa','penting','segera','rahasia'] as $s)<option value="{{ $s }}">{{ ucfirst($s) }}</option>@endforeach
                </select>
            </div>
        </div>

        <button type="submit" class="px-5 py-2.5 rounded-lg text-white text-sm font-medium" style="background: var(--navy);">Simpan</button>
    </form>
</div>
@endsection
