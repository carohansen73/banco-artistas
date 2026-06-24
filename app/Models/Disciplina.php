<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

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

    protected static function booted()
    {
        static::creating(function ($disciplina) {
            if (empty($disciplina->slug)) {
                $disciplina->slug = Str::slug($disciplina->nombre);
            }
        });
    }

    public function generos()
    {
        return $this->hasMany(Genero::class);
    }

    public function artistas()
    {
        return $this->hasMany(Artista::class);
    }

}
