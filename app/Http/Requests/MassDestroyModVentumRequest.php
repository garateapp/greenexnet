<?php

namespace App\Http\Requests;

use App\Models\ModVentum;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyModVentumRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('mod_ventum_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:mod_venta,id',
        ];
    }
}
