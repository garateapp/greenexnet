<?php

namespace App\Http\Requests;

use App\Models\EmisionBl;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyEmisionBlRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('emision_bl_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:emision_bls,id',
        ];
    }
}
