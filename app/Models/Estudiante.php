<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiantes';

    protected $fillable = [
        'dni',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'telefono',
        'email',
        'activo',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'activo' => 'boolean',
    ];

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }

    public function grupos(): BelongsToMany
    {
        return $this->belongsToMany(Grupo::class, 'matriculas')
            ->using(Matricula::class)
            ->withPivot(['id'])
            ->withTimestamps();
    }

    public function notaEstudiantes(): HasMany
    {
        return $this->hasMany(NotaEstudiante::class);
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombres} {$this->apellidos}");
    }
}