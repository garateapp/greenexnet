<?php

namespace App\Http\Requests;

use App\Models\LiqCxCabecera;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateLiqCxCabeceraRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('liq_cx_cabecera_edit');
    }

    public function rules()
    {
        return [
            'instructivo' => [
                'string',
                'required',
            ],
            'cliente_id' => [
                'required',
                'integer',
            ],
            'nave_id' => [
                'integer',
                'nullable',
            ],
            'eta' => [
                'date_format:' . config('panel.date_format'),
                'nullable'
            ],
            'tasa_intercambio' => [
                'numeric',
                'required'
            ],
            'total_costo' => [
                'numeric'
            ],
            'total_bruto' => [
                'numeric',
            ],
            'total_neto' => [
                'numeric'
            ],

            'flete_exportadora'=>[
                'numeric'
            ],
            'tipo_transporte'=>[
                'string',
                'required'
            ],
            'factor_imp_destino'=>[
                'numeric',
                'required'
            ]
        ];
    }
}
