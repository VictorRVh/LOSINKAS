<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PadreGrupo extends Model
{
    use HasFactory;

    protected $table = 'padre_grupos';

    protected $fillable = [
        'periodo_id',
        'seccion_id',
        'grado_id',
        'nombre_grupo',
    ];

    // protected $casts = [
    //     'activo' => 'boolean',
    // ];

    public function periodo(): BelongsTo
    {
        return $this->belongsTo(Periodo::class);
    }

    public function grado(): BelongsTo
    {
        return $this->belongsTo(GradoArea::class, 'grado_id');
    }

    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class);
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }

    public function grupos(): HasMany
    {
        return $this->hasMany(Grupo::class, 'padre_id');
    }

    // public function estudiantes(): BelongsToMany
    // {
    //     return $this->belongsToMany(Estudiante::class, 'matriculas')
    //         ->using(Matricula::class)
    //         // ->withPivot(['id', 'fecha_matricula'])
    //         ->withPivot(['id'])
    //         ->withTimestamps();
    // }

}
