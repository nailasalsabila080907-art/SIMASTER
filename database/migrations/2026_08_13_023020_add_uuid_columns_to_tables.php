<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'pegawai',
            'users',
            'surat_masuk',
            'surat_keluar',
            'template_surat',
            'unit_kerja',
            'jabatan',
            'kategori_surat',
            'klasifikasi_arsip',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'uuid')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->uuid('uuid')->unique();
                });
            }
        }
    }

    public function down(): void
    {
        $tables = [
            'pegawai', 'users', 'surat_masuk', 'surat_keluar',
            'template_surat', 'unit_kerja', 'jabatan',
            'kategori_surat', 'klasifikasi_arsip',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasColumn($tableName, 'uuid')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('uuid');
                });
            }
        }
    }
};