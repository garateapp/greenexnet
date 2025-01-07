<?php

namespace App\Http\Requests;

use App\Models\Especy;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyEspecyRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('especy_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:especies,id',
        ];
    }
}
