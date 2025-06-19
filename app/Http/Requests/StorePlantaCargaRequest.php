<?php

namespace App\Http\Requests;

use App\Models\PlantaCarga;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePlantaCargaRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('planta_carga_create');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
            ],
            'direccion' => [
                'string',
                'nullable',
            ],
            'id_fx' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
