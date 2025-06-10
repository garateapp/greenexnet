<?php

namespace App\Http\Requests;

use App\Models\Proceso;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreProcesoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('proceso_create');
    }

    public function rules()
    {
        return [
            'productor_id' => [
                'required',
                'integer',
            ],
           
            'variedad' => [
                'string',
                'required',
            ],
            'categoria' => [
                'string',
                'required',
            ],
            'etiqueta' => [
                'string',
                'nullable',
            ],
            'calibre' => [
                'string',
                'required',
            ],
            'color' => [
                'string',
                'nullable',
            ],
            'total_kilos' => [
                'numeric',
                'required',
            ],
            'etd_week' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'eta_week' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'resultado_kilo' => [
                'string',
                'nullable',
            ],
            'resultado_total' => [
                'numeric',
            ],
            'precio_comercial' => [
                'numeric',
            ],
            'total_comercial' => [
                'numeric',
            ],
            'costo_comercial' => [
                'numeric',
            ],
        ];
    }
}
