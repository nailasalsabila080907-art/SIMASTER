<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KlasifikasiArsip extends Model
{
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
}
