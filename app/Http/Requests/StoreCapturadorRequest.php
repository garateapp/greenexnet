<?php

namespace App\Http\Requests;

use App\Models\Capturador;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCapturadorRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('capturador_create');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
            ],
            'modulo_id' => [
                'required',
                'integer',
            ],
            'funcion_id' => [
                'required',
                'integer',
            ],
            'activo' => [

            ],
        ];
    }
}
