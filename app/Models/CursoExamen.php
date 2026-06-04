<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Model;

class CursoExamen extends Model
{
    use HasFactory;

    protected $table = 'curso_examen';

    public $incrementing = true;

    protected $fillable = ['examen_id', 'curso_id'];

    public function examen(): BelongsTo
    {
        return $this->belongsTo(Examen::class);
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    // public function ajusteExamen(): BelongsTo
    // {
    //     return $this->belongsTo(AjusteExamen::class);
    // }

    public function notaEstudiantes(): HasMany
    {
        return $this->hasMany(NotaEstudiante::class);
    }
}