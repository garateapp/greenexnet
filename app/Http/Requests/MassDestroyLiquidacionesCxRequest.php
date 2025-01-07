<?php

namespace App\Http\Requests;

use App\Models\LiquidacionesCx;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyLiquidacionesCxRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('liquidaciones_cx_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:liquidaciones_cxes,id',
        ];
    }
}
