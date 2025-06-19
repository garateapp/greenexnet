<?php

namespace App\Http\Requests;

use App\Models\InstructivoEmbarque;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateInstructivoEmbarqueRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('instructivo_embarque_edit');
    }

    public function rules()
    {
        return [
            'instructivo' => [
                'string',
                'required',
            ],
            'fecha' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'embarcador_id' => [
                'required',
                'integer',
            ],
            'agente_aduana_id' => [
                'required',
                'integer',
            ],
            'consignee_id' => [
                'required',
                'integer',
            ],
            'num_booking' => [
                'nullable',
                'string',
                
            ],
            'nave' => [
                'nullable',
                'string',
                
            ],
            'cut_off' => [
                'nullable',
                'string',
                
            ],
            'stacking_ini' => [
                'nullable',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'stacking_end' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'etd' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'eta' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'puerto_embarque_id' => [
                'nullable',
                'integer',
            ],
            'punto_de_entrada' => [
                'string',
                'nullable',
            ],
            'num_contenedor' => [
                'string',
                'nullable',
            ],
            'ventilacion' => [
                'string',
                'nullable',
            ],
            'tara_contenedor' => [
                'string',
                'nullable',
            ],
            'quest' => [
                'string',
                'nullable',
            ],
            'num_sello' => [
                'string',
                'nullable',
            ],
            'temperatura' => [
                'numeric',
                'nullable',
            ],
            'empresa_transportista' => [
                'string',
                'nullable',
            ],
            'conductor_id' => [
                'nullable',
                'integer',
            ],
            'rut_conductor' => [
                'string',
                'nullable',
            ],
            'ppu' => [
                'string',
                'nullable',
            ],
            'telefono' => [
                'string',
                'nullable',
            ],
            'direccion' => [
                'string',
                'required',
            ],
            'fecha_carga' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'hora_carga' => [
                'nullable',
                'date_format:' . config('panel.time_format'),
            ],
            'guia_despacho_dirigida' => [
                'string',
                'required',
            ],
            'planilla_sag_dirigida' => [
                'string',
                'required',
            ],
            'num_po' => [
                'string',
                'nullable',
            ],
            'emision_de_bl_id' => [
                'nullable',
                'integer',
            ],
            'clausula_de_venta_id' => [
                'nullable',
                'integer',
            ],
            'moneda_id' => [
                'nullable',
                'integer',
            ],
            'forma_de_pago_id' => [
                'nullable',
                'integer',
            ],
        ];
    }
}
