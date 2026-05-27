<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Genero extends Model
{
    use HasFactory;

    protected $fillable = [
        'disciplina_id',
        'nombre',
        'slug',
    ];

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function artistas()
    {
        return $this->belongsToMany(Artista::class, 'artista_genero');
    }
}
