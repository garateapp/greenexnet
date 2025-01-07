<?php

namespace App\Http\Requests;

use App\Models\Capturador;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCapturadorRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('capturador_edit');
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
                'required',
            ],
        ];
    }
}
