<?php

namespace App\Http\Requests;

use App\Models\Grupo;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateGrupoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('grupo_edit');
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
                'nullable',
            ],
            'conjunto_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
