<?php

namespace App\Http\Requests;

use App\Models\ValorDolar;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyValorDolarRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('valor_dolar_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:valor_dolars,id',
        ];
    }
}
