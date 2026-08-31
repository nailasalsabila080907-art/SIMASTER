<?php

namespace App\Http\Controllers;

use App\Models\DisposisiSuratMasuk;
use App\Models\LogAktivitas;
use App\Models\LogAktivitasSurat;
use App\Models\Notifikasi;
use App\Models\Pegawai;
use App\Models\SuratMasuk;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisposisiSuratMasukController extends Controller
{
    public function store(Request $request, SuratMasuk $suratMasuk)
    {
        abort_unless(in_array(Auth::user()->role, ['admin_tu', 'super_admin', 'kepala_sekolah'], true), 403);

        $data = $request->validate([
            'tujuan_tipe' => 'required|in:pegawai,unit',
            'tujuan_id' => 'required|integer',
            'instruksi' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $pegawaiPengirim = Auth::user()->pegawai;
        abort_unless($pegawaiPengirim, 422, 'Akun Anda belum terhubung dengan data pegawai.');

        if ($data['tujuan_tipe'] === 'pegawai') {
            $penerima = Pegawai::where('status', 'aktif')->findOrFail($data['tujuan_id']);
            $kePegawai = $penerima->id_pegawai;
            $keUnit = null;
        } else {
            $unit = UnitKerja::where('status', 'aktif')->findOrFail($data['tujuan_id']);
            $kePegawai = null;
            $keUnit = $unit->id_unit;
        }

        $disposisi = DisposisiSuratMasuk::create([
            'id_surat_masuk' => $suratMasuk->id_surat_masuk,
            'dari_pegawai' => $pegawaiPengirim->id_pegawai,
            'ke_pegawai' => $kePegawai,
            'ke_unit' => $keUnit,
            'instruksi' => $data['instruksi'] ?? null,
            'catatan' => $data['catatan'] ?? null,
            'status' => 'menunggu',
        ]);

        $suratMasuk->update(['status' => 'didisposisi']);

        if ($kePegawai && $penerima->user) {
            Notifikasi::kirim(
                $penerima->user->id_user,
                'masuk',
                $suratMasuk->id_surat_masuk,
                $disposisi->id_disposisi,
                'Disposisi surat baru',
                "Surat \"{$suratMasuk->perihal}\" didisposisikan kepada Anda."
            );

        } elseif ($keUnit) {
            $pegawaiUnit = Pegawai::where('id_unit', $keUnit)
                ->where('status', 'aktif')
                ->with('user')
                ->get();

        foreach ($pegawaiUnit as $pegawai) {
        if ($pegawai->user) {
            Notifikasi::kirim(
                $pegawai->user->id_user,
                'Disposisi',
                $suratMasuk->id_surat_masuk,
                $disposisi->id_disposisi,
                'Disposisi surat baru',
                "Surat \"{$suratMasuk->perihal}\" didisposisikan ke {$unit->nama_unit}."
            );
        }
    }
}

        LogAktivitas::catat('tambah_data', 'Disposisi Surat Masuk', "Membuat disposisi untuk surat: {$suratMasuk->perihal}");

        $tujuanNama = $kePegawai ? $penerima->nama_lengkap : $unit->nama_unit;
        LogAktivitasSurat::catat(
        LogAktivitasSurat::TIPE_MASUK,
        $suratMasuk->id_surat_masuk,
        LogAktivitasSurat::AKSI_DISPOSISI,
        "Didisposisikan ke {$tujuanNama}" .
        ($data['instruksi'] ? ": {$data['instruksi']}" : '')
        );

        return back()->with('sukses', 'Disposisi berhasil dibuat.');
    }

    public function tindaklanjuti(DisposisiSuratMasuk $disposisi)
{
    $this->bolehKelola($disposisi);
    $disposisi->update(['status' => 'ditindaklanjuti']);

    LogAktivitasSurat::catat(
        LogAktivitasSurat::TIPE_MASUK,
        $disposisi->id_surat_masuk,
        LogAktivitasSurat::AKSI_TINDAK_LANJUT,
        "Disposisi mulai ditindaklanjuti"
    );

    return back()->with('sukses', 'Disposisi ditandai sedang ditindaklanjuti.');
    }
    public function selesaikan(DisposisiSuratMasuk $disposisi)
    {
        $this->bolehKelola($disposisi);
        $disposisi->update(['status' => 'selesai', 'tanggal_selesai' => now()]);

        $suratMasuk = $disposisi->suratMasuk;
        if ($suratMasuk->disposisi()->where('status', '!=', 'selesai')->doesntExist()) {
            $suratMasuk->update(['status' => 'selesai']);
        }

        LogAktivitas::catat('ubah_data', 'Disposisi Surat Masuk', "Menyelesaikan disposisi surat: {$suratMasuk->perihal}");

LogAktivitasSurat::catat(
    LogAktivitasSurat::TIPE_MASUK,
    $suratMasuk->id_surat_masuk,
    'disposisi_selesai', // saran: tambahin const AKSI_DISPOSISI_SELESAI
    "Disposisi selesai ditindaklanjuti"
);

if ($suratMasuk->status === 'selesai') {
    LogAktivitasSurat::catat(
        LogAktivitasSurat::TIPE_MASUK,
        $suratMasuk->id_surat_masuk,
        LogAktivitasSurat::AKSI_ARSIP, // atau const baru 'selesai' kalau mau beda dari arsip
        "Semua disposisi selesai, surat ditandai selesai"
    );
}

return back()->with('sukses', 'Disposisi ditandai selesai.');
    }

        public function show(DisposisiSuratMasuk $disposisi)
    {
        $pegawaiId = Auth::user()->pegawai?->id_pegawai;

        $bolehLihat =
            in_array(
                Auth::user()->role,
                ['admin_tu', 'super_admin', 'kepala_sekolah'],
                true
            )
            || $disposisi->ke_pegawai === $pegawaiId
            || (
                $disposisi->ke_unit
                && Auth::user()->pegawai?->id_unit === $disposisi->ke_unit
            );

        abort_unless(
            $bolehLihat,
            403,
            'Anda tidak memiliki akses ke lembar disposisi ini.'
        );

        $disposisi->load([
            'suratMasuk.kategori',
            'suratMasuk.klasifikasi',
            'pemberiDisposisi',
            'penerimaPegawai',
            'penerimaUnit',
        ]);

        $sekolah = \App\Models\Sekolah::first();

        return view('disposisi.show', compact(
            'disposisi',
            'sekolah'
        ));
    }


    private function bolehKelola(DisposisiSuratMasuk $disposisi): void
    {
        $pegawaiId = Auth::user()->pegawai?->id_pegawai;
        $bolehAdmin = in_array(Auth::user()->role, ['admin_tu', 'super_admin', 'kepala_sekolah'], true);
        $bolehPenerima = $disposisi->ke_pegawai === $pegawaiId;
        abort_unless($bolehAdmin || $bolehPenerima, 403, 'Anda tidak memiliki akses ke disposisi ini.');
    }
}
