<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KlasifikasiArsip extends Model
{
    use HasUuids;
    use HasFactory;

    protected $table = 'klasifikasi_arsip';
    protected $primaryKey = 'id_klasifikasi';

    protected $fillable = ['kode_klasifikasi', 'nama_klasifikasi', 'parent_id'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(KlasifikasiArsip::class, 'parent_id', 'id_klasifikasi');
    }

    public function children(): HasMany
    {
        return $this->hasMany(KlasifikasiArsip::class, 'parent_id', 'id_klasifikasi');
    }

    public function suratKeluar(): HasMany
    {
        return $this->hasMany(SuratKeluar::class, 'id_klasifikasi', 'id_klasifikasi');
    }

    public function suratMasuk(): HasMany
    {
        return $this->hasMany(SuratMasuk::class, 'id_klasifikasi', 'id_klasifikasi');
    }
     public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
