<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->id('id_surat_masuk');
            $table->string('nomor_surat_masuk', 100)->nullable(); // nomor agenda internal
            $table->string('nomor_surat_asal', 100)->nullable(); // nomor surat dari pengirim
            $table->string('asal_instansi', 150);
            $table->foreignId('id_kategori')
                ->constrained('kategori_surat', 'id_kategori')
                ->restrictOnDelete();
            $table->foreignId('id_klasifikasi')
                ->constrained('klasifikasi_arsip', 'id_klasifikasi')
                ->restrictOnDelete();
            $table->string('perihal', 255);
            $table->date('tanggal_surat')->nullable(); // tanggal tertulis pada surat
            $table->date('tanggal_diterima'); // tanggal diterima sekolah
            $table->enum('sifat_surat', ['biasa', 'penting', 'segera', 'rahasia'])->default('biasa');
            $table->string('file_scan_path')->nullable();
            $table->enum('status', [
                'baru', 'didisposisi', 'diproses', 'selesai', 'diarsipkan',
            ])->default('baru');
            $table->foreignId('diterima_oleh')
                ->constrained('users', 'id_user')
                ->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_masuk');
    }
};
