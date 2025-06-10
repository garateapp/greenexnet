<?php

namespace App\Http\Requests;

use App\Models\Conjunto;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateConjuntoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('conjunto_edit');
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
