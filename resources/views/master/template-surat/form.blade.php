@extends('layouts.app')
@section('title', $template->exists ? 'Kelola Template' : 'Tambah Template')

@section('content')
<div class="w-full max-w-6xl">
    @if(session('sukses'))<div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">{{ session('sukses') }}</div>@endif

    <form method="POST" action="{{ $template->exists ? route('template-surat.update', $template) : route('template-surat.store') }}" class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
        @csrf
        @if($template->exists) @method('PUT') @endif

        @if($errors->any())
            <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <p class="text-sm font-medium" style="color: var(--navy);">Info Template</p>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1.5">Nama Template</label>
                <input type="text" name="nama_template" value="{{ old('nama_template', $template->nama_template) }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Kode Template</label>
                <input type="text" name="kode_template" value="{{ old('kode_template', $template->kode_template) }}" placeholder="mis. TPL-CUTI-01" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm" required>
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium mb-1.5">Kategori</label>
                <select name="id_kategori" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm" required>
                    <option value="">-- Pilih kategori --</option>
                    @foreach($kategoriList as $k)
                        <option value="{{ $k->id_kategori }}" {{ (string) old('id_kategori', $template->id_kategori) === (string) $k->id_kategori ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium mb-1.5">Format Nomor Surat</label>
                <input type="text" name="format_nomor" value="{{ old('format_nomor', $template->format_nomor ?: '420.5/SMKN-07/KP/{tahun}/{no_urut}') }}" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm font-mono" required>
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium mb-1.5">Isi Template (HTML, gunakan placeholder seperti nama_field)</label>
                <textarea name="isi_template" rows="10" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm font-mono">{{ old('isi_template', $template->isi_template) }}</textarea>
            </div>
            <div class="col-span-2 flex items-center gap-3">
                <input type="hidden" name="is_active" value="0">
                <input id="is_active" type="checkbox" name="is_active" value="1" class="rounded border-gray-300" {{ old('is_active', $template->exists ? $template->is_active : true) ? 'checked' : '' }}>
                <label for="is_active" class="text-sm">Template aktif dan dapat dipilih saat membuat surat</label>
            </div>
        </div>

        @if($template->exists)
            <hr class="border-gray-100">
            <p class="text-sm font-medium" style="color: var(--navy);">Field Dinamis yang Sudah Ada</p>
            <div class="space-y-2">
                @forelse($template->variabel as $v)
                    <div class="flex items-center justify-between text-sm bg-gray-50 rounded-lg px-3 py-2">
                        <span> {{ $v->label }}
                        <span class="text-xs font-mono" style="color: var(--ink-muted);"> &#123;&#123;{{ $v->nama_variabel }}&#125;&#125;</span>&middot; {{ $v->tipe_input }}</span>
                        <a href="{{ route('template-surat.variabel.hapus', $v) }}" onclick="event.preventDefault(); document.getElementById('hapus-var-{{ $v->id_variabel }}').submit();" class="text-xs text-red-600">Hapus</a>
                        <form id="hapus-var-{{ $v->id_variabel }}" method="POST" action="{{ route('template-surat.variabel.hapus', $v) }}" class="hidden">@csrf @method('DELETE')</form>
                    </div>
                @empty
                    <p class="text-sm" style="color: var(--ink-muted);">Belum ada field.</p>
                @endforelse
            </div>

            <hr class="border-gray-100">
            <p class="text-sm font-medium" style="color: var(--navy);">Tambah Field Baru</p>
            <div id="field-baru-container" class="space-y-3"></div>
            <button type="button" onclick="tambahBarisField()" class="text-xs font-medium" style="color: var(--navy);">+ Tambah Baris Field</button>
        @endif

        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-5 py-2.5 rounded-lg text-white text-sm font-medium" style="background: var(--navy);">Simpan</button>
            <a href="{{ route('template-surat.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm">Selesai</a>
        </div>
    </form>
</div>

<script>
function tambahBarisField() {
    const container = document.getElementById('field-baru-container');
    const div = document.createElement('div');
    div.className = 'grid grid-cols-3 gap-2';
    div.innerHTML = `
        <input type="text" name="variabel_baru_nama[]" placeholder="nama_field" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
        <input type="text" name="variabel_baru_label[]" placeholder="Label tampilan" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
        <select name="variabel_baru_tipe[]" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
            <option value="text">Text</option>
            <option value="textarea">Textarea</option>
            <option value="date">Tanggal</option>
            <option value="number">Angka</option>
            <option value="select">Pilihan</option>
        </select>
    `;
    container.appendChild(div);
}
</script>
@endsection
