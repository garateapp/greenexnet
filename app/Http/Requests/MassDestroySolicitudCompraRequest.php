<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySolicitudCompraRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('solicitud_compra_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:solicitud_compras,id',
        ];
    }
}
