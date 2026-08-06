<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id('id_notifikasi');
            $table->foreignId('id_user')
                ->constrained('users', 'id_user')
                ->cascadeOnDelete();
            $table->enum('tipe_surat', ['masuk', 'keluar']);
            $table->unsignedBigInteger('id_surat');
            $table->string('judul', 150); // mis. "Disposisi baru dari Kepala Sekolah"
            $table->string('pesan', 255);
            $table->boolean('sudah_dibaca')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->index(['tipe_surat', 'id_surat']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
