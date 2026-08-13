<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <style>
        @page {
            margin: 25px 45px 40px 45px;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            color: #000;
            margin: 0;
        }

        .kop {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 22px;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-kiri {
            width: 75px;
            text-align: center;
            vertical-align: middle;
        }

        .logo-kiri img {
            width: 68px;
            height: 100px;
            object-fit: contain;
        }

        .logo-kanan {
            width: 75px;
            text-align: center;
            vertical-align: middle;
        }

        .logo-kanan img {
            width: 100px;
            height: 110px;
            object-fit: contain;
        }

        .kop-text {
            text-align: center;
            vertical-align: middle;
        }

        .kop-text .pemerintah {
            font-size: 15pt;
            font-weight: bold;
            margin: 0;
        }

        .kop-text .dinas {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
        }

        .kop-text .sekolah {
            font-size: 12pt;
            font-weight: bold;
            margin: 2px 0;
        }

        .kop-text .alamat {
            font-size: 9pt;
            margin: 3px 0 0 0;
        }

        .kop-text .kontak {
            font-size: 9pt;
            margin: 2px 0;
        }

        .kop-text .identitas {
            font-size: 9pt;
            margin: 2px 0 0 0;
        }

        .nomor {
            text-align: center;
            margin-top: 25px;
            margin-bottom: 25px;
        }

        .isi {
            font-size: 12pt;
            line-height: 1.5;
        }

        .isi p {
            margin: 8px 0;
            text-align: justify;
        }
    </style>
</head>

<body>

@php
    $logoKiri = null;
    $logoKanan = null;

    if ($sekolah?->logo_path) {
        $path = storage_path('app/public/' . $sekolah->logo_path);

        if (file_exists($path)) {
            $mime = mime_content_type($path);
            $logoKiri = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
        }
    }

    $logoKananPath = public_path('images/logo-tut-wuri.png.jpg');

    if (file_exists($logoKananPath)) {
        $mime = mime_content_type($logoKananPath);
        $logoKanan = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($logoKananPath));
    }
@endphp

<div class="kop">
    <table class="kop-table">
        <tr>

            <td class="logo-kiri">
                @if($logoKiri)
                    <img src="{{ $logoKiri }}" alt="Logo Sekolah">
                @endif
            </td>

            <td class="kop-text">

                <p class="pemerintah">
                    PEMERINTAH PROVINSI RIAU
                </p>

                <p class="dinas">
                    DINAS PENDIDIKAN
                </p>

                <p class="sekolah">
                SEKOLAH MENENGAH KEJURUAN (SMK) NEGERI 7 PEKANBARU
                </p>

                <p class="alamat">
                    {{ $sekolah->alamat ?? '-' }}
                    {{ $sekolah->kota ?? 'Pekanbaru' }}
                    {{ $sekolah->provinsi ?? 'Riau' }}
                    {{ $sekolah->kode_pos ?? '' }}
                </p>

                <p class="kontak">
                    E-mail: {{ $sekolah->email ?? '-' }}
                    &nbsp;&nbsp;
                    Website: {{ $sekolah->website ?? '-' }}
                    &nbsp;&nbsp;
                    Telp: {{ $sekolah->telepon ?? '-' }}
                </p>

                <p class="identitas">
                    NPSN: {{ $sekolah->npsn ?? '10496502' }}
                    &nbsp;&nbsp;
                    NSS: {{ $sekolah->nss ?? '16120632160' }}
                </p>

            </td>

            <td class="logo-kanan">
                @if($logoKanan)
                    <img src="{{ $logoKanan }}" alt="Tut Wuri Handayani">
                @endif
            </td>

        </tr>
    </table>
</div>

<div class="nomor">
    <div>
        Nomor: {{ $suratKeluar->nomor_surat }}
    </div>

    <div>
        Perihal: {{ $suratKeluar->perihal }}
    </div>
</div>

<div class="isi">
    {!! $suratKeluar->isi_surat !!}
</div>

</body>
</html>