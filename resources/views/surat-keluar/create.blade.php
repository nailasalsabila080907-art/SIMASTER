@extends('layouts.app')
@section('title', $suratKeluar?->exists ? 'Ubah Surat Keluar' : 'Buat Surat Keluar')

@section('content')
<div class="w-full max-w-6xl">
    @if($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <div class="grid xl:grid-cols-[320px_minmax(0,1fr)] gap-5 items-start">
        <div class="space-y-5">
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <p class="text-xs uppercase tracking-wider font-semibold" style="color: var(--gold);">Langkah 1</p>
                <h2 class="mt-1 font-display text-lg" style="color: var(--navy);">Pilih jenis surat</h2>
                <select onchange="pilihKategori(this.value)" class="mt-4 w-full rounded-xl border border-gray-300 px-3.5 py-3 text-sm">
                    <option value="">-- Pilih kategori --</option>
                    @foreach($kategoriList as $k)
                        <option value="{{ $k->id_kategori }}" {{ (string) $kategoriTerpilih === (string) $k->id_kategori ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if($kategoriTerpilih)
                <div class="bg-white rounded-2xl border border-gray-200 p-5">
                    <p class="text-xs uppercase tracking-wider font-semibold" style="color: var(--gold);">Langkah 2</p>
                    <h2 class="mt-1 font-display text-lg" style="color: var(--navy);">Pilih template</h2>
                    @if($templateList->isEmpty())
                        <div class="mt-4 rounded-xl bg-amber-50 border border-amber-200 px-4 py-3 text-sm text-amber-800">
                            Belum ada template aktif untuk kategori ini. Buat template dari menu Master Data → Template Surat.
                        </div>
                    @else
                        <select onchange="pilihTemplate(this.value)" class="mt-4 w-full rounded-xl border border-gray-300 px-3.5 py-3 text-sm">
                            <option value="">-- Pilih template --</option>
                            @foreach($templateList as $t)
                                <option value="{{ $t->id_template }}" {{ $template && $template->id_template === $t->id_template ? 'selected' : '' }}>{{ $t->nama_template }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
            @endif
        </div>

        @if($template)
            @php
                $dataLama = $suratKeluar?->data_variabel ?? [];
                $action = $suratKeluar?->exists ? route('surat-keluar.update', $suratKeluar) : route('surat-keluar.store');
            @endphp
            <form method="POST" action="{{ $action }}" class="bg-white rounded-2xl border border-gray-200 p-6 lg:p-8 space-y-6">
                @csrf
                @if($suratKeluar?->exists) @method('PUT') @endif
                <input type="hidden" name="id_template" value="{{ $template->id_template }}">

                <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-5">
                    <div>
                        <p class="text-xs uppercase tracking-wider font-semibold" style="color: var(--gold);">Langkah 3</p>
                        <h2 class="mt-1 font-display text-xl" style="color: var(--navy);">Isi data surat</h2>
                        <p class="mt-1 text-sm" style="color: var(--ink-muted);">{{ $template->nama_template }}</p>
                    </div>
                    @if($suratKeluar?->exists)
                        <span class="text-xs px-2.5 py-1 rounded-full bg-amber-50 text-amber-700">Mode revisi</span>
                    @endif
                </div>

                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Perihal <span class="text-red-500">*</span></label>
                        <input type="text" name="perihal" value="{{ old('perihal', $suratKeluar?->perihal) }}" class="w-full rounded-xl border border-gray-300 px-3.5 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Tujuan</label>
                        <input type="text" name="tujuan" value="{{ old('tujuan', $suratKeluar?->tujuan) }}" class="w-full rounded-xl border border-gray-300 px-3.5 py-3 text-sm" placeholder="Instansi / penerima surat">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Tanggal Surat <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', $suratKeluar?->tanggal_surat?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" class="w-full rounded-xl border border-gray-300 px-3.5 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Sifat Surat</label>
                        <select name="sifat_surat" class="w-full rounded-xl border border-gray-300 px-3.5 py-3 text-sm">
                            @foreach(['biasa','penting','segera','rahasia'] as $s)
                                <option value="{{ $s }}" {{ old('sifat_surat', $suratKeluar?->sifat_surat ?? 'biasa') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6 space-y-5">
                    <div>
                        <h3 class="font-medium" style="color: var(--navy);">Data sesuai template</h3>
                        <p class="text-xs mt-1" style="color: var(--ink-muted);">Field bertanda * wajib diisi.</p>
                    </div>

                    @foreach($template->variabel as $var)
                        @php
                            $nilai = old('variabel_'.$var->id_variabel, $dataLama[$var->nama_variabel] ?? '');
                        @endphp
                        <div>
                            <label class="block text-sm font-medium mb-1.5">{{ $var->label }} @if($var->wajib)<span class="text-red-500">*</span>@endif</label>
                            @if($var->tipe_input === 'textarea')
                                <textarea name="variabel_{{ $var->id_variabel }}" rows="4" class="w-full rounded-xl border border-gray-300 px-3.5 py-3 text-sm" {{ $var->wajib ? 'required' : '' }}>{{ $nilai }}</textarea>
                            @elseif($var->tipe_input === 'date')
                                <input type="date" name="variabel_{{ $var->id_variabel }}" value="{{ $nilai instanceof \Carbon\CarbonInterface ? $nilai->format('Y-m-d') : $nilai }}" class="w-full rounded-xl border border-gray-300 px-3.5 py-3 text-sm" {{ $var->wajib ? 'required' : '' }}>
                            @elseif($var->tipe_input === 'number')
                                <input type="number" name="variabel_{{ $var->id_variabel }}" value="{{ $nilai }}" class="w-full rounded-xl border border-gray-300 px-3.5 py-3 text-sm" {{ $var->wajib ? 'required' : '' }}>
                            @elseif($var->tipe_input === 'select' && $var->nama_variabel === 'jenis_kelamin')
                                <select name="variabel_{{ $var->id_variabel }}" class="w-full rounded-xl border border-gray-300 px-3.5 py-3 text-sm" {{ $var->wajib ? 'required' : '' }}>
                                    <option value="">-- Pilih --</option>
                                    <option value="Laki-laki" {{ $nilai === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ $nilai === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            @else
                                <input type="text" name="variabel_{{ $var->id_variabel }}" value="{{ $nilai }}" class="w-full rounded-xl border border-gray-300 px-3.5 py-3 text-sm" {{ $var->wajib ? 'required' : '' }}>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-100 pt-6">
                    <a href="{{ route('surat-keluar.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-sm">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-white text-sm font-medium" style="background: var(--navy);">
                        {{ $suratKeluar?->exists ? 'Simpan Perubahan' : 'Simpan sebagai Draft' }}
                    </button>
                </div>
            </form>
        @else
            <div class="bg-white rounded-2xl border border-dashed border-gray-300 p-10 min-h-[360px] flex items-center justify-center text-center">
                <div>
                    <div class="mx-auto w-14 h-14 rounded-2xl flex items-center justify-center text-xl" style="background: #EAF0F6; color: var(--navy);">✉</div>
                    <h2 class="mt-4 font-display text-xl" style="color: var(--navy);">Mulai membuat surat</h2>
                    <p class="mt-2 text-sm max-w-md" style="color: var(--ink-muted);">Pilih kategori dan template di sebelah kiri. Form akan muncul otomatis sesuai variabel template.</p>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function pilihKategori(id) {
    const url = new URL('{{ route('surat-keluar.create') }}', window.location.origin);
    if (id) url.searchParams.set('kategori', id);
    window.location = url.toString();
}
function pilihTemplate(id) {
    const url = new URL('{{ route('surat-keluar.create') }}', window.location.origin);
    const kategori = document.querySelector('select[onchange="pilihKategori(this.value)"]')?.value;
    if (kategori) url.searchParams.set('kategori', kategori);
    if (id) url.searchParams.set('template', id);
    window.location = url.toString();
}
</script>
@endsection
