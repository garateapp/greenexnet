<?php

namespace App\Http\Requests;

use App\Models\Embarque;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreEmbarqueRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('embarque_create');
    }

    public function rules()
    {
        return [
            'num_embarque' => [
                'string',
                'required',
            ],
            'id_cliente' => [
                'string',
                'nullable',
            ],
            'n_cliente' => [
                'string',
                'nullable',
            ],
            'semana' => [
                'string',
                'required',
            ],
            'planta_carga' => [
                'string',
                'nullable',
            ],
            'n_naviera' => [
                'string',
                'nullable',
            ],
            'nave' => [
                'string',
                'nullable',
            ],
            'num_contenedor' => [
                'string',
                'nullable',
            ],
            'especie' => [
                'string',
                'nullable',
            ],
            'variedad' => [
                'string',
                'nullable',
            ],
            'embalajes' => [
                'string',
                'nullable',
            ],
            'etiqueta' => [
                'string',
                'nullable',
            ],
            'cajas' => [
                'string',
                'nullable',
            ],
            'peso_neto' => [
                'string',
                'nullable',
            ],
            'puerto_embarque' => [
                'string',
                'nullable',
            ],
            'pais_destino' => [
                'string',
                'nullable',
            ],
            'puerto_destino' => [
                'string',
                'nullable',
            ],
            'mercado' => [
                'string',
                'nullable',
            ],
            'etd_estimado' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'eta_estimado' => [
                'string',
                'nullable',
            ],
            'fecha_zarpe_real' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'fecha_arribo_real' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'dias_transito_real' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'descargado' => [
                'string',
                'nullable',
            ],
            'retirado_full' => [
                'string',
                'nullable',
            ],
            'devuelto_vacio' => [
                'string',
                'nullable',
            ],
            'calificacion' => [
                'string',
                'nullable',
            ],
            'conexiones' => [
                'string',
                'nullable',
            ],
            'con_fecha_hora' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'num_pallets' => [
                'string',
                'nullable',
            ],
            'embalaje_std' => [
                'string',
                'nullable',
            ],
            'num_orden' => [
                'string',
                'nullable',
            ],
            'tipo_especie' => [
                'string',
                'nullable',
            ],
            [
                'total_pallets'=>[
                'string',
                'nullable',
                ]
                ],
            [
                'numero_reserva_agente_naviero'=>[
                'string',
                'nullable',
                ]
                ],
                [
                    'transporte'=>[
                    'string',
                    'nullable',
                    ]
                ]
        ];
    }
}
