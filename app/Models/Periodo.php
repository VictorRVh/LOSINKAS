<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periodo extends Model
{
    use HasFactory;

    protected $table = 'periodos';

    protected $fillable = [
        'nombre_periodo',
    ];


    public function grupos(): HasMany
    {
        return $this->hasMany(Grupo::class);
    }
}