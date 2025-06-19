<?php

namespace App\Http\Requests;

use App\Models\PuertoCorreo;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyPuertoCorreoRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('puerto_correo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:puerto_correos,id',
        ];
    }
}
