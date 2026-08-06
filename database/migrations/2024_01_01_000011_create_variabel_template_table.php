<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variabel_template', function (Blueprint $table) {
            $table->id('id_variabel');
            $table->foreignId('id_template')
                ->constrained('template_surat', 'id_template')
                ->cascadeOnDelete();
            $table->string('nama_variabel', 50); // mis. nama_penerima, tanggal_acara
            $table->string('label', 100); // label ditampilkan di form
            $table->enum('tipe_input', ['text', 'textarea', 'date', 'number', 'select']);
            $table->boolean('wajib')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variabel_template');
    }
};
