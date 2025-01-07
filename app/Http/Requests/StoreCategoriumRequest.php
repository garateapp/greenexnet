<?php

namespace App\Http\Requests;

use App\Models\Categorium;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCategoriumRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('categorium_create');
    }

    public function rules()
    {
        return [
            'codigo' => [
                'string',
                'required',
            ],
            'nombre' => [
                'string',
                'required',
            ],
            'tipo' => [
                'string',
                'required',
            ],
        ];
    }
}
