<?php

namespace Database\Seeders;

use App\Models\KategoriSurat;
use App\Models\KlasifikasiArsip;
use App\Models\TemplateSurat;
use App\Models\User;
use App\Models\VariabelTemplate;
use Illuminate\Database\Seeder;

class DataAwalSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin_tu')->where('status', 'aktif')->orderBy('id_user')->first();
        if (! $admin) {
            $this->command->error('Belum ada akun admin_tu aktif. Jalankan DatabaseSeeder terlebih dahulu.');
            return;
        }

        KlasifikasiArsip::firstOrCreate(
            ['kode_klasifikasi' => '420.5'],
            ['nama_klasifikasi' => 'Persuratan Dinas Pendidikan']
        );

        $kategoriList = [
            ['nama' => 'Undangan', 'jenis' => 'keluar', 'kode_unit' => 'KP', 'kode' => 'TPL-UNDANGAN-01'],
            ['nama' => 'Surat Tugas', 'jenis' => 'keluar', 'kode_unit' => 'KP', 'kode' => 'TPL-TUGAS-01'],
            ['nama' => 'Surat Keterangan Pegawai', 'jenis' => 'keluar', 'kode_unit' => 'KP', 'kode' => 'TPL-KET-PEGAWAI-01'],
            ['nama' => 'Surat Pengantar', 'jenis' => 'keluar', 'kode_unit' => 'KP', 'kode' => 'TPL-PENGANTAR-01'],
            ['nama' => 'Surat Izin Riset', 'jenis' => 'keluar', 'kode_unit' => 'KM', 'kode' => 'TPL-IZIN-RISET-01'],
            ['nama' => 'Surat Keterangan Riset', 'jenis' => 'keluar', 'kode_unit' => 'KM', 'kode' => 'TPL-KET-RISET-01'],
            ['nama' => 'Surat Dinas Masuk', 'jenis' => 'masuk', 'kode_unit' => 'KP', 'kode' => null],
            ['nama' => 'Surat Umum', 'jenis' => 'umum', 'kode_unit' => 'KP', 'kode' => null],
        ];

        $fields = [
            'TPL-UNDANGAN-01' => [
                ['tujuan_undangan', 'Ditujukan kepada', 'text'],
                ['nama_acara', 'Nama acara', 'text'],
                ['hari_tanggal', 'Hari, tanggal', 'date'],
                ['pukul', 'Pukul', 'text'],
                ['tempat', 'Tempat', 'text'],
            ],
            'TPL-TUGAS-01' => [
                ['nama_pegawai', 'Nama pegawai', 'text'],
                ['nip', 'NIP', 'text'],
                ['pangkat_golongan', 'Pangkat/Golongan', 'text'],
                ['jabatan', 'Jabatan', 'text'],
                ['nama_kegiatan', 'Nama kegiatan', 'text'],
                ['tanggal_kegiatan', 'Tanggal pelaksanaan', 'date'],
                ['lokasi_kegiatan', 'Tempat kegiatan', 'text'],
            ],
            'TPL-KET-PEGAWAI-01' => [
                ['nama_pegawai', 'Nama pegawai', 'text'],
                ['nip', 'NIP/NIPPPK', 'text'],
                ['ttl', 'Tempat, tanggal lahir', 'text'],
                ['jenis_kelamin', 'Jenis kelamin', 'select'],
                ['jabatan', 'Jabatan', 'text'],
                ['keterangan', 'Isi keterangan', 'textarea'],
            ],
            'TPL-PENGANTAR-01' => [
                ['tujuan_surat', 'Ditujukan kepada', 'text'],
                ['perihal_lampiran', 'Perihal lampiran', 'textarea'],
            ],
            'TPL-IZIN-RISET-01' => [
                ['nama_mahasiswa', 'Nama mahasiswa', 'text'],
                ['nim', 'NIM/No. identitas', 'text'],
                ['prodi', 'Program studi', 'text'],
                ['guru_pamong', 'Guru pamong', 'text'],
                ['tanggal_riset', 'Periode riset', 'text'],
            ],
            'TPL-KET-RISET-01' => [
                ['nama_mahasiswa', 'Nama mahasiswa', 'text'],
                ['nim', 'NIM', 'text'],
                ['prodi', 'Program studi', 'text'],
                ['judul_penelitian', 'Judul penelitian', 'textarea'],
                ['periode_riset', 'Periode riset', 'text'],
            ],
        ];

        foreach ($kategoriList as $item) {
            $kategori = KategoriSurat::firstOrCreate(
                ['nama_kategori' => $item['nama']],
                ['jenis' => $item['jenis']]
            );

            if (! $item['kode']) {
                continue;
            }

            $template = TemplateSurat::firstOrCreate(
                ['kode_template' => $item['kode']],
                [
                    'id_kategori' => $kategori->id_kategori,
                    'nama_template' => $item['nama'],
                    'isi_template' => '<p>Template '.$item['nama'].'.</p>',
                    'format_nomor' => '420.5/SMKN-07/'.$item['kode_unit'].'/{tahun}/{no_urut}',
                    'is_active' => true,
                    'created_by' => $admin->id_user,
                ]
            );

            foreach ($fields[$item['kode']] as [$nama, $label, $tipe]) {
                VariabelTemplate::firstOrCreate(
                    ['id_template' => $template->id_template, 'nama_variabel' => $nama],
                    ['label' => $label, 'tipe_input' => $tipe, 'wajib' => true]
                );
            }
        }

        $this->command->info('Data master, kategori, template, dan variabel surat berhasil diisi.');
    }
}
