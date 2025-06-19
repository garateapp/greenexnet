<?php

namespace App\Http\Requests;

use App\Models\Chofer;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyChoferRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('chofer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:chofers,id',
        ];
    }
}
