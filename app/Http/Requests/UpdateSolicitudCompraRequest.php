<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSolicitudCompraRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('solicitud_compra_edit');
    }

    public function rules()
    {
        return [
            'titulo' => [
                'required',
                'string',
                'max:255',
            ],
            'descripcion' => [
                'nullable',
                'string',
            ],
            'monto_estimado' => [
                'required',
                'numeric',
                'min:0',
            ],
            'cotizaciones_por_adquisiciones' => [
                'boolean',
            ],
            'fecha_requerida' => [
                'nullable',
                'date_format:' . config('panel.date_format'),
            ],
        ];
    }
}
