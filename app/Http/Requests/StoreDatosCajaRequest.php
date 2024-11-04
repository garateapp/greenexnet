<?php

namespace App\Http\Requests;

use App\Models\DatosCaja;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreDatosCajaRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('datos_caja_create');
    }

    public function rules()
    {
        return [
            'proceso' => [
                'string',

            ],
            'fecha_produccion' => [

                'date_format:' . config('panel.date_format'),
            ],
            'turno' => [
                'string',

            ],
            'cod_linea' => [
                'string',

            ],
            'cat' => [
                'string',
                'nullable',
            ],
            'variedad_real' => [
                'string',
                'nullable',
            ],
            'variedad_timbrada' => [
                'string',
                'nullable',
            ],
            'salida' => [
                'string',
                'nullable',
            ],
            'marca' => [
                'string',
                'nullable',
            ],
            'productor_real' => [
                'string',
                'nullable',
            ],
            'especie' => [
                'string',
                'nullable',
            ],
            'cod_caja' => [
                'string',
                'nullable',
            ],
            'cod_confeccion' => [
                'string',
                'nullable',
            ],
            'calibre_timbrado' => [
                'string',
                'nullable',
            ],
            'peso_timbrado' => [
                'string',
                'nullable',
            ],
            'lote' => [
                'string',
                'nullable',
            ],
            'nuevo_lote' => [
                'string',
                'nullable',
            ],
            'codigo_qr' => [
                'string',
                'nullable',
            ],
        ];
    }
}
