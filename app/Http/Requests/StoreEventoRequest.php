<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventoRequest extends FormRequest
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
            'nombre'                  => 'required|string|max:255',
            'descripcion'             => 'nullable|string',
            'fecha_inicio'            => 'required|date',
            'fecha_fin'               => 'nullable|date|after_or_equal:fecha_inicio',
            'lugar'                   => 'required|string|max:255',
            'direccion'               => 'nullable|string|max:255',
            'ciudad'                  => 'nullable|string|max:100',
            'imagen_portada'          => 'nullable|image|max:2048',
            'link_entradas'           => 'nullable|url|max:255',
            'link_externo'            => 'nullable|url|max:255',
            'artistas_ids'            => 'required|array|min:1',
            'artistas_ids.*'          => 'integer|exists:artistas,id',
        ];
    }

     public function messages()
    {
        return [
            'artistas_ids.required' => 'Debe seleccionar al menos un perfil de artista',
            'artistas_ids.min'      => 'Debe seleccionar al menos un perfil de artista',

            'artistas_ids.*.integer' => 'Perfil de artista inválido',
            'artistas_ids.*.exists'  => 'Perfil de artista inválido',
        ];
    }
}
