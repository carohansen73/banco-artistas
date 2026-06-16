<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Genero extends Model
{
    use HasFactory;

    protected $fillable = [
        'disciplina_id',
        'nombre',
        'slug',
    ];

        // Auto-genera el slug desde el nombre
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($genero) {
            $genero->slug = Str::slug($genero->nombre) . '-' . uniqid();
        });
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function artistas()
    {
        return $this->belongsToMany(Artista::class, 'artista_genero');
    }
}
