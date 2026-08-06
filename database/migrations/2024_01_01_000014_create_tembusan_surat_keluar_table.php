<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tembusan_surat_keluar', function (Blueprint $table) {
            $table->id('id_tembusan');
            $table->foreignId('id_surat_keluar')
                ->constrained('surat_keluar', 'id_surat_keluar')
                ->cascadeOnDelete();
            $table->string('nama_tujuan', 150); // nama/jabatan pihak penerima tembusan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tembusan_surat_keluar');
    }
};
