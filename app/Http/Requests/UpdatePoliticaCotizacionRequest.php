<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePoliticaCotizacionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('politica_cotizacion_edit');
    }

    public function rules()
    {
        return [
            'monto_min' => [
                'required',
                'numeric',
                'min:0',
            ],
            'monto_max' => [
                'nullable',
                'numeric',
                'gte:monto_min',
            ],
            'cotizaciones_requeridas' => [
                'required',
                'integer',
                'min:1',
                'max:3',
            ],
            'activo' => [
                'boolean',
            ],
        ];
    }
}
