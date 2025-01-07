<?php

namespace App\Http\Requests;

use App\Models\Funcione;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyFuncioneRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('funcione_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:funciones,id',
        ];
    }
}
