<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->unsignedBigInteger('id_disposisi')
                ->nullable()
                ->after('id_surat');

            $table->index('id_disposisi');
        });
    }

    public function down(): void
    {
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->dropIndex(['id_disposisi']);
            $table->dropColumn('id_disposisi');
        });
    }
};