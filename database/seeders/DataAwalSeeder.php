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
        $admin = User::where('username', 'admin')->first();
        if (! $admin) {
            $this->command->warn('User admin tidak ditemukan. buat akun admin terlebih dahulu.');
            return;
        }

        $klasifikasi = KlasifikasiArsip::firstOrCreate(
            ['kode_klasifikasi' => '420.5'],
            ['nama_klasifikasi' => 'Persuratan Dinas Pendidikan']
        );

        // 6 kategori sesuai contoh surat SMK Negeri 7 Pekanbaru
        $kategoriList = [
            ['nama_kategori' => 'Undangan', 'kode_unit' => 'KP', 'kode_template' => 'TPL-UNDANGAN-01'],
            ['nama_kategori' => 'Surat Tugas', 'kode_unit' => 'KP', 'kode_template' => 'TPL-TUGAS-01'],
            ['nama_kategori' => 'Surat Keterangan Pegawai', 'kode_unit' => 'KP', 'kode_template' => 'TPL-KET-PEGAWAI-01'],
            ['nama_kategori' => 'Surat Pengantar', 'kode_unit' => 'KP', 'kode_template' => 'TPL-PENGANTAR-01'],
            ['nama_kategori' => 'Surat Izin Riset', 'kode_unit' => 'KM', 'kode_template' => 'TPL-IZIN-RISET-01'],
            ['nama_kategori' => 'Surat Keterangan Riset', 'kode_unit' => 'KM', 'kode_template' => 'TPL-KET-RISET-01'],
        ];

        // Field per template - sesuai hasil analisa 6 surat asli
        $fieldPerTemplate = [
            'TPL-UNDANGAN-01' => [
                ['nama_variabel' => 'tujuan_undangan', 'label' => 'Ditujukan kepada', 'tipe_input' => 'text'],
                ['nama_variabel' => 'nama_acara', 'label' => 'Nama acara', 'tipe_input' => 'text'],
                ['nama_variabel' => 'hari_tanggal', 'label' => 'Hari, tanggal', 'tipe_input' => 'date'],
                ['nama_variabel' => 'pukul', 'label' => 'Pukul', 'tipe_input' => 'text'],
                ['nama_variabel' => 'tempat', 'label' => 'Tempat', 'tipe_input' => 'text'],
            ],
            'TPL-TUGAS-01' => [
                ['nama_variabel' => 'nama_pegawai', 'label' => 'Nama pegawai', 'tipe_input' => 'text'],
                ['nama_variabel' => 'nip', 'label' => 'NIP', 'tipe_input' => 'text'],
                ['nama_variabel' => 'pangkat_golongan', 'label' => 'Pangkat/Golongan', 'tipe_input' => 'text'],
                ['nama_variabel' => 'jabatan', 'label' => 'Jabatan', 'tipe_input' => 'text'],
                ['nama_variabel' => 'nama_kegiatan', 'label' => 'Nama kegiatan', 'tipe_input' => 'text'],
                ['nama_variabel' => 'tanggal_kegiatan', 'label' => 'Tanggal pelaksanaan', 'tipe_input' => 'date'],
                ['nama_variabel' => 'lokasi_kegiatan', 'label' => 'Tempat kegiatan', 'tipe_input' => 'text'],
            ],
            'TPL-KET-PEGAWAI-01' => [
                ['nama_variabel' => 'nama_pegawai', 'label' => 'Nama pegawai', 'tipe_input' => 'text'],
                ['nama_variabel' => 'nip', 'label' => 'NIP/NIPPPK', 'tipe_input' => 'text'],
                ['nama_variabel' => 'ttl', 'label' => 'Tempat, tanggal lahir', 'tipe_input' => 'text'],
                ['nama_variabel' => 'jenis_kelamin', 'label' => 'Jenis kelamin', 'tipe_input' => 'select'],
                ['nama_variabel' => 'jabatan', 'label' => 'Jabatan', 'tipe_input' => 'text'],
                ['nama_variabel' => 'keterangan', 'label' => 'Isi keterangan', 'tipe_input' => 'textarea'],
            ],
            'TPL-PENGANTAR-01' => [
                ['nama_variabel' => 'tujuan_surat', 'label' => 'Ditujukan kepada', 'tipe_input' => 'text'],
                ['nama_variabel' => 'perihal_lampiran', 'label' => 'Perihal lampiran', 'tipe_input' => 'textarea'],
            ],
            'TPL-IZIN-RISET-01' => [
                ['nama_variabel' => 'nama_mahasiswa', 'label' => 'Nama mahasiswa', 'tipe_input' => 'text'],
                ['nama_variabel' => 'nim', 'label' => 'NIM/No. identitas', 'tipe_input' => 'text'],
                ['nama_variabel' => 'prodi', 'label' => 'Program studi', 'tipe_input' => 'text'],
                ['nama_variabel' => 'guru_pamong', 'label' => 'Guru pamong', 'tipe_input' => 'text'],
                ['nama_variabel' => 'tanggal_riset', 'label' => 'Periode riset', 'tipe_input' => 'text'],
            ],
            'TPL-KET-RISET-01' => [
                ['nama_variabel' => 'nama_mahasiswa', 'label' => 'Nama mahasiswa', 'tipe_input' => 'text'],
                ['nama_variabel' => 'nim', 'label' => 'NIM', 'tipe_input' => 'text'],
                ['nama_variabel' => 'prodi', 'label' => 'Program studi', 'tipe_input' => 'text'],
                ['nama_variabel' => 'judul_penelitian', 'label' => 'Judul penelitian', 'tipe_input' => 'textarea'],
                ['nama_variabel' => 'periode_riset', 'label' => 'Periode riset', 'tipe_input' => 'text'],
            ],
        ];

        foreach ($kategoriList as $item) {
            $jenisKategori = KategoriSurat::firstOrCreate(
                ['nama_kategori' => $item['nama_kategori']],
                ['jenis' => 'keluar']
            );

            $template = TemplateSurat::firstOrCreate(
                ['kode_template' => $item['kode_template']],
                [
                    'id_kategori' => $jenisKategori->id_kategori,
                    'nama_template' => $item['nama_kategori'],
                    'isi_template' => "<p>Isi surat {$item['nama_kategori']} - placeholder, tinggal ganti sesuai kop surat asli.</p>",
                    'format_nomor' => "420.5/SMKN-07/{$item['kode_unit']}/{tahun}/{no_urut}",
                    'is_active' => true,
                    'created_by' => $admin->id_user,
                ]
            );

            foreach ($fieldPerTemplate[$item['kode_template']] as $field) {
                VariabelTemplate::firstOrCreate(
                    ['id_template' => $template->id_template, 'nama_variabel' => $field['nama_variabel']],
                    ['label' => $field['label'], 'tipe_input' => $field['tipe_input'], 'wajib' => true]
                );
            }
        }

        $this->command->info('Data awal (klasifikasi, kategori, template, variabel) berhasil diisi.');
    }
}
