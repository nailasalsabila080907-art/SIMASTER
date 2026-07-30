<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id('id_pegawai');
            $table->foreignId('id_sekolah')
                ->constrained('sekolah', 'id_sekolah')
                ->cascadeOnDelete();
            $table->foreignId('id_unit')->nullable()
                ->constrained('unit_kerja', 'id_unit')
                ->nullOnDelete();
            $table->foreignId('id_jabatan')->nullable()
                ->constrained('jabatan', 'id_jabatan')
                ->nullOnDelete();
            $table->foreignId('id_jurusan')->nullable() // diisi jika Kajur / guru jurusan tertentu
                ->constrained('jurusan', 'id_jurusan')
                ->nullOnDelete();
            $table->string('nip', 30)->unique();
            $table->string('nama_lengkap', 100);
            $table->string('gelar_depan', 20)->nullable();
            $table->string('gelar_belakang', 20)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('pangkat_golongan', 50)->nullable(); // mis. Pembina Tk.1/IV.b
            $table->string('no_hp', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('tanda_tangan_path')->nullable(); // file scan ttd, dipakai saat generate surat
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
