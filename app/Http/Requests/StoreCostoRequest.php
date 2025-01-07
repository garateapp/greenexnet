<?php

namespace App\Http\Requests;

use App\Models\Costo;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCostoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('costo_create');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
            ],
            'valor_x_defecto' => [
                'string',
                'nullable',
            ],
        ];
    }
}
