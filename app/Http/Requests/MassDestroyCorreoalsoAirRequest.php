<?php

namespace App\Http\Requests;

use App\Models\CorreoalsoAir;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyCorreoalsoAirRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('correoalso_air_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:correoalso_airs,id',
        ];
    }
}
