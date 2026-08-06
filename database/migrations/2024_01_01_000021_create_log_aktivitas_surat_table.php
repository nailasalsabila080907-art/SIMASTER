<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_aktivitas_surat', function (Blueprint $table) {
            $table->id('id_log');
            $table->enum('tipe_surat', ['masuk', 'keluar']);
            $table->unsignedBigInteger('id_surat');
            $table->foreignId('id_user')
                ->constrained('users', 'id_user')
                ->restrictOnDelete();
            $table->string('aktivitas', 150); // mis. "membuat draft", "mengajukan approval", "menyetujui"
            $table->string('keterangan', 255)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['tipe_surat', 'id_surat']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas_surat');
    }
};
