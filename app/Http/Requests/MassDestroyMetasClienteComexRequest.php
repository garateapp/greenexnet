<?php

namespace App\Http\Requests;

use App\Models\MetasClienteComex;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyMetasClienteComexRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('metas_cliente_comex_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:metas_cliente_comexes,id',
        ];
    }
}
