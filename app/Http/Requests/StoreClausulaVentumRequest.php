<?php

namespace App\Http\Requests;

use App\Models\ClausulaVentum;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreClausulaVentumRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('clausula_ventum_create');
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
