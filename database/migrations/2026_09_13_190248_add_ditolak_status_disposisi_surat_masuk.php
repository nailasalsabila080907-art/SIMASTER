<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah status 'ditolak' - dipakai kalau penerima disposisi menolak
        // tugas yang diberikan (misal: salah unit tujuan)
        DB::statement("ALTER TABLE disposisi_surat_masuk MODIFY status ENUM(
            'siap_kirim', 'menunggu', 'diterima', 'ditindaklanjuti', 'diteruskan', 'selesai', 'ditolak'
        ) DEFAULT 'menunggu'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE disposisi_surat_masuk MODIFY status ENUM(
            'siap_kirim', 'menunggu', 'diterima', 'ditindaklanjuti', 'diteruskan', 'selesai'
        ) DEFAULT 'menunggu'");
    }
};