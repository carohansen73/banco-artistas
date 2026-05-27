<?php

namespace Database\Seeders;

use App\Models\Disciplina;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DisciplinaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $disciplinas = [
            'Artes Plásticas',
            'Audiovisual',
            'Artesanías',
            'Danza',
            'Literatura',
            'Música',
            'Productor/Gestor',
            'Teatro',
        ];

        foreach ($disciplinas as $nombre) {
            Disciplina::create([
                'nombre'              => $nombre,
                'slug'                => Str::slug($nombre),
                'pendiente_revision'  => false,
            ]);
        }
    }
}
