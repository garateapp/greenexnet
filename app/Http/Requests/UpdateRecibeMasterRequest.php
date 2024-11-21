<?php

namespace App\Http\Requests;

use App\Models\RecibeMaster;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateRecibeMasterRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('recibe_master_edit');
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
            'estado' => [
                'required',
            ],
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
                'date_format:' . config('panel.date_format'),
            ],
            'fecha_cosecha' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'cod_variedad' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'estiba_camion' => [
                'required',
            ],
            'esponjas_cloradas' => [
                'required',
            ],
            'nro_bandeja' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'hora_llegada' => [
                'required',
                'date_format:' . config('panel.time_format'),
            ],
            'kilo_muestra' => [
                'numeric',
                'required',
            ],
            'kilo_neto' => [
                'numeric',
                'required',
            ],
            'temp_ingreso' => [
                'numeric',
                'required',
            ],
            'temp_salida' => [
                'numeric',
                'required',
            ],
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
