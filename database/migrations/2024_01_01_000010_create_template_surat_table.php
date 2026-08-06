<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_surat', function (Blueprint $table) {
            $table->id('id_template');
            $table->foreignId('id_kategori')
                ->constrained('kategori_surat', 'id_kategori')
                ->cascadeOnDelete();
            $table->string('nama_template', 150);
            $table->string('kode_template', 30)->unique(); // mis. TPL-UNDANGAN-01
            $table->longText('isi_template'); // HTML dengan placeholder {{nama_variabel}}
            $table->string('format_nomor', 100); // mis. {kode_klasifikasi}/{kode_sekolah}/{kode_unit}/{tahun}/{no_urut}
            $table->string('file_asli_path')->nullable(); // file docx/pdf asli jika diunggah
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')
                ->constrained('users', 'id_user')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_surat');
    }
};
