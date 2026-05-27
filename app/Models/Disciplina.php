<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Disciplina extends Model
{
    use HasFactory;

      protected $fillable = [
        'nombre',
        'slug',
        'pendiente_revision',
    ];

    protected $casts = [
        'pendiente_revision' => 'boolean',
    ];

    public function generos()
    {
        return $this->hasMany(Genero::class);
    }

    public function artistas()
    {
        return $this->hasMany(Artista::class);
    }
}
