<?php

namespace Database\Seeders;

use App\Models\TemplateSurat;
use Illuminate\Database\Seeder;

class UpdateIsiTemplateAsliSeeder extends Seeder
{
    public function run(): void
    {
        $ttdKepala = '<p style="text-align:right">Pekanbaru, {{tanggal_surat}}<br>Kepala Sekolah,</p>'
            .'<p style="text-align:right">&nbsp;</p><p style="text-align:right">&nbsp;</p>'
            .'<p style="text-align:right"><u><strong>PADMI RIANA, S.Pi., M.Pd</strong></u><br>NIP. 19750316 200501 2 008</p>';

        $isi = [
            'TPL-UNDANGAN-01' => '
                <p>Kepada Yth,<br>{{tujuan_undangan}}<br>SMK Negeri 7 Pekanbaru</p>
                <p>Di Tempat</p>
                <p>Sebelumnya kami mendoakan semoga Bapak/Ibu dalam keadaan sehat walafiat dan selalu dalam lindungan Tuhan Yang Maha Esa. Bersama ini kami mengundang Bapak/Ibu untuk dapat hadir dalam kegiatan <strong>&quot;{{nama_acara}}&quot;</strong> yang akan dilaksanakan pada:</p>
                <p>Hari/Tanggal&nbsp;&nbsp;: {{hari_tanggal}}<br>Pukul&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{pukul}}<br>Tempat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{tempat}}</p>
                <p>Demikian kami sampaikan, atas perhatian dan kehadiran Bapak/Ibu tepat pada waktunya kami ucapkan terima kasih.</p>
            '.$ttdKepala,

            'TPL-TUGAS-01' => '
                <p style="text-align:center"><u><strong>SURAT TUGAS</strong></u></p>
                <p>Yang bertanda tangan di bawah ini Kepala Sekolah Menengah Kejuruan (SMK) Negeri 7 Kota Pekanbaru, dengan ini menugaskan kepada:</p>
                <p>Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{nama_pegawai}}<br>NIP&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{nip}}<br>Pangkat/Gol&nbsp;: {{pangkat_golongan}}<br>Jabatan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{jabatan}}</p>
                <p>Yang bersangkutan diberikan tugas untuk menghadiri kegiatan <strong>&quot;{{nama_kegiatan}}&quot;</strong> yang dilaksanakan pada {{tanggal_kegiatan}}, bertempat di {{lokasi_kegiatan}}.</p>
                <p>Demikian Surat Tugas ini diberikan untuk dilaksanakan dengan sebaik-baiknya dan penuh tanggung jawab.</p>
            '.$ttdKepala,

            'TPL-KET-PEGAWAI-01' => '
                <p style="text-align:center"><u><strong>SURAT KETERANGAN</strong></u></p>
                <p>Yang bertanda tangan di bawah ini, Kepala SMK Negeri 7 Kota Pekanbaru dengan ini menerangkan bahwa:</p>
                <p>Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{nama_pegawai}}<br>NIP/NIPPPK&nbsp;&nbsp;: {{nip}}<br>Tempat/Tgl Lahir&nbsp;: {{ttl}}<br>Jenis Kelamin&nbsp;&nbsp;&nbsp;: {{jenis_kelamin}}<br>Jabatan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{jabatan}}</p>
                <p>{{keterangan}}</p>
                <p>Demikian surat keterangan ini diberikan untuk dapat dipergunakan sebagaimana mestinya.</p>
            '.str_replace('Kepala Sekolah,', 'Kepala SMKN 7 Pekanbaru', $ttdKepala),

            'TPL-PENGANTAR-01' => '
                <p style="text-align:center"><u><strong>SURAT PENGANTAR</strong></u></p>
                <p>Kepada Yth,<br>{{tujuan_surat}}</p>
                <p>Di Tempat</p>
                <p>Dengan hormat,<br>Bersama ini kami kirimkan sebagai berikut:</p>
                <p>{{perihal_lampiran}}</p>
                <p>Demikian Surat Pengantar ini disampaikan, kami ucapkan terima kasih.</p>
            '.str_replace('Kepala Sekolah,', 'Kepala Sekolah', $ttdKepala),

            'TPL-IZIN-RISET-01' => '
                <p>Hal: Izin Riset</p>
                <p>Kepada Yth,<br>Dekan/Pimpinan Perguruan Tinggi Terkait</p>
                <p>Di Tempat</p>
                <p>Menindaklanjuti surat permohonan riset, dengan ini kami memberikan izin kepada nama yang tersebut di bawah untuk dapat melaksanakan Riset/Pra Riset di SMK Negeri 7 Pekanbaru.</p>
                <p>Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{nama_mahasiswa}}<br>NIM/KTP&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{nim}}<br>Program Studi&nbsp;: {{prodi}}<br>Guru Pamong&nbsp;&nbsp;: {{guru_pamong}}<br>Periode&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{tanggal_riset}}</p>
                <p>Demikian surat izin ini disampaikan, atas perhatiannya diucapkan terima kasih.</p>
            '.$ttdKepala,

            'TPL-KET-RISET-01' => '
                <p style="text-align:center"><u><strong>SURAT KETERANGAN</strong></u></p>
                <p>Yang bertanda tangan di bawah ini Kepala SMK Negeri 7 Pekanbaru, dengan ini menerangkan bahwa:</p>
                <p>Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{nama_mahasiswa}}<br>NIM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{nim}}<br>Program Studi&nbsp;&nbsp;: {{prodi}}<br>Judul Penelitian&nbsp;: {{judul_penelitian}}</p>
                <p>Nama di atas benar telah melaksanakan Riset/Pra Riset pada periode {{periode_riset}} di SMK Negeri 7 Pekanbaru.</p>
                <p>Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya, atas perhatian diucapkan terima kasih.</p>
            '.$ttdKepala,
        ];

        foreach ($isi as $kodeTemplate => $html) {
            $template = TemplateSurat::where('kode_template', $kodeTemplate)->first();
            if ($template) {
                $template->update(['isi_template' => trim($html)]);
                $this->command->info("Isi template {$kodeTemplate} berhasil diperbarui.");
            } else {
                $this->command->warn("Template {$kodeTemplate} tidak ditemukan, lewati.");
            }
        }
    }
}
