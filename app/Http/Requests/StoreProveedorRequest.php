<?php

namespace App\Http\Requests;

use App\Models\Proveedor;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreProveedorRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('proveedor_create');
    }

    public function rules()
    {
        return [
            'rut' => [
                'string',
                'required',
            ],
            'cobro' => [
                'string',
                'required',
            ],
            'nombre_simple' => [
                'string',
                'nullable',
            ],
            'razon_social' => [
                'string',
                'required',
            ],
        ];
    }
}
