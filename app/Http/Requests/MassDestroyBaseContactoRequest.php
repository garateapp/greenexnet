<?php

namespace App\Http\Requests;

use App\Models\BaseContacto;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyBaseContactoRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('base_contacto_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:base_contactos,id',
        ];
    }
}
