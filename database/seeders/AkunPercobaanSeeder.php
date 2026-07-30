<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\Sekolah;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AkunPercobaanSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data sekolah (sesuai kop surat asli)
        $sekolah = Sekolah::firstOrCreate(
            ['kode_surat' => 'SMKN-07'],
            [
                'npsn' => '10490900',
                'nama_sekolah' => 'SMK Negeri 7 Pekanbaru',
                'kota' => 'Pekanbaru',
                'provinsi' => 'Riau',
                'nama_kepala_sekolah' => 'Padmi Riana, M.Pi, M.Pd',
                'nip_kepala_sekolah' => '19750316 200501 2 008',
            ]
        );

        // 2. Unit kerja TU
        $unit = UnitKerja::firstOrCreate(
            ['id_sekolah' => $sekolah->id_sekolah, 'kode_unit' => 'KP'],
            ['nama_unit' => 'Kepegawaian / Tata Usaha', 'status' => 'aktif']
        );

        // 3. Jabatan
        $jabatan = Jabatan::firstOrCreate(
            ['nama_jabatan' => 'Staff TU'],
            ['level_jabatan' => 1]
        );

        // 4. Data pegawai percobaan
        $pegawai = Pegawai::firstOrCreate(
            ['nip' => '000000000000000001'],
            [
                'id_sekolah' => $sekolah->id_sekolah,
                'id_unit' => $unit->id_unit,
                'id_jabatan' => $jabatan->id_jabatan,
                'nama_lengkap' => 'Admin TU (Percobaan)',
                'jenis_kelamin' => 'P',
                'status' => 'aktif',
            ]
        );

        // 5. Akun login percobaan
        User::firstOrCreate(
            ['username' => 'admintu'],
            [
                'id_pegawai' => $pegawai->id_pegawai,
                'password_hash' => Hash::make('password123'),
                'role' => 'admin_tu',
                'status' => 'aktif',
            ]
        );
    }
}
