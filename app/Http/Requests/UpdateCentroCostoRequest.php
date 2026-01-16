<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCentroCostoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('centro_costo_edit');
    }

    public function rules()
    {
        return [
            'entidad_id' => [
                'nullable',
                'integer',
                'exists:entidads,id',
            ],
            'id_centrocosto' => [
                'nullable',
                'string',
                'max:255',
            ],
            'c_centrocosto' => [
                'nullable',
                'string',
                'max:255',
            ],
            'n_centrocosto' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }
}
