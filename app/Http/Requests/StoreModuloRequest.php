<?php

namespace App\Http\Requests;

use App\Models\Modulo;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreModuloRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('modulo_create');
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
