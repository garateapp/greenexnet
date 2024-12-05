<?php

namespace App\Http\Requests;

use App\Models\ClientesComex;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateClientesComexRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('clientes_comex_edit');
    }

    public function rules()
    {
        return [
            'codigo_cliente' => [
                'string',
                'required',
            ],
            'nombre_empresa' => [
                'string',
                'required',
            ],
            'nombre_fantasia' => [
                'string',
                'required',
            ],
        ];
    }
}
