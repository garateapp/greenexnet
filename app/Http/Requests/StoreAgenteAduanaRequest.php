<?php

namespace App\Http\Requests;

use App\Models\AgenteAduana;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAgenteAduanaRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('agente_aduana_create');
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
            'codigo' => [
                'string',
                'nullable',
            ],
            'direccion' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
            ],
            'telefono' => [
                'string',
                'nullable',
            ],
        ];
    }
}
