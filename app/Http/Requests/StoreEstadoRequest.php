<?php

namespace App\Http\Requests;

use App\Models\Estado;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreEstadoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('estado_create');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
                'unique:estados',
            ],
        ];
    }
}
