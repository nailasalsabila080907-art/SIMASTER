<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\Sekolah;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $sekolah = Sekolah::firstOrCreate(
            ['kode_surat' => 'SMKN-07'],
            [
                'npsn' => '10490900',
                'nama_sekolah' => 'SMK Negeri 7 Pekanbaru',
                'kota' => 'Pekanbaru',
                'provinsi' => 'Riau',
                'nama_kepala_sekolah' => 'Padmi Riana, S.Pi, M.Pd',
                'nip_kepala_sekolah' => '19750316 200501 2 008',
            ]
        );

        $unitTu = UnitKerja::firstOrCreate(
            [
                'id_sekolah' => $sekolah->id_sekolah,
                'kode_unit' => 'KP'
            ],
            [
                'nama_unit' => 'Kepegawaian / Tata Usaha',
                'status' => 'aktif'
            ]
        );

        $unitKurikulum = UnitKerja::firstOrCreate(
            [
                'id_sekolah' => $sekolah->id_sekolah,
                'kode_unit' => 'KUR'
            ],
            [
                'nama_unit' => 'Kurikulum',
                'status' => 'aktif'
            ]
        );

        $jabatanStaff = Jabatan::firstOrCreate(
            ['nama_jabatan' => 'Staff TU'],
            ['level_jabatan' => 1]
        );

        $jabatanKepalaTu = Jabatan::firstOrCreate(
            ['nama_jabatan' => 'Kepala TU'],
            ['level_jabatan' => 2]
        );

        $jabatanWakaKurikulum = Jabatan::firstOrCreate(
            ['nama_jabatan' => 'Wakil Kepala Sekolah Bidang Kurikulum'],
            ['level_jabatan' => 2]
        );

        $jabatanWakaKesiswaan = Jabatan::firstOrCreate(
            ['nama_jabatan' => 'Wakil Kepala Sekolah Bidang Kesiswaan'],
            ['level_jabatan' => 2]
        );

        $jabatanWakaHumas = Jabatan::firstOrCreate(
            ['nama_jabatan' => 'Wakil Kepala Sekolah Bidang Humas'],
            ['level_jabatan' => 2]
        );
        
        $jabatanWakaSarpras = Jabatan::firstOrCreate(
            ['nama_jabatan' => 'Wakil Kepala Sekolah Bidang Sarana dan Prasarana'],
            ['level_jabatan' => 2]
        );

        $jabatanKepala = Jabatan::firstOrCreate(
            ['nama_jabatan' => 'Kepala Sekolah'],
            ['level_jabatan' => 3]
        );

        $adminPegawai = Pegawai::firstOrCreate(
            ['nip' => 'ADMIN001'],
            [
                'id_sekolah' => $sekolah->id_sekolah,
                'id_unit' => $unitTu->id_unit,
                'id_jabatan' => $jabatanStaff->id_jabatan,
                'nama_lengkap' => 'Administrator TU',
                'jenis_kelamin' => 'P',
                'status' => 'aktif',
            ]
        );

        $kepalaTuPegawai = Pegawai::firstOrCreate(
            ['nip' => 'KTU001'],
            [
                'id_sekolah' => $sekolah->id_sekolah,
                'id_unit' => $unitTu->id_unit,
                'id_jabatan' => $jabatanKepalaTu->id_jabatan,
                'nama_lengkap' => 'Kepala Tata Usaha',
                'jenis_kelamin' => 'L',
                'status' => 'aktif',
            ]
        );

        $kepalaPegawai = Pegawai::firstOrCreate(
            ['nip' => '197503162005012008'],
            [
                'id_sekolah' => $sekolah->id_sekolah,
                'id_unit' => $unitTu->id_unit,
                'id_jabatan' => $jabatanKepala->id_jabatan,
                'nama_lengkap' => 'Padmi Riana',
                'gelar_belakang' => 'S.Pi, M.Pd',
                'jenis_kelamin' => 'P',
                'pangkat_golongan' => 'Pembina Utama Muda/IV.c',
                'status' => 'aktif',
            ]
        );

        $kepalaKurikulumPegawai = Pegawai::firstOrCreate(
            ['nip' => 'WKUR001'],
            [   
                'id_sekolah' => $sekolah->id_sekolah,
                'id_unit' => $unitTu->id_unit,
                'id_jabatan' => $jabatanWakaKurikulum->id_jabatan,
                'nama_lengkap' => 'Wakil Kepala Sekolah Bidang Kurikulum',
                'jenis_kelamin' => 'P',
                'status' => 'aktif',
            ]
        );

        $kepalaKesiswaanPegawai = Pegawai::firstOrCreate(
            ['nip' => 'WKES001'],
            [
                'id_sekolah' => $sekolah->id_sekolah,
                'id_unit' => $unitTu->id_unit,
                'id_jabatan' => $jabatanWakaKesiswaan->id_jabatan,
                'nama_lengkap' => 'Wakil Kepala Sekolah Bidang Kesiswaan',
                'jenis_kelamin' => 'P',
                'status' => 'aktif',
            ]
        );

        $kepalaHumasPegawai = Pegawai::firstOrCreate(
            ['nip' => 'WHUM001'],
            [
                'id_sekolah' => $sekolah->id_sekolah,
                'id_unit' => $unitTu->id_unit,
                'id_jabatan' => $jabatanWakaHumas->id_jabatan,
                'nama_lengkap' => 'Wakil Kepala Sekolah Bidang Humas',
                'jenis_kelamin' => 'L',
                'status' => 'aktif',
            ]
        );

        $kepalaSarprasPegawai = Pegawai::firstOrCreate(
            ['nip' => 'WSAR001'],
            [
                'id_sekolah' => $sekolah->id_sekolah,
                'id_unit' => $unitTu->id_unit,
                'id_jabatan' => $jabatanWakaSarpras->id_jabatan,
                'nama_lengkap' => 'Wakil Kepala Sekolah Bidang Sarana dan Prasarana',
                'jenis_kelamin' => 'P',
                'status' => 'aktif',
            ]
        );

        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'id_pegawai' => $adminPegawai->id_pegawai,
                'password_hash' => Hash::make('admin123'),
                'role' => 'admin_tu',
                'status' => 'aktif',
            ]
        );

        User::firstOrCreate(
            ['username' => 'kepalatu'],
            [
                'id_pegawai' => $kepalaTuPegawai->id_pegawai,
                'password_hash' => Hash::make('kepalatu123'),
                'role' => 'admin_tu',
                'status' => 'aktif',
            ]
        );

        User::firstOrCreate(
            ['username' => 'kepsek'],
            [
                'id_pegawai' => $kepalaPegawai->id_pegawai,
                'password_hash' => Hash::make('kepsek123'),
                'role' => 'kepala_sekolah',
                'status' => 'aktif',
            ]
        );

        User::firstOrCreate(
            ['username' => 'wk_kurikulum'],
            [
                'id_pegawai' => $kepalaKurikulumPegawai->id_pegawai,
                'password_hash' => Hash::make('kurikulum123'),
                'role' => 'wakil_kepala_sekolah',
                'status' => 'aktif',
            ]
        );

        User::firstOrCreate(
            ['username' => 'wk_kesiswaan'],
            [
                'id_pegawai' => $kepalaKesiswaanPegawai->id_pegawai,
                'password_hash' => Hash::make('kesiswaan123'),
                'role' => 'wakil_kepala_sekolah',
                'status' => 'aktif',
            ]
        );

        User::firstOrCreate(
            ['username' => 'wk_humas'],
            [
                'id_pegawai' => $kepalaHumasPegawai->id_pegawai,
                'password_hash' => Hash::make('humas123'),
                'role' => 'wakil_kepala_sekolah',
                'status' => 'aktif',
            ]
        );

        User::firstOrCreate(
            ['username' => 'wk_sarpras'],
            [
                'id_pegawai' => $kepalaSarprasPegawai->id_pegawai,
                'password_hash' => Hash::make('sarpras123'),
                'role' => 'wakil_kepala_sekolah',
                'status' => 'aktif',
            ]
        );

        $this->call([
            DataAwalSeeder::class,
            UpdateIsiTemplateAsliSeeder::class,
        ]);

        $this->command->info('SIMASTER berhasil disiapkan dengan data dasar dan akun utama.');
        $this->command->line('Admin TU : admin / admin123');
        $this->command->line('Kepala TU: kepalatu / kepalatu123');
        $this->command->line('Kepsek   : kepsek / kepsek123');
        $this->command->line('Wakil Kepala Sekolah:');
        $this->command->line('  - WK Kurikulum: wk_kurikulum / kurikulum123');
        $this->command->line('  - WK Kesiswaan: wk_kesiswaan / kesiswaan123');
        $this->command->line('  - WK Humas: wk_humas / humas123');
        $this->command->line('  - WK Sarpras: wk_sarpras / sarpras123');
    }
}