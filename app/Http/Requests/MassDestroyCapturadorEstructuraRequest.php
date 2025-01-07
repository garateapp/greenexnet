<?php

namespace App\Http\Requests;

use App\Models\CapturadorEstructura;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyCapturadorEstructuraRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('capturador_estructura_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:capturador_estructuras,id',
        ];
    }
}
