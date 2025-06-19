<?php

namespace App\Http\Requests;

use App\Models\ClausulaVentum;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyClausulaVentumRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('clausula_ventum_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:clausula_venta,id',
        ];
    }
}
