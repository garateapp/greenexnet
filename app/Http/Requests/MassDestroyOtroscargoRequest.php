<?php

namespace App\Http\Requests;

use App\Models\Otroscargo;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyOtroscargoRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('otroscargo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:otroscargos,id',
        ];
    }
}
