<?php

namespace App\Http\Requests;

use App\Models\Configuracion;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyConfiguracionRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('configuracion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:configuracions,id',
        ];
    }
}
