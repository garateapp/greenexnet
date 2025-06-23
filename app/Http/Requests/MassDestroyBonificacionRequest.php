<?php

namespace App\Http\Requests;

use App\Models\Bonificacion;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyBonificacionRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('bonificacion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:bonificacions,id',
        ];
    }
}
