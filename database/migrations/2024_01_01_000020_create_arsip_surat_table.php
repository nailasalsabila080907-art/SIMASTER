<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arsip_surat', function (Blueprint $table) {
            $table->id('id_arsip');
            $table->enum('tipe_surat', ['masuk', 'keluar']);
            $table->unsignedBigInteger('id_surat');
            $table->string('lokasi_fisik', 100)->nullable(); // lemari/rak, jika arsip fisik masih disimpan
            $table->integer('tahun_arsip');
            $table->timestamp('tanggal_diarsipkan')->useCurrent();
            $table->foreignId('diarsipkan_oleh')
                ->constrained('users', 'id_user')
                ->restrictOnDelete();

            $table->index(['tipe_surat', 'id_surat']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsip_surat');
    }
};
