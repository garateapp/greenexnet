<?php

namespace App\Http\Requests;

use App\Models\Chofer;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreChoferRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('chofer_create');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
            ],
            'rut' => [
                'string',
                'nullable',
            ],
            'telefono' => [
                'string',
                'nullable',
            ],
            'patente' => [
                'string',
                'nullable',
            ],
        ];
    }
}
