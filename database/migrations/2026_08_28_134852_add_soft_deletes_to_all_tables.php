<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('jabatan', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('jabatan', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};