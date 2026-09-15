<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('disposisi_surat_masuk', 'uuid')) {
            Schema::table('disposisi_surat_masuk', function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->unique()->after('id_disposisi');
            });
        }

        // Isi UUID buat baris yang sudah ada duluan (dibuat sebelum kolom ini ada),
        // biar tidak ada yang uuid-nya kosong (NULL). Baris baru nanti otomatis
        // keisi lewat trait HasUuids di model, ini cuma buat data lama.
        DB::table('disposisi_surat_masuk')->whereNull('uuid')->orderBy('id_disposisi')->get(['id_disposisi'])
            ->each(function ($row) {
                DB::table('disposisi_surat_masuk')
                    ->where('id_disposisi', $row->id_disposisi)
                    ->update(['uuid' => (string) \Illuminate\Support\Str::orderedUuid()]);
            });
    }

    public function down(): void
    {
        Schema::table('disposisi_surat_masuk', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};