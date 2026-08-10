<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12pt; color: #000; }
        .kop { display: table; width: 100%; border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 20px; }
        .kop-logo { display: table-cell; width: 70px; vertical-align: middle; }
        .kop-logo img { width: 60px; height: 60px; object-fit: contain; }
        .kop-text { display: table-cell; text-align: center; vertical-align: middle; }
        .kop-text h1 { font-size: 14pt; margin: 0; text-transform: uppercase; }
        .kop-text p { font-size: 10pt; margin: 2px 0; }
        .nomor { text-align: center; margin-bottom: 20px; text-decoration: underline; }
        .isi p { margin: 8px 0; text-align: justify; }
    </style>
</head>
<body>
    @php $sekolah = \App\Models\Sekolah::first(); @endphp
    <div class="kop">
        @if($sekolah?->logo_path)
            <div class="kop-logo">
                <img src="{{ storage_path('app/public/'.$sekolah->logo_path) }}">
            </div>
        @endif
        <div class="kop-text">
            <h1>Pemerintah Provinsi Riau</h1>
            <h1>Dinas Pendidikan</h1>
            <h1>{{ $sekolah->nama_sekolah ?? 'SMK Negeri 7 Pekanbaru' }}</h1>
            <p>{{ $sekolah->alamat ?? '[Alamat Sekolah]' }} &middot; {{ $sekolah->kota ?? 'Pekanbaru' }}, {{ $sekolah->provinsi ?? 'Riau' }} &middot; Telp: {{ $sekolah->telepon ?? '-' }} &middot; Email: {{ $sekolah->email ?? '-' }}</p>
        </div>
    </div>

    <p class="nomor">
        Nomor: {{ $suratKeluar->nomor_surat }}<br>
        Perihal: {{ $suratKeluar->perihal }}
    </p>

    <div class="isi">
        {!! $suratKeluar->isi_surat !!}
    </div>
</body>
</html>
