<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArtistaRedes extends Model
{
     use HasFactory;

    protected $table = 'artista_redes';

    protected $fillable = [
        'artista_id',
        'plataforma',
        'url',
        'nombre_personalizado',
    ];

    public function artista()
    {
        return $this->belongsTo(Artista::class);
    }

    // Devuelve el icono y color según config/redes.php
    public function getIconoAttribute(): string
    {
        $config = config('redes.' . $this->plataforma);
        return $config['icono'] ?? 'fa-link';
    }

    public function getColorAttribute(): string
    {
        $config = config('redes.' . $this->plataforma);
        return $config['color'] ?? '#888888';
    }

    // Nombre a mostrar en el botón
    public function getNombreDisplayAttribute(): string
    {
        return $this->nombre_personalizado ?? ucfirst($this->plataforma);
    }
}
