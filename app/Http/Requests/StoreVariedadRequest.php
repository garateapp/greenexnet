<?php

namespace App\Http\Requests;

use App\Models\Variedad;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreVariedadRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('variedad_create');
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
