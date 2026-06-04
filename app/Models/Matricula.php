<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Matricula extends Pivot
{
    use HasFactory;

    protected $table = 'matriculas';

    public $incrementing = true;

    protected $fillable = [
        'estudiante_id',
        'grupo_id',
        'fecha_matricula',
        'estado',
    ];

    protected $casts = [
        'fecha_matricula' => 'date',
    ];

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class);
    }
}