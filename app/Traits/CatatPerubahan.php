<?php

namespace App\Traits;

use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

trait CatatPerubahan
{
    // Panggil ini sekali di boot() Laravel (lewat static::booted) untuk model yang pakai trait ini
    public static function bootCatatPerubahan(): void
    {
        static::created(function ($model) {
            self::catatPerubahan('tambah_data', $model, "Menambah data baru");
        });

        static::updated(function ($model) {
            self::catatPerubahan('ubah_data', $model, "Mengubah data");
        });

        static::deleted(function ($model) {
            self::catatPerubahan('hapus_data', $model, "Menghapus data");
        });
    }

    protected static function catatPerubahan(string $aktivitas, $model, string $deskripsi): void
    {
        if (! Auth::check()) {
            return; // mis. saat seeding/migrasi, tidak ada user login
        }

        $namaModul = class_basename($model);

        LogAktivitas::catat(
            aktivitas: $aktivitas,
            modul: $namaModul,
            deskripsi: "{$deskripsi} pada {$namaModul} (ID: {$model->getKey()})"
        );
    }
}
