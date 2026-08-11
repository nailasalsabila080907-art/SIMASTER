<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateSurat extends Model
{
    use HasFactory;

    protected $table = 'template_surat';
    protected $primaryKey = 'id_template';

    protected $fillable = [
        'id_kategori', 'nama_template', 'kode_template', 'isi_template',
        'format_nomor', 'file_asli_path', 'is_active', 'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriSurat::class, 'id_kategori', 'id_kategori');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    public function variabel(): HasMany
    {
        return $this->hasMany(VariabelTemplate::class, 'id_template', 'id_template');
    }

    public function suratKeluar(): HasMany
    {
        return $this->hasMany(SuratKeluar::class, 'id_template', 'id_template');
    }
}
