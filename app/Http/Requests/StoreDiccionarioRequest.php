<?php

namespace App\Http\Requests;

use App\Models\Diccionario;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreDiccionarioRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('diccionario_create');
    }

    public function rules()
    {
        return [
            'variable' => [
                'string',
                'required',
            ],
            'valor' => [
                'string',
                'required',
            ],
            'tipo' => [
                'required',
            ],
        ];
    }
}
