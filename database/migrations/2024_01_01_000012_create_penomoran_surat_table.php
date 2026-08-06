<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penomoran_surat', function (Blueprint $table) {
            $table->id('id_penomoran');
            $table->foreignId('id_unit')
                ->constrained('unit_kerja', 'id_unit')
                ->cascadeOnDelete();
            $table->foreignId('id_kategori')
                ->constrained('kategori_surat', 'id_kategori')
                ->cascadeOnDelete();
            $table->integer('tahun');
            $table->integer('nomor_urut_terakhir')->default(0);
            $table->timestamps();

            // Kombinasi ini bersifat unique - tiap unit+kategori+tahun punya counter sendiri
            $table->unique(['id_unit', 'id_kategori', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penomoran_surat');
    }
};
