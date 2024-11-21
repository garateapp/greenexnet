<?php

namespace App\Http\Requests;

use App\Models\RecibeMaster;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreRecibeMasterRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('recibe_master_create');
    }

    public function rules()
    {
        return [
            'especie' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'exportador' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'partida' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'estado' => [],
            'cod_central' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'cod_productor' => [
                'string',
                'required',
            ],
            'nro_guia_despacho' => [
                'string',
                'required',
            ],
            'fecha_recepcion' => [
                'required',

            ],
            'fecha_cosecha' => [
                'required',

            ],
            'cod_variedad' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'estiba_camion' => [],
            'esponjas_cloradas' => [],
            'nro_bandeja' => [],
            'hora_llegada' => [
                'required',

            ],
            'kilo_muestra' => [],
            'kilo_neto' => [
                'numeric',
                'required',
            ],
            'temp_ingreso' => [],
            'temp_salida' => [],
            'lote' => [
                'string',
                'required',
            ],
            'huerto' => [
                'string',
                'required',
            ],
            'hidro' => [
                'string',
                'required',
            ],
            'fecha_envio' => [
                'string',
                'nullable',
            ],
            'respuesta_envio' => [
                'string',
                'nullable',
            ],
        ];
    }
}
