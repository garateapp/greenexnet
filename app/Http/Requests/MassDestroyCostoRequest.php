<?php

namespace App\Http\Requests;

use App\Models\Costo;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyCostoRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('costo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:costos,id',
        ];
    }
}
