<?php

namespace App\Http\Requests;

use App\Models\Modulo;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyModuloRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('modulo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:modulos,id',
        ];
    }
}
