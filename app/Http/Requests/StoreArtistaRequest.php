<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreArtistaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'disciplina_id'         => 'required|exists:disciplinas,id',
            'nombre_artistico'      => 'required|string|max:255',
            'localidad'             => 'required|string|max:255',
            'telefono'              => 'required|string|max:50',
            'domicilio'             => 'nullable|string|max:255',
            'rol_proyecto'          => 'nullable|string|max:100',
            'generos'               => 'nullable|array',
            'generos.*'             => 'exists:generos,id',
            'descripcion_actividad' => 'required|string',
            'integrantes'           => 'nullable|array',
            'integrantes.*'         => ['nullable', 'string', 'max:100'],
            'tiene_formacion'       => 'required|boolean',
            'detalle_formacion'     => 'nullable|string',
            'anio_inicio'           => 'required|integer|min:1900|max:' . date('Y'),
            'tiene_documentacion'   => 'required|boolean',
            'acepta_difusion'       => 'required|boolean',
            'img_perfil'            => 'nullable|image|max:5120',

        ];
    }
}
