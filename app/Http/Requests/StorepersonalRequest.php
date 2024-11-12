<?php

namespace App\Http\Requests;

use App\Models\Personal;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePersonalRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('personal_create');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
            ],
            'codigo' => [
                'string',
                'nullable',
            ],
            'rut' => [
                'string',
                'required',
            ],
            'email' => [
                'string',
                'nullable',
            ],
            'telefono' => [
                'string',
                'nullable',
            ],
            'estado_id' => [
                'required',
                'integer',
            ],
            'entidad_id' => [
                'required',
                'integer',
            ],
            'foto' => [
                'string',
                'nullable'
            ],
        ];
    }
}
