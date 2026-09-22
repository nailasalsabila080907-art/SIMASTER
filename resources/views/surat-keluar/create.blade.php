@extends('layouts.app')

@section('title', $suratKeluar?->exists ? 'Ubah Surat Keluar' : 'Buat Surat Keluar')

@section('content')

    {{-- Header --}}
    <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
        <div>
            <p class="text-muted mb-1" style="font-size:.82rem">
                Persuratan / Surat Keluar
            </p>

            <h2 class="mb-1" style="font-size:1.5rem">
                {{ $suratKeluar?->exists ? 'Ubah Surat Keluar' : 'Buat Surat Keluar' }}
            </h2>

            <p class="text-muted mb-0" style="font-size:.78rem">
                Pilih kategori surat, lalu lengkapi datanya.
            </p>
        </div>
    </div>


    {{-- Error --}}
    @if($errors->any())
        <div class="alert alert-danger rounded-3 d-flex gap-2 mb-4" style="font-size:.85rem">
            <i class="bi bi-exclamation-triangle mt-1"></i>

            <div>
                <p class="fw-semibold mb-1">
                    Periksa kembali isian berikut:
                </p>

                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif


    {{-- Layout --}}
    <div class="row g-3">
        <div class="col-xl-4 col-xxl-3">

            <div class="d-flex flex-column gap-3">
                <div class="card">

                    <div class="card-header d-flex align-items-center gap-3">

                        <span
                            class="d-inline-flex align-items-center justify-content-center rounded-3 text-white fw-bold flex-shrink-0"
                            style="
                                width:48px;
                                height:48px;
                                background:linear-gradient(135deg,#178754,#0EA5A4);
                                font-size:1.1rem;
                            ">
                            1
                        </span>

                        <h3 class="mb-0" style="font-size:1.05rem">
                            Pilih Jenis Surat
                        </h3>

                    </div>


                    <div class="card-body">

                        <label class="form-label" style="font-size:.85rem">
                            Kategori
                        </label>

                        <select
                            onchange="pilihKategori(this.value)"
                            class="form-select">

                            <option value="">
                                -- Pilih kategori --
                            </option>

                            @foreach($kategoriList as $k)

                                <option
                                    value="{{ $k->id_kategori }}"
                                    {{ (string) $kategoriTerpilih === (string) $k->id_kategori ? 'selected' : '' }}>

                                    {{ $k->nama_kategori }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- Informasi jika kategori sudah dipilih --}}
                @if($template)

                    <div class="card">

                        <div class="card-body">

                            <div class="d-flex align-items-start gap-3">

                                <div
                                    class="d-inline-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                                    style="
                                        width:40px;
                                        height:40px;
                                        background:var(--primary-light);
                                        color:var(--bs-primary);
                                    ">

                                    <i class="bi bi-file-earmark-text"></i>

                                </div>

                                <div>

                                    <p
                                        class="mb-1 text-muted"
                                        style="font-size:.72rem;text-transform:uppercase;letter-spacing:.04em;">
                                        Template otomatis
                                    </p>

                                    <p
                                        class="mb-0 fw-semibold"
                                        style="font-size:.85rem;color:var(--ink);">

                                        {{ $template->nama_template }}

                                    </p>

                                </div>

                            </div>

                        </div>
                    </div>

                @elseif($kategoriTerpilih)

                    <div class="alert alert-warning rounded-3 mb-0" style="font-size:.8rem">

                        <div class="d-flex gap-2">

                            <i class="bi bi-exclamation-triangle mt-1"></i>

                            <div>
                                Belum ada template aktif untuk kategori ini.
                                Silakan buat template dari menu
                                <strong>Master Data → Template Surat</strong>.
                            </div>

                        </div>

                    </div>

                @endif

            </div>

        </div>
        <div class="col-xl-8 col-xxl-9">

            @if($template)

                @php

                    $dataLama = $suratKeluar?->data_variabel ?? [];

                    $action = $suratKeluar?->exists
                        ? route('surat-keluar.update', $suratKeluar)
                        : route('surat-keluar.store');

                @endphp


                <form
                    method="POST"
                    action="{{ $action }}">

                    @csrf

                    @if($suratKeluar?->exists)
                        @method('PUT')
                    @endif

                    <input
                        type="hidden"
                        name="id_template"
                        value="{{ $template->id_template }}">

                    <div class="card mb-3">

                        <div
                            class="card-header d-flex align-items-center justify-content-between gap-3">

                            <div class="d-flex align-items-center gap-3">

                                <span
                                    class="d-inline-flex align-items-center justify-content-center rounded-3 text-white fw-bold flex-shrink-0"
                                    style="
                                        width:32px;
                                        height:32px;
                                        background:linear-gradient(135deg,#3E4652,#5B5D6B);
                                        font-size:.85rem;
                                    ">
                                    2
                                </span>

                                <div>

                                    <h3
                                        class="mb-1"
                                        style="font-size:1.05rem">
                                        Isi Data Surat
                                    </h3>

                                    <p
                                        class="text-muted mb-0"
                                        style="font-size:.78rem">

                                        {{ $template->nama_template }}

                                    </p>

                                </div>

                            </div>


                            @if($suratKeluar?->exists)

                                <span
                                    class="badge rounded-pill text-bg-warning"
                                    style="font-size:.72rem">

                                    Mode revisi

                                </span>

                            @endif

                        </div>


                        <div class="card-body">

                            <div class="row g-3">

                                {{-- Perihal --}}
                                <div class="col-md-6">

                                    <label
                                        class="form-label"
                                        style="font-size:.85rem">

                                        Perihal
                                        <span class="text-danger">*</span>

                                    </label>

                                    <input
                                        type="text"
                                        name="perihal"
                                        value="{{ old('perihal', $suratKeluar?->perihal) }}"
                                        class="form-control"
                                        required>

                                </div>


                                {{-- Tujuan --}}
                                <div class="col-md-6">

                                    <label
                                        class="form-label"
                                        style="font-size:.85rem">

                                        Tujuan

                                    </label>

                                    <input
                                        type="text"
                                        name="tujuan"
                                        value="{{ old('tujuan', $suratKeluar?->tujuan) }}"
                                        class="form-control"
                                        placeholder="Instansi / penerima surat">

                                </div>


                                {{-- Tanggal --}}
                                <div class="col-md-6">

                                    <label
                                        class="form-label"
                                        style="font-size:.85rem">

                                        Tanggal Surat
                                        <span class="text-danger">*</span>

                                    </label>

                                    <input
                                        type="date"
                                        name="tanggal_surat"
                                        value="{{ old('tanggal_surat', $suratKeluar?->tanggal_surat?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                                        class="form-control"
                                        required>

                                </div>


                                {{-- Sifat Surat --}}
                                <div class="col-md-6">

                                    <label
                                        class="form-label"
                                        style="font-size:.85rem">

                                        Sifat Surat

                                    </label>

                                    <select
                                        name="sifat_surat"
                                        class="form-select">

                                        @foreach(['biasa','penting','segera','rahasia'] as $s)

                                            <option
                                                value="{{ $s }}"
                                                {{ old('sifat_surat', $suratKeluar?->sifat_surat ?? 'biasa') === $s ? 'selected' : '' }}>

                                                {{ ucfirst($s) }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="card mb-3">

                        <div class="card-header">

                            <h3
                                class="mb-1"
                                style="font-size:1.05rem">

                                Data Sesuai Template

                            </h3>

                            <p
                                class="text-muted mb-0"
                                style="font-size:.78rem">

                                Field bertanda * wajib diisi.

                            </p>

                        </div>


                        <div class="card-body">

                            <div class="row g-3">

                                @foreach($template->variabel as $var)

                                    @php

                                        $nilai = old(
                                            'variabel_'.$var->id_variabel,
                                            $dataLama[$var->nama_variabel] ?? ''
                                        );

                                    @endphp


                                    <div class="col-md-6">

                                        <label
                                            class="form-label"
                                            style="font-size:.85rem">

                                            {{ $var->label }}

                                            @if($var->wajib)
                                                <span class="text-danger">*</span>
                                            @endif

                                        </label>


                                        {{-- Textarea --}}
                                        @if($var->tipe_input === 'textarea')

                                            <textarea
                                                name="variabel_{{ $var->id_variabel }}"
                                                rows="4"
                                                class="form-control"
                                                {{ $var->wajib ? 'required' : '' }}>{{ $nilai }}</textarea>


                                        {{-- Date --}}
                                        @elseif($var->tipe_input === 'date')

                                            <input
                                                type="date"
                                                name="variabel_{{ $var->id_variabel }}"
                                                value="{{ $nilai instanceof \Carbon\CarbonInterface ? $nilai->format('Y-m-d') : $nilai }}"
                                                class="form-control"
                                                {{ $var->wajib ? 'required' : '' }}>


                                        {{-- Number --}}
                                        @elseif($var->tipe_input === 'number')

                                            <input
                                                type="number"
                                                name="variabel_{{ $var->id_variabel }}"
                                                value="{{ $nilai }}"
                                                class="form-control"
                                                {{ $var->wajib ? 'required' : '' }}>


                                        {{-- Jenis Kelamin --}}
                                        @elseif($var->tipe_input === 'select' && $var->nama_variabel === 'jenis_kelamin')

                                            <select
                                                name="variabel_{{ $var->id_variabel }}"
                                                class="form-select"
                                                {{ $var->wajib ? 'required' : '' }}>

                                                <option value="">
                                                    -- Pilih --
                                                </option>

                                                <option
                                                    value="Laki-laki"
                                                    {{ $nilai === 'Laki-laki' ? 'selected' : '' }}>

                                                    Laki-laki

                                                </option>

                                                <option
                                                    value="Perempuan"
                                                    {{ $nilai === 'Perempuan' ? 'selected' : '' }}>

                                                    Perempuan

                                                </option>

                                            </select>


                                        {{-- Default --}}
                                        @else

                                            <input
                                                type="text"
                                                name="variabel_{{ $var->id_variabel }}"
                                                value="{{ $nilai }}"
                                                class="form-control"
                                                {{ $var->wajib ? 'required' : '' }}>

                                        @endif

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    </div>
                    <div class="d-flex flex-wrap justify-content-end gap-2">

                        <a
                            href="{{ route('surat-keluar.index') }}"
                            class="btn btn-light">

                            Batal

                        </a>


                        <button
                            type="submit"
                            class="btn d-inline-flex align-items-center gap-2 text-white"
                            style="
                                background:linear-gradient(135deg,#178754,#0EA5A4);
                                border:none;
                            ">

                            <i class="bi bi-save"></i>

                            {{ $suratKeluar?->exists ? 'Simpan Perubahan' : 'Simpan sebagai Draft' }}

                        </button>

                    </div>

                </form>


            @else

                {{-- Belum memilih kategori --}}
                <div class="card">

                    <div
                        class="card-body d-flex align-items-center justify-content-center text-center"
                        style="min-height:360px">

                        <div>

                            <span
                                class="d-inline-flex align-items-center justify-content-center rounded-3 text-white mx-auto mb-3"
                                style="
                                    width:56px;
                                    height:56px;
                                    background:linear-gradient(135deg,#178754,#0EA5A4);
                                    font-size:1.4rem;
                                ">

                                <i class="bi bi-envelope"></i>

                            </span>


                            <h3
                                class="mb-2"
                                style="font-size:1.15rem">

                                Mulai membuat surat

                            </h3>


                            <p
                                class="text-muted mb-0 mx-auto"
                                style="
                                    font-size:.85rem;
                                    max-width:380px;
                                ">

                                Pilih kategori surat di sebelah kiri.
                                Template akan dipilih otomatis dan
                                form pengisian akan muncul di sini.

                            </p>

                        </div>

                    </div>

                </div>

            @endif

        </div>

    </div>
    <script>

        function pilihKategori(id) {

            const url = new URL(
                '{{ route('surat-keluar.create') }}',
                window.location.origin
            );

            if (id) {
                url.searchParams.set('kategori', id);
            }

            window.location = url.toString();

        }

    </script>

@endsection