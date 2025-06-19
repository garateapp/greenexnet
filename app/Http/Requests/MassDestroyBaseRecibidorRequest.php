<?php

namespace App\Http\Requests;

use App\Models\BaseRecibidor;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyBaseRecibidorRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('base_recibidor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:base_recibidors,id',
        ];
    }
}
