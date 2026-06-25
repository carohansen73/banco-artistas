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
                'Blues', 'Cumbia', 'Clásica','Electrónica',
                'Folklore', 'Hip Hop', 'Indie', 'Jazz',
                'Metal', 'Tango', 'Pop', 'Punk',  'Reggae',
                'Rock', 'Ska',  'Trap', 'Tropical',
            ],
            'Artes Plásticas' => [
                'Pintura', 'Escultura', 'Dibujo', 'Grabado',
                'Arte Digital', 'Muralismo', 'Ilustración',
            ],
            'Danza' => [
                'Ballet', 'Contemporánea', 'Danza Árabe',
                'Flamenco', 'Folklore', 'Hip Hop', 'Salsa',
                'Tango', 'Urbano', 'Zumba',
            ],
            'Diseño' => [
                'Diseño de indumentaria', 'Diseño Gráfico',
                'Diseño Industrial', 'Diseño Web / UX-UI',
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
