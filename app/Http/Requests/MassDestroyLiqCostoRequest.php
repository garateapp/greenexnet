<?php

namespace App\Http\Requests;

use App\Models\LiqCosto;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyLiqCostoRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('liq_costo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:liq_costos,id',
        ];
    }
}
