<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnitKerjaAdmin extends Model
{
    use HasFactory;

    protected $table = 'unit_kerja_admin';
    protected $primaryKey = 'id_unit_admin';

    protected $fillable = [
        'id_unit', 'id_user', 'peran', 'status',
    ];

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'id_unit', 'id_unit');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function scopeAktif(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('status', 'aktif');
    }
}