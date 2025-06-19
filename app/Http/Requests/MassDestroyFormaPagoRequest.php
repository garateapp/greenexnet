<?php

namespace App\Http\Requests;

use App\Models\FormaPago;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyFormaPagoRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('forma_pago_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:forma_pagos,id',
        ];
    }
}
