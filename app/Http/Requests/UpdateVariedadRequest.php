<?php

namespace App\Http\Requests;

use App\Models\Variedad;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateVariedadRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('variedad_edit');
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
            'especie_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
