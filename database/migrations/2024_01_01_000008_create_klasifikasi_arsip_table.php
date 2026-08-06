<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('klasifikasi_arsip', function (Blueprint $table) {
            $table->id('id_klasifikasi');
            $table->string('kode_klasifikasi', 20)->unique(); // mis. 420.5
            $table->string('nama_klasifikasi', 150);
            $table->foreignId('parent_id')->nullable() // hierarki klasifikasi, opsional
                ->constrained('klasifikasi_arsip', 'id_klasifikasi')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('klasifikasi_arsip');
    }
};
