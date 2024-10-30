<?php

namespace App\Http\Requests;

use App\Models\FrecuenciaTurno;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyFrecuenciaTurnoRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('frecuencia_turno_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:frecuencia_turnos,id',
        ];
    }
}
