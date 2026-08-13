<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriSurat extends Model
{
    use HasUuids;
    use HasFactory;

    protected $table = 'kategori_surat';
    protected $primaryKey = 'id_kategori';

    protected $fillable = ['nama_kategori', 'jenis', 'keterangan'];

    public function templateSurat(): HasMany
    {
        return $this->hasMany(TemplateSurat::class, 'id_kategori', 'id_kategori');
    }

    public function penomoranSurat(): HasMany
    {
        return $this->hasMany(PenomoranSurat::class, 'id_kategori', 'id_kategori');
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
