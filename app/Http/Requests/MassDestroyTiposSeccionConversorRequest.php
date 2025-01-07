<?php

namespace App\Http\Requests;

use App\Models\TiposSeccionConversor;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTiposSeccionConversorRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('tipos_seccion_conversor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:tipos_seccion_conversors,id',
        ];
    }
}
