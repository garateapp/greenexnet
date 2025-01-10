<?php

namespace App\Http\Requests;

use App\Models\LiquidacionesCx;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreLiquidacionesCxRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('liquidaciones_cx_create');
    }

    public function rules()
    {
        return [
            'contenedor' => [
                'string',
                'nullable',
            ],
            'eta' => [
                //'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'variedad_id' => [
                'required',
                'string',
            ],
            'pallet' => [
                'string',
                'nullable',
            ],
            'calibre' => [
                'string',
                'nullable',
            ],
            'cantidad' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'fecha_venta' => [
                //'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'ventas' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'precio_unitario' => [
                'numeric',
            ],
            'monto_rmb' => [
                'numeric',
            ],
            'liqcabecera_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
