<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curso extends Model
{
    use HasFactory;

    protected $table = 'cursos';

    protected $fillable = [
        'nombre_curso',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function gradoArea(): BelongsTo
    {
        return $this->belongsTo(GradoArea::class);
    }

    public function ajusteExamenes(): HasMany
    {
        return $this->hasMany(AjusteExamen::class);
    }

    public function cursoExamenes(): HasMany
    {
        return $this->hasMany(CursoExamen::class);
    }
    
    public function grupo(): HasMany
    {
        return $this->hasMany(Grupo::class);
    }

    public function examenes(): BelongsToMany
    {
        return $this->belongsToMany(Examen::class, 'curso_examen')
            ->using(CursoExamen::class)
            ->withPivot([
                'id',
                'ajuste_examen_id',
                'orden',
                'cantidad_preguntas',
                'puntaje_total',
            ])
            ->withTimestamps();
    }
}