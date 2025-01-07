<?php

namespace App\Http\Requests;

use App\Models\Configuracion;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateConfiguracionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('configuracion_edit');
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
        ];
    }
}
