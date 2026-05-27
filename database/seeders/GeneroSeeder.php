<?php

namespace Database\Seeders;

use App\Models\Disciplina;
use App\Models\Genero;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GeneroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $generos = [
            'Música' => [
                'Rock', 'Blues', 'Jazz', 'Tango', 'Folklore', 'Pop',
                'Cumbia', 'Clásica', 'Metal', 'Electrónica', 'Hip Hop',
                'Reggae', 'Punk', 'Indie', 'Trap', 'Tropical', 'Ska',
            ],
            'Artes Plásticas' => [
                'Pintura', 'Escultura', 'Dibujo', 'Grabado',
                'Arte Digital', 'Muralismo', 'Ilustración',
            ],
            'Danza' => [
                'Tango', 'Folklore', 'Ballet', 'Contemporánea',
                'Flamenco', 'Hip Hop', 'Jazz', 'Salsa',
            ],
            'Teatro' => [
                'Drama', 'Comedia', 'Musical', 'Infantil',
                'Impro', 'Clown', 'Títeres', 'Teatro Físico',
            ],
            'Literatura' => [
                'Poesía', 'Narrativa', 'Cuento', 'Novela',
                'Crónica', 'Dramaturgia', 'Literatura Infantil',
            ],
            'Artesanías' => [
                'Cerámica', 'Textil', 'Cuero', 'Madera',
                'Joyería', 'Vidrio', 'Cestería',
            ],
            'Productor/Gestor' => [
                'Producción Musical', 'Gestión Cultural',
                'Producción Audiovisual', 'Producción Teatral',
            ],
            'Audiovisual' => [
                'Cine', 'Cortometraje', 'Documental',
                'Fotografía', 'Videoclip', 'Animación',
            ],
        ];

        foreach ($generos as $disciplinaNombre => $lista) {
            $disciplina = Disciplina::where('nombre', $disciplinaNombre)->first();

            foreach ($lista as $nombre) {
                Genero::create([
                    'disciplina_id' => $disciplina->id,
                    'nombre'        => $nombre,
                    'slug'          => Str::slug($nombre . '-' . $disciplina->slug),
                ]);
            }
        }
    }
}
