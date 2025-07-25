<?php

namespace App\Http\Requests;

use App\Models\ValorEnvase;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyValorEnvaseRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('valor_envase_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:valor_envases,id',
        ];
    }
}
