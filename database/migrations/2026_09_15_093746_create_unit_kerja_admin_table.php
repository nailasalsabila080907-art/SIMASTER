<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Tabel ini merepresentasikan "siapa yang pegang akun unit" - satu unit
    // kerja bisa punya lebih dari satu admin (utama + cadangan), dan admin
    // unit TETAP akun pegawai biasa (role aslinya tidak berubah). Ini murni
    // penanda tambahan, bukan pegawai/akun baru, supaya tidak mengganggu
    // fitur lain yang query pegawai berdasarkan id_unit (penandatangan
    // surat, tembusan, approval, dsb).
    public function up(): void
    {
        Schema::create('unit_kerja_admin', function (Blueprint $table) {
            $table->id('id_unit_admin');
            $table->foreignId('id_unit')
                ->constrained('unit_kerja', 'id_unit')
                ->cascadeOnDelete();
            $table->foreignId('id_user')
                ->constrained('users', 'id_user')
                ->cascadeOnDelete();
            $table->enum('peran', ['utama', 'cadangan'])->default('utama');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();

            // Satu user cuma boleh jadi admin untuk unit yang sama sekali (tidak dobel baris)
            $table->unique(['id_unit', 'id_user']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_kerja_admin');
    }
};