<?php

namespace App\Http\Requests;

use App\Models\Funcione;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateFuncioneRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('funcione_edit');
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
