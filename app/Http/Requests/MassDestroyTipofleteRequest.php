<?php

namespace App\Http\Requests;

use App\Models\Tipoflete;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTipofleteRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('tipoflete_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:tipofletes,id',
        ];
    }
}
