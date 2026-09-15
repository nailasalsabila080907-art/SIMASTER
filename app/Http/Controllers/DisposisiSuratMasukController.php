<?php

namespace App\Http\Controllers;

use App\Models\DisposisiSuratMasuk;
use App\Models\LogAktivitas;
use App\Models\LogAktivitasSurat;
use App\Models\Notifikasi;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DisposisiSuratMasukController extends Controller
{
    // =====================================================
    // ALUR:
    // 1. Admin TU (atau super_admin) langsung membuat disposisi
    //    ke satu/lebih pegawai & unit kerja (disposisikan).
    // 2a. Kalau tujuannya PEGAWAI perorangan: penerima
    //     menindaklanjuti (tindaklanjuti), menyelesaikan
    //     (selesaikan), atau menolak (tolak) disposisinya.
    // 2b. Kalau tujuannya UNIT: notifikasi & hak aksi HANYA
    //     untuk akun admin unit (lihat UnitKerjaAdmin) - bukan
    //     sembarang pegawai yang kebetulan satu unit. Aksinya
    //     cuma dua: Terima (langsung selesai) atau Tolak.
    //     Kalau unit itu belum punya admin aktif, surat tetap
    //     dibuat & didisposisikan, tapi admin_tu/super_admin
    //     diberi notifikasi supaya segera menunjuk admin unit.
    // 3. Kepala Sekolah TIDAK ikut proses ini sama sekali -
    //    dia cuma dapat notifikasi begitu SEMUA disposisi
    //    surat itu selesai (lihat method selesaikan()).
    // =====================================================

    public function disposisikan(Request $request, SuratMasuk $suratMasuk)
    {
        abort_unless(in_array(Auth::user()->role, ['admin_tu', 'super_admin'], true), 403);

        abort_if(
            in_array($suratMasuk->status, ['selesai', 'diarsipkan'], true),
            422,
            'Surat ini sudah selesai/diarsipkan, tidak bisa didisposisikan lagi.'
        );

        $data = $request->validate([
            'tujuan_pegawai' => 'nullable|array',
            'tujuan_pegawai.*' => 'integer|exists:pegawai,id_pegawai',
            'tujuan_unit' => 'nullable|array',
            'tujuan_unit.*' => 'integer|exists:unit_kerja,id_unit',
            'instruksi' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $totalTujuan = count($data['tujuan_pegawai'] ?? []) + count($data['tujuan_unit'] ?? []);
        abort_if($totalTujuan === 0, 422, 'Pilih minimal satu tujuan (pegawai atau unit).');

        $pegawaiPengirim = Auth::user()->pegawai;
        abort_unless($pegawaiPengirim, 422, 'Akun Anda belum terhubung dengan data pegawai.');

        $dibuat = [];

        DB::transaction(function () use ($data, $suratMasuk, $pegawaiPengirim, &$dibuat) {
            foreach ($data['tujuan_pegawai'] ?? [] as $idPegawai) {
                $dibuat[] = DisposisiSuratMasuk::create([
                    'id_surat_masuk' => $suratMasuk->id_surat_masuk,
                    'dari_pegawai' => $pegawaiPengirim->id_pegawai,
                    'ke_pegawai' => $idPegawai,
                    'instruksi' => $data['instruksi'] ?? null,
                    'catatan' => $data['catatan'] ?? null,
                    'status' => 'menunggu',
                ]);
            }

            foreach ($data['tujuan_unit'] ?? [] as $idUnit) {
                $dibuat[] = DisposisiSuratMasuk::create([
                    'id_surat_masuk' => $suratMasuk->id_surat_masuk,
                    'dari_pegawai' => $pegawaiPengirim->id_pegawai,
                    'ke_unit' => $idUnit,
                    'instruksi' => $data['instruksi'] ?? null,
                    'catatan' => $data['catatan'] ?? null,
                    'status' => 'menunggu',
                ]);
            }

            $suratMasuk->update(['status' => 'didisposisi']);
        });

        foreach ($dibuat as $disposisi) {
            if ($disposisi->ke_pegawai && $disposisi->penerimaPegawai?->user) {
                Notifikasi::kirim(
                    $disposisi->penerimaPegawai->user->id_user,
                    'masuk',
                    $suratMasuk->id_surat_masuk,
                    $disposisi->id_disposisi,
                    'Disposisi surat baru',
                    "Surat \"{$suratMasuk->perihal}\" didisposisikan kepada Anda."
                );
            } elseif ($disposisi->ke_unit) {
                $unit = $disposisi->penerimaUnit;
                $adminUnit = $unit?->penggunaAdminAktif() ?? collect();

                if ($adminUnit->isNotEmpty()) {
                    foreach ($adminUnit as $userAdmin) {
                        Notifikasi::kirim(
                            $userAdmin->id_user,
                            'masuk',
                            $suratMasuk->id_surat_masuk,
                            $disposisi->id_disposisi,
                            'Disposisi surat baru',
                            "Surat \"{$suratMasuk->perihal}\" didisposisikan ke unit {$unit?->nama_unit}."
                        );
                    }
                } else {
                    // Belum ada admin unit yang ditunjuk - surat tetap jalan,
                    // tapi admin_tu/super_admin perlu tahu supaya segera
                    // menunjuk siapa yang pegang akun unit ini.
                    $adminSistem = User::whereIn('role', ['admin_tu', 'super_admin'])
                        ->where('status', 'aktif')
                        ->get();

                    foreach ($adminSistem as $admin) {
                        Notifikasi::kirim(
                            $admin->id_user,
                            'masuk',
                            $suratMasuk->id_surat_masuk,
                            $disposisi->id_disposisi,
                            'Unit belum punya admin',
                            "Surat \"{$suratMasuk->perihal}\" didisposisikan ke unit {$unit?->nama_unit}, tapi unit ini belum punya akun admin. Segera tunjuk admin unit di halaman Unit Kerja."
                        );
                    }
                }
            }
        }

        LogAktivitas::catat('tambah_data', 'Disposisi Surat Masuk', "Membuat disposisi ke {$totalTujuan} tujuan: {$suratMasuk->perihal}");
        LogAktivitasSurat::catat(
            LogAktivitasSurat::TIPE_MASUK,
            $suratMasuk->id_surat_masuk,
            LogAktivitasSurat::AKSI_DISPOSISI,
            "Didisposisikan ke {$totalTujuan} tujuan" . (($data['instruksi'] ?? null) ? ": {$data['instruksi']}" : '')
        );

        return back()->with('sukses', 'Disposisi berhasil dibuat dan dikirim ke tujuan.');
    }

    public function terima(DisposisiSuratMasuk $disposisi)
    {
        $this->bolehKelola($disposisi);

        abort_unless($disposisi->status === 'menunggu', 422, 'Disposisi ini sudah diproses sebelumnya.');

        // Tujuan UNIT: "Terima" langsung dianggap selesai (satu langkah, cepat).
        // Tujuan PEGAWAI perorangan: "Terima" cuma menandai diterima,
        // masih perlu ditindaklanjuti & diselesaikan terpisah.
        if ($disposisi->ke_unit) {
            $disposisi->update(['status' => 'selesai', 'tanggal_selesai' => now()]);
        } else {
            $disposisi->update(['status' => 'diterima']);
        }

        $suratMasuk = $disposisi->suratMasuk;

        $belumSelesai = $suratMasuk->disposisi()
            ->whereNotIn('status', ['selesai', 'ditolak'])
            ->exists();

        if (! $belumSelesai) {
            $suratMasuk->update(['status' => 'selesai']);
        }

        LogAktivitas::catat('ubah_data', 'Disposisi Surat Masuk', "Menerima disposisi surat: {$suratMasuk->perihal}");
        LogAktivitasSurat::catat(
            LogAktivitasSurat::TIPE_MASUK,
            $suratMasuk->id_surat_masuk,
            LogAktivitasSurat::AKSI_TINDAK_LANJUT,
            "Disposisi ke {$disposisi->tujuan_label} diterima"
        );

        if ($suratMasuk->status === 'selesai') {
            LogAktivitasSurat::catat(
                LogAktivitasSurat::TIPE_MASUK,
                $suratMasuk->id_surat_masuk,
                LogAktivitasSurat::AKSI_SELESAI,
                "Semua disposisi selesai, surat ditandai selesai"
            );

            $kepsekList = User::where('role', 'kepala_sekolah')->where('status', 'aktif')->get();
            foreach ($kepsekList as $kepsek) {
                Notifikasi::kirim(
                    $kepsek->id_user,
                    'masuk',
                    $suratMasuk->id_surat_masuk,
                    null,
                    'Surat sudah selesai ditindaklanjuti',
                    "Surat \"{$suratMasuk->perihal}\" sudah selesai ditindaklanjuti oleh seluruh unit tujuan."
                );
            }
        }

        return back()->with('sukses', 'Disposisi diterima.');
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

        // Surat ditandai selesai kalau SEMUA disposisinya sudah selesai
        // (disposisi yang ditolak tidak dihitung menghalangi, karena memang tidak akan dikerjakan)
        $belumSelesai = $suratMasuk->disposisi()
            ->whereNotIn('status', ['selesai', 'ditolak'])
            ->exists();

        if (! $belumSelesai) {
            $suratMasuk->update(['status' => 'selesai']);
        }

        LogAktivitas::catat('ubah_data', 'Disposisi Surat Masuk', "Menyelesaikan disposisi surat: {$suratMasuk->perihal}");

        LogAktivitasSurat::catat(
            LogAktivitasSurat::TIPE_MASUK,
            $suratMasuk->id_surat_masuk,
            LogAktivitasSurat::AKSI_DISPOSISI_SELESAI,
            "Disposisi selesai ditindaklanjuti"
        );

        if ($suratMasuk->status === 'selesai') {
            LogAktivitasSurat::catat(
                LogAktivitasSurat::TIPE_MASUK,
                $suratMasuk->id_surat_masuk,
                LogAktivitasSurat::AKSI_SELESAI,
                "Semua disposisi selesai, surat ditandai selesai"
            );

            // Kepsek TIDAK ikut proses disposisi sama sekali - dia cuma
            // diberi tahu lewat notifikasi begitu suratnya benar-benar tuntas.
            $kepsekList = User::where('role', 'kepala_sekolah')->where('status', 'aktif')->get();
            foreach ($kepsekList as $kepsek) {
                Notifikasi::kirim(
                    $kepsek->id_user,
                    'masuk',
                    $suratMasuk->id_surat_masuk,
                    null,
                    'Surat sudah selesai ditindaklanjuti',
                    "Surat \"{$suratMasuk->perihal}\" sudah selesai ditindaklanjuti oleh seluruh unit tujuan."
                );
            }
        }

        return back()->with('sukses', 'Disposisi ditandai selesai.');
    }

    public function tolak(Request $request, DisposisiSuratMasuk $disposisi)
    {
        $this->bolehKelola($disposisi);

        $data = $request->validate([
            'catatan_penolakan' => 'required|string|max:500',
        ]);

        $disposisi->update([
            'status' => 'ditolak',
            'catatan' => $data['catatan_penolakan'],
        ]);

        $suratMasuk = $disposisi->suratMasuk;

        // Beri tahu siapa yang membuat disposisi ini (Admin TU) kalau ditolak
        if ($disposisi->pemberiDisposisi?->user) {
            Notifikasi::kirim(
                $disposisi->pemberiDisposisi->user->id_user,
                'masuk',
                $suratMasuk->id_surat_masuk,
                $disposisi->id_disposisi,
                'Disposisi ditolak',
                "Disposisi surat \"{$suratMasuk->perihal}\" ke {$disposisi->tujuan_label} ditolak: {$data['catatan_penolakan']}"
            );
        }

        LogAktivitas::catat('ubah_data', 'Disposisi Surat Masuk', "Disposisi ke {$disposisi->tujuan_label} ditolak: {$suratMasuk->perihal}");
        LogAktivitasSurat::catat(
            LogAktivitasSurat::TIPE_MASUK,
            $suratMasuk->id_surat_masuk,
            LogAktivitasSurat::AKSI_TOLAK,
            "Disposisi ke {$disposisi->tujuan_label} ditolak: {$data['catatan_penolakan']}"
        );

        return back()->with('sukses', 'Disposisi ditolak.');
    }

    public function show(DisposisiSuratMasuk $disposisi)
    {
        $user = Auth::user();
        $pegawaiId = $user->pegawai?->id_pegawai;

        $bolehLihat =
            in_array(
                $user->role,
                ['admin_tu', 'super_admin', 'kepala_sekolah'],
                true
            )
            || $disposisi->ke_pegawai === $pegawaiId
            || (
                $disposisi->ke_unit
                && $user->adalahAdminUnit($disposisi->ke_unit)
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
        $user = Auth::user();
        $pegawaiId = $user->pegawai?->id_pegawai;
        $bolehAdmin = in_array($user->role, ['admin_tu', 'super_admin'], true);
        $bolehPenerima = $disposisi->ke_pegawai === $pegawaiId
            || ($disposisi->ke_unit && $user->adalahAdminUnit($disposisi->ke_unit));
        abort_unless($bolehAdmin || $bolehPenerima, 403, 'Anda tidak memiliki akses ke disposisi ini.');
    }
}