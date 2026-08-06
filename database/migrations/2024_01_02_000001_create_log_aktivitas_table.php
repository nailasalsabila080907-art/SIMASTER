<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id('id_log_aktivitas');
            $table->foreignId('id_user')->nullable()
                ->constrained('users', 'id_user')
                ->nullOnDelete();
            $table->string('aktivitas', 50); // login, logout, lihat_halaman, tambah_data, ubah_data, hapus_data
            $table->string('modul', 100)->nullable(); // mis. "Dashboard TU", "Surat Keluar", "Master Jabatan"
            $table->string('deskripsi', 255)->nullable(); // mis. "Membuka halaman Dashboard TU"
            $table->string('url', 255)->nullable();
            $table->string('method', 10)->nullable(); // GET, POST, PUT, DELETE
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['id_user', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
