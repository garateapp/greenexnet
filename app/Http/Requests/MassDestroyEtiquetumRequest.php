<?php

namespace App\Http\Requests;

use App\Models\Etiquetum;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyEtiquetumRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('etiquetum_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:etiqueta,id',
        ];
    }
}
