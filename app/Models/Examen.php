<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Examen extends Model
{
    use HasFactory;

    protected $table = 'examenes';

    protected $fillable = [
        'nombre_examen',
        'numero_examen',
        'descripcion',
        'fecha_examen',
        'clave_respuestas',
        'grado_area_id',
        'activo',
    ];

    protected $casts = [
        'fecha_examen' => 'date',
        // 'clave_respuestas' => 'array',
        'activo' => 'boolean',
    ];

    public function gradoArea(): BelongsTo
    {
        return $this->belongsTo(GradoArea::class);
    }

    public function cursoExamenes(): HasMany
    {
        return $this->hasMany(CursoExamen::class);
    }

    // public function cursos(): BelongsToMany
    // {
    //     return $this->belongsToMany(Curso::class, 'curso_examen')
    //         ->using(CursoExamen::class)
    //         ->withPivot([
    //             'id',
    //             'ajuste_examen_id',
    //             'orden',
    //             'cantidad_preguntas',
    //             'puntaje_total',
    //         ])
    //         ->withTimestamps();
    // }
}