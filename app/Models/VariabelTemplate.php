<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariabelTemplate extends Model
{
    use HasFactory;

    protected $table = 'variabel_template';
    protected $primaryKey = 'id_variabel';

    protected $fillable = ['id_template', 'nama_variabel', 'label', 'tipe_input', 'wajib'];

    protected $casts = [
        'wajib' => 'boolean',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(TemplateSurat::class, 'id_template', 'id_template');
    }
}
