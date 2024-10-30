<?php

namespace App\Http\Requests;

use App\Models\Entidad;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreEntidadRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('entidad_create');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
            ],
            'rut' => [
                'string',
                'required',
            ],
            'tipo_id' => [
                'required',
                'integer',
            ],
            'direccion' => [
                'string',
                'nullable',
            ],
        ];
    }
}
