<?php

namespace Database\Factories;

use App\Models\Artista;
use App\Models\Disciplina;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Artista>
 */
class ArtistaFactory extends Factory
{
    protected $model = Artista::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'               => User::factory(),
            'disciplina_id'         => Disciplina::factory(),
            'nombre_artistico'      => $this->faker->name(),
            'localidad'             => $this->faker->city(),
            'slug'                  => Str::slug($this->faker->unique()->words(2, true)) . '-' . uniqid(),
            'descripcion_actividad' => $this->faker->paragraph(),
            'anio_inicio'           => $this->faker->year(),
            'tiene_formacion'       => $this->faker->boolean(),
            'detalle_formacion'     => $this->faker->paragraph(),
            'tiene_documentacion'   => $this->faker->boolean(),
            'acepta_difusion'       => $this->faker->boolean(),
            'visible'               => false,
            'integrantes'           => null,
        ];
    }
}
