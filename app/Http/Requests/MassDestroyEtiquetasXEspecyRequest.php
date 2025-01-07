<?php

namespace App\Http\Requests;

use App\Models\EtiquetasXEspecy;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyEtiquetasXEspecyRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('etiquetas_x_especy_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:etiquetas_x_especies,id',
        ];
    }
}
