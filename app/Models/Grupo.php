<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grupo extends Model
{
    use HasFactory;

    protected $table = 'grupos';

    protected $fillable = [
        'padre_id',
        'curso_id',
        'activo',
        'nombre_grupo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function padre(): BelongsTo
    {
        return $this->belongsTo(PadreGrupo::class, 'padre_id');
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function estudiantes(): BelongsToMany
    {
        return $this->belongsToMany(Estudiante::class, 'matriculas')
            ->using(Matricula::class)
            // ->withPivot(['id', 'fecha_matricula'])
            ->withPivot(['id'])
            ->withTimestamps();
    }

    public function notaEstudiantes(): HasMany
    {
        return $this->hasMany(NotaEstudiante::class);
    }
}
