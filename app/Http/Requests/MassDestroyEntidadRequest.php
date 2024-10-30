<?php

namespace App\Http\Requests;

use App\Models\Entidad;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyEntidadRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('entidad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:entidads,id',
        ];
    }
}
