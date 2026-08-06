<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lampiran_surat', function (Blueprint $table) {
            $table->id('id_lampiran');
            $table->enum('tipe_surat', ['masuk', 'keluar']);
            $table->unsignedBigInteger('id_surat'); // referensi ke surat_masuk/surat_keluar, dijaga di level aplikasi
            $table->string('nama_file', 150);
            $table->string('file_path');
            $table->unsignedInteger('ukuran_file')->nullable(); // dalam KB
            $table->foreignId('uploaded_by')
                ->constrained('users', 'id_user')
                ->restrictOnDelete();
            $table->timestamp('uploaded_at')->useCurrent();

            $table->index(['tipe_surat', 'id_surat']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lampiran_surat');
    }
};
