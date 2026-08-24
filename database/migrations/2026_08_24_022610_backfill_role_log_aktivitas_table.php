<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('log_aktivitas')
            ->join('users', 'users.id_user', '=', 'log_aktivitas.id_user')
            ->whereNull('log_aktivitas.role')
            ->update([
                'log_aktivitas.role' => DB::raw('users.role'),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Sengaja dibiarkan kosong — lihat penjelasan di bawah
    }
};