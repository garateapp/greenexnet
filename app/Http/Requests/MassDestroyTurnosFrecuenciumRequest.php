<?php

namespace App\Http\Requests;

use App\Models\TurnosFrecuencium;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTurnosFrecuenciumRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('turnos_frecuencium_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:turnos_frecuencia,id',
        ];
    }
}
