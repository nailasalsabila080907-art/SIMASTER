<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('pegawai', 'foto_path')) {
            Schema::table('pegawai', function (Blueprint $table) {
                $table->string('foto_path')->nullable()->after('tanda_tangan_path');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pegawai', 'foto_path')) {
            Schema::table('pegawai', function (Blueprint $table) {
                $table->dropColumn('foto_path');
            });
        }
    }
};
