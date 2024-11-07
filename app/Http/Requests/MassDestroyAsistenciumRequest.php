<?php

namespace App\Http\Requests;

use App\Models\Asistencium;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyAsistenciumRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('asistencium_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:asistencia,id',
        ];
    }
}
