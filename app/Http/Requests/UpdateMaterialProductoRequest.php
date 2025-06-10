<?php

namespace App\Http\Requests;

use App\Models\MaterialProducto;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateMaterialProductoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('material_producto_edit');
    }

    public function rules()
    {
        return [
            'embalaje_id' => [
                'required',
                'integer',
            ],
            'material_id' => [
                'required',
                'integer',
            ],
            'unidadxcaja' => [
                'numeric',
                'required',
            ],
            'unidadxpallet' => [
                'numeric',
                'required',
            ],
            'costoxcajaclp' => [
                'numeric',
                'required',
            ],
            'costoxpallet_clp' => [
                'numeric',
                'required',
            ],
            'costoxcaja_usd' => [
                'numeric',
                'required',
            ],
            'costoxpallet_usd' => [
                'string',
                'required',
            ],
        ];
    }
}
