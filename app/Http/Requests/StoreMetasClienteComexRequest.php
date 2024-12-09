<?php

namespace App\Http\Requests;

use App\Models\MetasClienteComex;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreMetasClienteComexRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('metas_cliente_comex_create');
    }

    public function rules()
    {
        return [
            'clientecomex_id' => [
                'required',
                'integer',
            ],
            'cantidad' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'semana' => [
                'required',
                'integer',
                'min:1',
                'max:53',
            ],
            'anno' => [
                'required',
                'integer',
                'min:2024',
                'max:2030',
            ]
        ];
    }
}
