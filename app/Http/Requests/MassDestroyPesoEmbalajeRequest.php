<?php

namespace App\Http\Requests;

use App\Models\PesoEmbalaje;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyPesoEmbalajeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('peso_embalaje_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:peso_embalajes,id',
        ];
    }
}
