<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class PenomoranSurat extends Model
{
    use HasFactory;

    protected $table = 'penomoran_surat';
    protected $primaryKey = 'id_penomoran';

    protected $fillable = ['id_unit', 'id_kategori', 'tahun', 'nomor_urut_terakhir'];

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'id_unit', 'id_unit');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriSurat::class, 'id_kategori', 'id_kategori');
    }

    /**
     * Ambil nomor urut berikutnya secara aman (anti bentrok kalau diakses bersamaan),
     * lalu naikkan counter-nya. Dipakai saat surat keluar disetujui & butuh nomor resmi.
     */
    public static function nomorUrutBerikutnya(int $idUnit, int $idKategori, int $tahun): int
    {
        return DB::transaction(function () use ($idUnit, $idKategori, $tahun) {
            $counter = self::where('id_unit', $idUnit)
                ->where('id_kategori', $idKategori)
                ->where('tahun', $tahun)
                ->lockForUpdate()
                ->first();

            if (! $counter) {
                $counter = self::create([
                    'id_unit' => $idUnit,
                    'id_kategori' => $idKategori,
                    'tahun' => $tahun,
                    'nomor_urut_terakhir' => 0,
                ]);
            }

            $counter->increment('nomor_urut_terakhir');

            return $counter->nomor_urut_terakhir;
        });
    }
}
