<?php

namespace App\Http\Controllers;

use App\Models\DisposisiSuratMasuk;
use App\Models\LogAktivitas;
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
                'Disposisi surat baru',
                "Surat \"{$suratMasuk->perihal}\" didisposisikan kepada Anda."
            );
        }

        LogAktivitas::catat('tambah_data', 'Disposisi Surat Masuk', "Membuat disposisi untuk surat: {$suratMasuk->perihal}");

        return back()->with('sukses', 'Disposisi berhasil dibuat.');
    }

    public function tindaklanjuti(DisposisiSuratMasuk $disposisi)
    {
        $this->bolehKelola($disposisi);
        $disposisi->update(['status' => 'ditindaklanjuti']);
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
        return back()->with('sukses', 'Disposisi ditandai selesai.');
    }

    private function bolehKelola(DisposisiSuratMasuk $disposisi): void
    {
        $pegawaiId = Auth::user()->pegawai?->id_pegawai;
        $bolehAdmin = in_array(Auth::user()->role, ['admin_tu', 'super_admin', 'kepala_sekolah'], true);
        $bolehPenerima = $disposisi->ke_pegawai === $pegawaiId;
        abort_unless($bolehAdmin || $bolehPenerima, 403, 'Anda tidak memiliki akses ke disposisi ini.');
    }
}
