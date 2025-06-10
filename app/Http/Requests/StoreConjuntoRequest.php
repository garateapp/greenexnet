<?php

namespace App\Http\Requests;

use App\Models\Conjunto;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreConjuntoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('conjunto_create');
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
