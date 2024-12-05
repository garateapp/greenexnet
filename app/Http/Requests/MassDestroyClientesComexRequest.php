<?php

namespace App\Http\Requests;

use App\Models\ClientesComex;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyClientesComexRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('clientes_comex_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:clientes_comexes,id',
        ];
    }
}
