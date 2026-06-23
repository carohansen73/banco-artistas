<?php

namespace App\Http\Controllers;


use App\Models\Disciplina;


class DisciplinaController extends Controller
{

    public function generos(Disciplina $disciplina)
    {
        return $disciplina->generos()->orderBy('nombre')->get(['id', 'nombre']);
    }
}
