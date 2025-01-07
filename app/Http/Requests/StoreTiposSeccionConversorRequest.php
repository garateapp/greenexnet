<?php

namespace App\Http\Requests;

use App\Models\TiposSeccionConversor;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTiposSeccionConversorRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('tipos_seccion_conversor_create');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
            ],
            'eslistado' => [
                'integer'
            ],
        ];
    }
}
