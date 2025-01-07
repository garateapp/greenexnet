<?php

namespace App\Http\Requests;

use App\Models\LiqCosto;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreLiqCostoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('liq_costo_create');
    }

    public function rules()
    {
        return [
            'liq_cabecera_id' => [
                'required',
                'integer',
            ],
            'nombre_costo' => [
                'string',
                'required',
            ],
            'valor' => [
                'numeric',
                'required',
                'unique:liq_costos,valor',
            ],
        ];
    }
}
