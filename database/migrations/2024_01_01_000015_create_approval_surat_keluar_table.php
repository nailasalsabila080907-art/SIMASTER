<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_surat_keluar', function (Blueprint $table) {
            $table->id('id_approval');
            $table->foreignId('id_surat_keluar')
                ->constrained('surat_keluar', 'id_surat_keluar')
                ->cascadeOnDelete();
            $table->foreignId('id_pegawai_pemberi_approval')
                ->constrained('pegawai', 'id_pegawai')
                ->restrictOnDelete();
            $table->integer('urutan'); // 1 = Kepala TU, 2 = Kepala Sekolah, dst
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->string('catatan', 255)->nullable();
            $table->timestamp('tanggal_approval')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_surat_keluar');
    }
};
