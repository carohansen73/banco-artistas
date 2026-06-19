<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Evento extends Model
{
    /** @use HasFactory<\Database\Factories\EventoFactory> */
    use HasFactory;

    protected $fillable = [
        'slug',
        'nombre',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'lugar',
        'direccion',
        'ciudad',
        'imagen_portada',
        'link_entradas',
        'link_externo',
        'destacado',
        'activo',
        'user_id',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin'    => 'datetime',
        'destacado'    => 'boolean',
        'activo'       => 'boolean',
    ];

    /* Genera el slug con el nombre del evento */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($evento) {
            $evento->slug = Str::slug($evento->nombre) . '-' . uniqid();
        });
    }

    // --- Relaciones ---

    /**
     * Usuario creador del evento
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Multiples artistas pueden participar de múltiples eventos
     */
    public function artistas()
    {
        return $this->belongsToMany(Artista::class, 'artista_evento')
                    ->withTimestamps();
    }

    // --- Scopes ---

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeDestacados($query)
    {
        return $query->where('destacado', true);
    }

    public function scopeVigentes($query)
    {
        return $query->where(function ($q) {

            // Eventos con fecha_fin
            $q->whereNotNull('fecha_fin')
            ->where('fecha_fin', '>=', now());

        })->orWhere(function ($q) {

            // Eventos de un solo día
            $q->whereNull('fecha_fin')
            ->where('fecha_inicio', '>=', now());

        });
    }

    // --- Helpers ---

    public function esPasado(): bool
    {
        $fin = $this->fecha_fin ?? $this->fecha_inicio;
        return $fin->isPast();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
