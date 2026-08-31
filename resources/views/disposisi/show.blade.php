@extends('layouts.app')

@section('title', 'Lembar Disposisi')

@section('content')

<style>
    .lembar-disposisi {
        max-width: 900px;
        margin: 0 auto;
        background: #fff;
        padding: 35px 40px;
        border: 1px solid #ddd;
    }

    .kop-surat img {
        width: 100%;
        max-height: 180px;
        object-fit: contain;
        display: block;
    }

    .judul-disposisi {
        text-align: center;
        font-weight: 700;
        font-size: 18px;
        margin: 18px 0 20px;
        text-decoration: underline;
    }

    .info-table,
    .disposisi-table {
        width: 100%;
        border-collapse: collapse;
    }

    .info-table td,
    .disposisi-table td,
    .disposisi-table th {
        border: 1px solid #222;
        padding: 9px 10px;
        vertical-align: top;
    }

    .label {
        width: 180px;
        font-weight: 600;
    }

    .isi-disposisi {
        min-height: 150px;
        white-space: pre-line;
    }

    .tombol-atas {
        max-width: 900px;
        margin: 0 auto 15px;
    }

    @media print {
        body {
            background: #fff !important;
        }

        .sidebar,
        .topbar,
        .tombol-atas,
        nav,
        header {
            display: none !important;
        }

        .lembar-disposisi {
            max-width: none;
            border: none;
            margin: 0;
            padding: 0;
        }

        @page {
            size: A4;
            margin: 15mm;
        }
    }
</style>

<div class="tombol-atas d-flex justify-content-between align-items-center">
    <a href="{{ url()->previous() }}"
       class="btn btn-light">
        <i class="bi bi-arrow-left"></i>
        Kembali
    </a>

    <button type="button"
            onclick="window.print()"
            class="btn btn-success">
        <i class="bi bi-printer"></i>
        Cetak Lembar Disposisi
    </button>
</div>

<div class="lembar-disposisi">

    {{-- KOP SEKOLAH --}}
    @if($sekolah?->kop_surat_path)

        <div class="kop-surat">
            <img src="{{ asset('storage/'.$sekolah->kop_surat_path) }}"
                 alt="Kop Surat Sekolah">
        </div>

    @else

        <div class="text-center mb-4">
            <h3 class="mb-1">{{ $sekolah->nama_sekolah ?? 'SMK Negeri 7' }}</h3>
            <p class="mb-0">
                {{ $sekolah->alamat ?? '' }}
            </p>
        </div>

    @endif


    <div class="judul-disposisi">
        LEMBAR DISPOSISI
    </div>


    {{-- INFORMASI SURAT --}}
    <table class="info-table">

        <tr>
            <td class="label">
                Diterima
            </td>
            <td>
                {{ $disposisi->suratMasuk->tanggal_diterima?->format('d/m/Y') ?? '-' }}
            </td>

            <td class="label">
                Nomor Agenda
            </td>
            <td>
                {{ $disposisi->suratMasuk->nomor_surat_masuk ?? '-' }}
            </td>
        </tr>

        <tr>
            <td class="label">
                Pengirim
            </td>
            <td colspan="3">
                {{ $disposisi->suratMasuk->asal_instansi ?? '-' }}
            </td>
        </tr>

        <tr>
            <td class="label">
                Nomor Surat
            </td>
            <td>
                {{ $disposisi->suratMasuk->nomor_surat_asal ?? '-' }}
            </td>

            <td class="label">
                Tanggal Surat
            </td>
            <td>
                {{ $disposisi->suratMasuk->tanggal_surat?->format('d/m/Y') ?? '-' }}
            </td>
        </tr>

        <tr>
            <td class="label">
                Perihal
            </td>
            <td colspan="3">
                {{ $disposisi->suratMasuk->perihal ?? '-' }}
            </td>
        </tr>

        <tr>
            <td class="label">
                Sifat Surat
            </td>
            <td colspan="3">
                {{ ucfirst($disposisi->suratMasuk->sifat_surat ?? 'Biasa') }}
            </td>
        </tr>

    </table>


    <div class="mt-4">

        <table class="disposisi-table">

            <tr>
                <th style="width:35%">
                    DITUJUKAN KEPADA YTH.
                </th>

                <th>
                    ISI DISPOSISI
                </th>
            </tr>

            <tr>

                <td>
                    <strong>
                        {{ $disposisi->tujuan_label }}
                    </strong>

                    <br><br>

                    <small>
                        Disposisi dari:
                    </small>

                    <br>

                    {{ $disposisi->pemberiDisposisi->nama_lengkap ?? '-' }}

                    <br><br>

                    <small>
                        Tanggal:
                    </small>

                    <br>

                    {{ $disposisi->tanggal_disposisi?->format('d/m/Y H:i') ?? '-' }}
                </td>

                <td class="isi-disposisi">

                    @if($disposisi->instruksi)
                        <strong>Instruksi:</strong>
                        <br>
                        {{ $disposisi->instruksi }}
                    @endif

                    @if($disposisi->catatan)
                        <br><br>
                        <strong>Catatan:</strong>
                        <br>
                        {{ $disposisi->catatan }}
                    @endif

                </td>

            </tr>

        </table>

    </div>


    <div class="mt-4 text-end">

        <small>
            Status Disposisi:
        </small>

        <strong>
            {{ ucfirst($disposisi->status) }}
        </strong>

    </div>

</div>

@endsection