<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <style>
        /* Margin kertas A4: atas, kanan, bawah, kiri */
        @page {
            margin: 0;
        }

        /* Margin halaman (atas, kanan, bawah, kiri) lewat padding */
        .halaman {
            padding: 1.2cm 1.5cm 1.5cm 2cm;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            color: #000;
        }

        /* ================= KOP SURAT ================= */
        .kop {
            width: 100%;
            padding-bottom: 4px;
            /* garis ganda: tebal + tipis */
            border-bottom: 3px solid #000;
        }

        .kop-garis {
            border-bottom: 1px solid #000;
            margin: 2px 0 10px 0;
            height: 0;
            line-height: 0;
            font-size: 0;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-kiri,
        .logo-kanan {
            width: 95px;
            text-align: center;
            vertical-align: middle;
            padding: 0;
        }

        .logo-kiri img,
        .logo-kanan img {
            width: 88px;
            height: auto;
            max-height: 105px;
        }

        .kop-text {
            text-align: center;
            vertical-align: middle;
            padding: 0 2px;
        }

        .kop-text p {
            margin: 0;
            padding: 0;
        }

        .pemerintah {
            font-size: 14pt;
            font-weight: bold;
            line-height: 1.15;
            text-transform: uppercase;
        }

        .dinas {
            font-size: 14pt;
            font-weight: bold;
            line-height: 1.15;
            text-transform: uppercase;
        }

        .sekolah {
            white-space: nowrap;
            font-size: 10.5pt;
            font-weight: bold;
            line-height: 1.2;
            margin: 1px 0 3px 0 !important;
            text-transform: uppercase;
        }

        .alamat,
        .kontak,
        .identitas {
            font-size: 9pt;
            line-height: 1.25;
        }

        /* ================= NOMOR SURAT ================= */
        .nomor {
            width: 100%;
            margin: 0 0 2px 0;
            font-size: 12pt;
            line-height: 1.5;
        }

        .nomor-table {
            border-collapse: collapse;
            width: auto;
        }

        .nomor-table td {
            padding: 0;
            vertical-align: top;
        }

        .nomor-label {
            width: 70px;
        }

        .nomor-titik {
            width: 12px;
        }

        /* ================= ISI SURAT ================= */
        .isi {
            width: 100%;
            font-size: 12pt;
            line-height: 1.5;
            text-align: justify;
            word-wrap: break-word;
        }

        .isi p {
            margin: 8px 0;
            text-align: justify;
        }

        .isi table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            page-break-inside: avoid;
        }

        .isi th,
        .isi td {
            vertical-align: middle;
            padding: 4px 5px;
            white-space: nowrap;
        }

        .isi tr {
            page-break-inside: avoid;
        }

        .isi img {
            max-width: 100%;
        }

        /* Paksa font isi surat: Times New Roman 12pt */
        .isi,
        .isi p,
        .isi div,
        .isi span,
        .isi td,
        .isi th,
        .isi li {
            font-family: "Times New Roman", Times, serif !important;
            font-size: 12pt !important;
        }
    </style>
</head>

<body>
<div class="halaman">

@php
    $sekolah = \App\Models\Sekolah::first();

    $logoKiri = null;
    $logoKanan = null;

    // Logo sekolah
    if ($sekolah?->logo_path) {
        $pathLogoKiri = storage_path('app/public/' . $sekolah->logo_path);

        if (file_exists($pathLogoKiri)) {
            $mime = mime_content_type($pathLogoKiri);
            $logoKiri = 'data:' . $mime . ';base64,' .
                base64_encode(file_get_contents($pathLogoKiri));
        }
    }

    // Logo Tut Wuri Handayani
    $pathLogoKanan = public_path('images/logo-tut-wuri.png.jpg');

    if (file_exists($pathLogoKanan)) {
        $mime = mime_content_type($pathLogoKanan);
        $logoKanan = 'data:' . $mime . ';base64,' .
            base64_encode(file_get_contents($pathLogoKanan));
    }
@endphp

{{-- KOP SURAT --}}
<div class="kop">
    <table class="kop-table">
        <tr>
            <td class="logo-kiri" width="95">
                @if($logoKiri)
                    <img src="{{ $logoKiri }}" alt="Logo Sekolah">
                @endif
            </td>

            <td class="kop-text">
                <p class="pemerintah">PEMERINTAH PROVINSI RIAU</p>
                <p class="dinas">DINAS PENDIDIKAN</p>
                <p class="sekolah">SEKOLAH MENENGAH KEJURUAN (SMK) NEGERI 7 PEKANBARU</p>

                <p class="alamat">
                    {{ $sekolah->alamat ?? '-' }},
                    {{ $sekolah->kota ?? 'Pekanbaru' }}
                    {{ $sekolah->provinsi ?? 'Riau' }}
                    {{ $sekolah->kode_pos ?? '' }}
                </p>

                <p class="kontak">
                    E-mail: {{ $sekolah->email ?? '-' }}
                    &nbsp;|&nbsp;
                    Website: {{ $sekolah->website ?? '-' }}
                </p>

                <p class="identitas">
                    Telp: {{ $sekolah->telepon ?? '-' }}
                    &nbsp;|&nbsp;
                    NPSN: {{ $sekolah->npsn ?? '-' }}
                    &nbsp;|&nbsp;
                    NSS: {{ $sekolah->nss ?? '-' }}
                </p>
            </td>

            <td class="logo-kanan" width="95">
                @if($logoKanan)
                    <img src="{{ $logoKanan }}" alt="Tut Wuri Handayani">
                @endif
            </td>
        </tr>
    </table>
</div>
<div class="kop-garis">&nbsp;</div>

{{-- NOMOR SURAT --}}
@php
    $isiCetak = $suratKeluar->isi_surat_cetak;
    // true kalau template sudah menaruh nomor sendiri lewat {{nomor_surat}}
    $nomorSudahDiIsi = filled($suratKeluar->nomor_surat)
        && str_contains((string) $isiCetak, (string) $suratKeluar->nomor_surat);
@endphp

{{-- NOMOR SURAT (hanya dicetak di kiri atas kalau template tidak menaruhnya sendiri) --}}
@unless($nomorSudahDiIsi)
<div class="nomor">
    <table class="nomor-table">
        <tr>
            <td class="nomor-label">Nomor</td>
            <td class="nomor-titik">:</td>
            <td>{{ $suratKeluar->nomor_surat }}</td>
        </tr>
    </table>
</div>
@endunless

{{-- ISI SURAT DARI TEMPLATE --}}
<div class="isi">
    {!! $isiCetak !!}
</div>

</div>
</body>
</html>