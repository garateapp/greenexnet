<?php

namespace App\Http\Requests;

use App\Models\Moneda;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateMonedaRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('moneda_edit');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
            ],
            'codigo' => [
                'string',
                'required',
            ],
        ];
    }
}
