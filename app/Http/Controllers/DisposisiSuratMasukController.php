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
        $data = $request->validate([
            'tujuan_tipe' => 'required|in:pegawai,unit',
            'tujuan_id' => 'required|integer',
            'instruksi' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $pegawaiPengirim = Auth::user()->pegawai;

        $disposisi = DisposisiSuratMasuk::create([
            'id_surat_masuk' => $suratMasuk->id_surat_masuk,
            'dari_pegawai' => $pegawaiPengirim?->id_pegawai,
            'ke_pegawai' => $data['tujuan_tipe'] === 'pegawai' ? $data['tujuan_id'] : null,
            'ke_unit' => $data['tujuan_tipe'] === 'unit' ? $data['tujuan_id'] : null,
            'instruksi' => $data['instruksi'] ?? null,
            'catatan' => $data['catatan'] ?? null,
            'status' => 'menunggu',
        ]);

        $suratMasuk->update(['status' => 'didisposisi']);

        // Kirim notifikasi ke penerima disposisi (kalau dia punya akun login)
        if ($data['tujuan_tipe'] === 'pegawai') {
            $penerima = Pegawai::find($data['tujuan_id']);
            if ($penerima?->user) {
                Notifikasi::kirim(
                    $penerima->user->id_user, 'masuk', $suratMasuk->id_surat_masuk,
                    'Disposisi surat baru', "Surat \"{$suratMasuk->perihal}\" didisposisikan kepada Anda."
                );
            }
        }

        LogAktivitas::catat('tambah_data', 'Disposisi Surat Masuk', "Membuat disposisi untuk surat: {$suratMasuk->perihal}");

        return back()->with('sukses', 'Disposisi berhasil dibuat.');
    }

    public function tindaklanjuti(DisposisiSuratMasuk $disposisi)
    {
        $disposisi->update(['status' => 'ditindaklanjuti']);
        return back()->with('sukses', 'Disposisi ditandai sedang ditindaklanjuti.');
    }

    public function selesaikan(DisposisiSuratMasuk $disposisi)
    {
        $disposisi->update(['status' => 'selesai', 'tanggal_selesai' => now()]);

        // Kalau semua disposisi surat ini sudah selesai, ubah status surat masuk jadi selesai juga
        $suratMasuk = $disposisi->suratMasuk;
        if ($suratMasuk->disposisi()->where('status', '!=', 'selesai')->doesntExist()) {
            $suratMasuk->update(['status' => 'selesai']);
        }

        LogAktivitas::catat('ubah_data', 'Disposisi Surat Masuk', "Menyelesaikan disposisi surat: {$suratMasuk->perihal}");

        return back()->with('sukses', 'Disposisi ditandai selesai.');
    }
}
