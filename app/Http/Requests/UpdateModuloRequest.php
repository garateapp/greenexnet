<?php

namespace App\Http\Requests;

use App\Models\Modulo;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateModuloRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('modulo_edit');
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
