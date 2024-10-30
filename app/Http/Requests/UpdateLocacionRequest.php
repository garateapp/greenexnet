<?php

namespace App\Http\Requests;

use App\Models\Locacion;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateLocacionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('locacion_edit');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'nullable',
            ],
            'cantidad_personal' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'locacion_padre_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
