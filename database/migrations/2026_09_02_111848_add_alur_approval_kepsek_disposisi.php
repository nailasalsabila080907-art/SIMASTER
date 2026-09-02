<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah status baru untuk alur: baru -> menunggu_approval_kepsek -> siap_kirim -> didisposisi
        DB::statement("ALTER TABLE surat_masuk MODIFY status ENUM(
            'baru', 'menunggu_approval_kepsek', 'siap_kirim',
            'didisposisi', 'diproses', 'selesai', 'diarsipkan'
        ) DEFAULT 'baru'");

        Schema::table('surat_masuk', function (Blueprint $table) {
            // Catatan dari Kepsek: diisi kalau surat DITOLAK (alasan penolakan),
            // atau catatan tambahan saat approve
            $table->text('catatan_kepsek')->nullable()->after('status');
        });

        // Tambah status 'siap_kirim' = disposisi sudah ditentukan Kepsek,
        // tapi belum "dikirim" (dinotifikasi ke penerima) oleh TU
        DB::statement("ALTER TABLE disposisi_surat_masuk MODIFY status ENUM(
            'siap_kirim', 'menunggu', 'diterima', 'ditindaklanjuti', 'diteruskan', 'selesai'
        ) DEFAULT 'menunggu'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE surat_masuk MODIFY status ENUM(
            'baru', 'didisposisi', 'diproses', 'selesai', 'diarsipkan'
        ) DEFAULT 'baru'");

        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->dropColumn('catatan_kepsek');
        });

        DB::statement("ALTER TABLE disposisi_surat_masuk MODIFY status ENUM(
            'menunggu', 'diterima', 'ditindaklanjuti', 'diteruskan', 'selesai'
        ) DEFAULT 'menunggu'");
    }
};