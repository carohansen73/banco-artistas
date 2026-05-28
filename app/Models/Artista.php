<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Artista extends Model
{
    /** @use HasFactory<\Database\Factories\ArtistaFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'disciplina_id',
        'nombre_artistico',
        'localidad',
        'slug',
        'img_perfil',
        'descripcion_actividad',
        'integrantes',
        'tiene_formacion',
        'detalle_formacion',
        'anio_inicio',
        'tiene_documentacion',
        'acepta_difusion',
        'telefono',
        'domicilio',
        'rol_proyecto',
        'visible',
    ];

    protected $casts = [
        'tiene_formacion'    => 'boolean',
        'tiene_documentacion'=> 'boolean',
        'acepta_difusion'    => 'boolean',
        'visible'            => 'boolean',
    ];

    // Auto-genera el slug desde el nombre artístico
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($artista) {
            $artista->slug = Str::slug($artista->nombre_artistico) . '-' . uniqid();
            // $artista->slug = Str::slug($artista->nombre_artistico);
        });
    }

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function generos()
    {
        return $this->belongsToMany(Genero::class, 'artista_genero');
    }

    public function redes()
    {
        return $this->hasMany(ArtistaRedes::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class)->orderBy('orden');
    }

    // Helpers útiles
    public function fotos()
    {
        return $this->hasMany(Media::class)->where('tipo', 'foto')->orderBy('orden');
    }

    public function tracks()
    {
        return $this->hasMany(Media::class)->where('tipo', 'audio_link')->orderBy('orden');
    }

    public function videos()
    {
        return $this->hasMany(Media::class)->where('tipo', 'video_link')->orderBy('orden');
    }

    public function tieneDisciplina(string $nombre): bool
    {
        return $this->disciplina->nombre === $nombre;
    }

    // pARA RUTA CON SLUG
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
