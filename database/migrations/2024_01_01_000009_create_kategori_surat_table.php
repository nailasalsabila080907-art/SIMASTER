<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_surat', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->string('nama_kategori', 100); // Undangan, Surat Tugas, Surat Keterangan, dst
            $table->enum('jenis', ['masuk', 'keluar', 'umum']);
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_surat');
    }
};
