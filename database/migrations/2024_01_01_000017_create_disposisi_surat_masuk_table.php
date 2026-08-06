<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disposisi_surat_masuk', function (Blueprint $table) {
            $table->id('id_disposisi');
            $table->foreignId('id_surat_masuk')
                ->constrained('surat_masuk', 'id_surat_masuk')
                ->cascadeOnDelete();

            // Diisi jika disposisi ini diteruskan dari disposisi sebelumnya (disposisi berjenjang)
            $table->foreignId('id_disposisi_asal')->nullable()
                ->constrained('disposisi_surat_masuk', 'id_disposisi')
                ->nullOnDelete();

            $table->foreignId('dari_pegawai')
                ->constrained('pegawai', 'id_pegawai')
                ->restrictOnDelete();

            // Salah satu dari 3 kolom di bawah wajib diisi (dijaga di level aplikasi)
            $table->foreignId('ke_pegawai')->nullable()
                ->constrained('pegawai', 'id_pegawai')
                ->nullOnDelete();
            $table->foreignId('ke_unit')->nullable()
                ->constrained('unit_kerja', 'id_unit')
                ->nullOnDelete();
            $table->foreignId('ke_jurusan')->nullable()
                ->constrained('jurusan', 'id_jurusan')
                ->nullOnDelete();

            $table->string('instruksi', 255)->nullable(); // mis. "Mohon ditindaklanjuti"
            $table->text('catatan')->nullable();
            $table->enum('status', [
                'menunggu', 'diterima', 'ditindaklanjuti', 'diteruskan', 'selesai',
            ])->default('menunggu');
            $table->timestamp('tanggal_disposisi')->useCurrent();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disposisi_surat_masuk');
    }
};
