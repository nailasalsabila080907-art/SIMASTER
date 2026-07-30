<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');
            $table->foreignId('id_pegawai')->unique() // 1 pegawai = 1 akun login
                ->constrained('pegawai', 'id_pegawai')
                ->cascadeOnDelete();
            $table->string('username', 50)->unique();
            $table->string('password_hash');
            $table->enum('role', [
                'super_admin', 'admin_tu', 'kepala_sekolah', 'staff', 'guru', 'operator',
            ]);
            $table->timestamp('last_login')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
