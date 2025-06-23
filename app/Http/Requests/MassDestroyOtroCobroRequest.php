<?php

namespace App\Http\Requests;

use App\Models\OtroCobro;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyOtroCobroRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('otro_cobro_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:otro_cobros,id',
        ];
    }
}
