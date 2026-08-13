<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitKerja extends Model
{
    use HasUuids;
    use HasFactory;

    protected $table = 'unit_kerja';
    protected $primaryKey = 'id_unit';

    protected $fillable = ['id_sekolah', 'kode_unit', 'nama_unit', 'keterangan', 'status'];

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah', 'id_sekolah');
    }

    public function pegawai(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'id_unit', 'id_unit');
    }

    public function penomoranSurat(): HasMany
    {
        return $this->hasMany(PenomoranSurat::class, 'id_unit', 'id_unit');
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
