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
                'required',
                'integer',
            ],
            'eta' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'tasa_intercambio' => [
                'numeric',
                'required',
            ],
            'total_costo' => [
                'numeric',
                'required',
            ],
            'total_bruto' => [
                'numeric',
            ],
            'total_neto' => [
                'numeric',
                'required',
            ],
        ];
    }
}
