<?php

namespace App\Http\Requests;

use App\Models\Nafe;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyNafeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('nafe_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:naves,id',
        ];
    }
}
