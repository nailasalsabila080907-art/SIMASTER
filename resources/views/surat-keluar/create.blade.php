@extends('layouts.app')
@section('title', 'Buat Surat Keluar')

@section('content')
<div class="max-w-2xl">

    {{-- Langkah 1: pilih kategori --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
        <label class="block text-sm font-medium mb-1.5">1. Pilih jenis surat</label>
        <select onchange="window.location='{{ route('surat-keluar.create') }}?kategori='+this.value" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">
            <option value="">-- Pilih kategori surat --</option>
            @foreach($kategoriList as $k)
                <option value="{{ $k->id_kategori }}" {{ (string) $kategoriTerpilih === (string) $k->id_kategori ? 'selected' : '' }}>
                    {{ $k->nama_kategori }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Langkah 2: pilih template --}}
    @if($kategoriTerpilih)
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
            <label class="block text-sm font-medium mb-1.5">2. Pilih template</label>
            @if($templateList->isEmpty())
                <p class="text-sm" style="color: var(--ink-muted);">Belum ada template aktif untuk kategori ini.</p>
            @else
                <select onchange="window.location='{{ route('surat-keluar.create') }}?kategori={{ $kategoriTerpilih }}&template='+this.value" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">
                    <option value="">-- Pilih template --</option>
                    @foreach($templateList as $t)
                        <option value="{{ $t->id_template }}" {{ $template && $template->id_template === $t->id_template ? 'selected' : '' }}>
                            {{ $t->nama_template }}
                        </option>
                    @endforeach
                </select>
            @endif
        </div>
    @endif

    {{-- Langkah 3: form dinamis --}}
    @if($template)
        <form method="POST" action="{{ route('surat-keluar.store') }}" class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            @csrf
            <input type="hidden" name="id_template" value="{{ $template->id_template }}">

            @if($errors->any())
                <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif

            <p class="text-sm font-medium" style="color: var(--navy);">3. Isi data surat: {{ $template->nama_template }}</p>

            <div>
                <label class="block text-sm font-medium mb-1.5">Perihal</label>
                <input type="text" name="perihal" value="{{ old('perihal') }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1.5">Tanggal Surat</label>
                    <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', now()->format('Y-m-d')) }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Sifat Surat</label>
                    <select name="sifat_surat" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm">
                        @foreach(['biasa','penting','segera','rahasia'] as $s)
                            <option value="{{ $s }}">{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr class="border-gray-100">

            @foreach($template->variabel as $var)
                <div>
                    <label class="block text-sm font-medium mb-1.5">{{ $var->label }}</label>
                    @if($var->tipe_input === 'textarea')
                        <textarea name="variabel_{{ $var->id_variabel }}" rows="3" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm" {{ $var->wajib ? 'required' : '' }}>{{ old('variabel_'.$var->id_variabel) }}</textarea>
                    @elseif($var->tipe_input === 'date')
                        <input type="date" name="variabel_{{ $var->id_variabel }}" value="{{ old('variabel_'.$var->id_variabel) }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm" {{ $var->wajib ? 'required' : '' }}>
                    @else
                        <input type="text" name="variabel_{{ $var->id_variabel }}" value="{{ old('variabel_'.$var->id_variabel) }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm" {{ $var->wajib ? 'required' : '' }}>
                    @endif
                </div>
            @endforeach

            <button type="submit" class="px-5 py-2.5 rounded-lg text-white text-sm font-medium" style="background: var(--navy);">Simpan sebagai Draft</button>
        </form>
    @endif
</div>
@endsection
