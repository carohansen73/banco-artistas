<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'artista_id',
        'tipo',
        'url',
        'titulo',
        'orden',
    ];

    public function artista()
    {
        return $this->belongsTo(Artista::class);
    }

    // Devuelve el ID del embed según la URL
    public function getEmbedUrlAttribute(): string
    {
        if ($this->tipo === 'audio_link') {
            // https://open.spotify.com/track/ID → embed
            preg_match('/spotify\.com\/(track|album|playlist)\/([a-zA-Z0-9]+)/', $this->url, $m);
            return isset($m[2])
                ? "https://open.spotify.com/embed/{$m[1]}/{$m[2]}"
                : $this->url;
        }

        if ($this->tipo === 'video_link') {
            // https://youtu.be/ID o https://youtube.com/watch?v=ID
            preg_match('/(?:youtu\.be\/|watch\?v=)([a-zA-Z0-9_-]+)/', $this->url, $m);
            return isset($m[1])
                ? "https://www.youtube.com/embed/{$m[1]}"
                : $this->url;
        }

        return $this->url;
    }
}
