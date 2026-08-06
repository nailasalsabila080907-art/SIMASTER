<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id('id_surat_keluar');
            $table->string('nomor_surat', 100)->unique()->nullable(); // diisi otomatis saat disetujui, kosong saat masih draft
            $table->foreignId('id_template')
                ->constrained('template_surat', 'id_template')
                ->restrictOnDelete();
            $table->foreignId('id_kategori')
                ->constrained('kategori_surat', 'id_kategori')
                ->restrictOnDelete();
            $table->foreignId('id_klasifikasi')
                ->constrained('klasifikasi_arsip', 'id_klasifikasi')
                ->restrictOnDelete();
            $table->foreignId('id_unit_pembuat')
                ->constrained('unit_kerja', 'id_unit')
                ->restrictOnDelete();

            // Belum di-constrain ke surat_masuk - tabel itu baru dibuat di Fase 4.
            // FK ditambahkan lewat migration terpisah setelah surat_masuk ada.
            $table->unsignedBigInteger('id_surat_masuk_asal')->nullable();

            $table->string('perihal', 255);
            $table->string('tujuan', 255)->nullable();
            $table->longText('isi_surat')->nullable(); // hasil render final (template + isian)
            $table->json('data_variabel')->nullable(); // isian mentah tiap variabel, mis. {"nama_penerima": "..."}
            $table->date('tanggal_surat')->nullable();
            $table->enum('sifat_surat', ['biasa', 'penting', 'segera', 'rahasia'])->default('biasa');
            $table->enum('status', [
                'draft', 'diajukan', 'disetujui', 'ditolak', 'terkirim', 'diarsipkan',
            ])->default('draft');
            $table->string('file_draft_path')->nullable(); // PDF pratinjau sebelum disetujui
            $table->string('file_final_path')->nullable(); // PDF final setelah disetujui + nomor terbit
            $table->foreignId('dibuat_oleh')
                ->constrained('users', 'id_user')
                ->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keluar');
    }
};
