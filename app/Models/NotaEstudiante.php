<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotaEstudiante extends Model
{
    use HasFactory;

    protected $table = 'nota_estudiantes';

    protected $fillable = [
        'estudiante_id',
        'grupo_id',
        'curso_examen_id',
        'nota',
        'respuestas_estudiante',
        'observacion',
    ];

    protected $casts = [
        'nota' => 'decimal:2',
        // 'respuestas_estudiante' => 'array',
    ];

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class);
    }

    public function cursoExamen(): BelongsTo
    {
        return $this->belongsTo(CursoExamen::class);
    }
}
