<?php

namespace App\Http\Requests;

use App\Models\TiposSeccionConversor;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTiposSeccionConversorRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('tipos_seccion_conversor_edit');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
            ],
            'eslistado'=> [
                'integer'
            ]
        ];
    }
}
