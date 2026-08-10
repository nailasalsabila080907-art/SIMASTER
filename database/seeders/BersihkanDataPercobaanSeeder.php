<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Database\Seeder;

class BersihkanDataPercobaanSeeder extends Seeder
{
    // NIP dummy yang dipakai AkunPercobaanSeeder dulu
    protected string $nipPercobaan = '000000000000000001';

    public function run(): void
    {
        $pegawai = Pegawai::where('nip', $this->nipPercobaan)->first();

        if (! $pegawai) {
            $this->command->info('Data percobaan sudah bersih, tidak ada yang perlu dihapus.');
            return;
        }

        $user = $pegawai->user;

        if ($user) {
            // Hapus dulu surat-surat yang dibuat/diterima akun percobaan, biar FK tidak bentrok
            $jumlahSuratKeluar = SuratKeluar::where('dibuat_oleh', $user->id_user)->count();
            SuratKeluar::where('dibuat_oleh', $user->id_user)->delete();

            $jumlahSuratMasuk = SuratMasuk::where('diterima_oleh', $user->id_user)->count();
            SuratMasuk::where('diterima_oleh', $user->id_user)->delete();

            $this->command->info("Menghapus {$jumlahSuratKeluar} surat keluar dan {$jumlahSuratMasuk} surat masuk percobaan.");

            $user->delete();
            $this->command->info('Akun login percobaan (admintu) berhasil dihapus.');
        }

        $pegawai->delete();
        $this->command->info('Data pegawai percobaan berhasil dihapus. Database siap diisi data asli.');
    }
}
