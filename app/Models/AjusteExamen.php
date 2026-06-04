<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AjusteExamen extends Model
{
    use HasFactory;

    protected $table = 'ajuste_examenes';

    protected $fillable = [
        'nro_preguntas',
        'peso',
        'orden',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

}