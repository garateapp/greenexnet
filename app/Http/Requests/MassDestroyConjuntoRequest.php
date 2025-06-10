<?php

namespace App\Http\Requests;

use App\Models\Conjunto;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyConjuntoRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('conjunto_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:conjuntos,id',
        ];
    }
}
