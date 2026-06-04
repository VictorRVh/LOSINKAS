<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GradoArea extends Model
{
    use HasFactory;

    protected $table = 'grado_areas';

    protected $fillable = [
        'nivel_id',
        'nombre_grado',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class);
    }

    public function cursos(): HasMany
    {
        return $this->hasMany(Curso::class);
    }

    // public function grupos(): HasMany
    // {
    //     return $this->hasMany(Grupo::class);
    // }

    public function examenes(): HasMany
    {
        return $this->hasMany(Examen::class);
    }
}