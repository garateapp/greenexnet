<?php

namespace App\Http\Requests;

use App\Models\LiqCxCabecera;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyLiqCxCabeceraRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('liq_cx_cabecera_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:liq_cx_cabeceras,id',
        ];
    }
}
