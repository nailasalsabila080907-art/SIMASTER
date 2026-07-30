<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jurusan', function (Blueprint $table) {
            $table->id('id_jurusan');
            $table->foreignId('id_sekolah')
                ->constrained('sekolah', 'id_sekolah')
                ->cascadeOnDelete();
            $table->string('kode_jurusan', 20); // TKJ, RPL, MM, dst
            $table->string('nama_jurusan', 100);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jurusan');
    }
};
