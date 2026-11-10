<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE disposisi_surat_masuk MODIFY COLUMN status ENUM(
            'siap_kirim', 'menunggu', 'diterima', 'ditindaklanjuti', 'diteruskan', 'selesai', 'ditolak'
        ) NOT NULL DEFAULT 'menunggu'");

        DB::statement('ALTER TABLE disposisi_surat_masuk ADD COLUMN alasan_tolak TEXT NULL AFTER catatan');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE disposisi_surat_masuk DROP COLUMN alasan_tolak');
        DB::statement("ALTER TABLE disposisi_surat_masuk MODIFY COLUMN status ENUM(
            'siap_kirim', 'menunggu', 'diterima', 'ditindaklanjuti', 'diteruskan', 'selesai'
        ) NOT NULL DEFAULT 'menunggu'");
    }
};