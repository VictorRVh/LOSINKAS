<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nivel extends Model
{
    use HasFactory;

    protected $table = 'niveles';

    protected $fillable = [
        'nombre_nivel',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function grado(): HasMany
    {
        return $this->hasMany(Grado::class);
    }
}