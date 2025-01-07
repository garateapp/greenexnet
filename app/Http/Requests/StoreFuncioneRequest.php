<?php

namespace App\Http\Requests;

use App\Models\Funcione;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreFuncioneRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('funcione_create');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
            ],
        ];
    }
}
