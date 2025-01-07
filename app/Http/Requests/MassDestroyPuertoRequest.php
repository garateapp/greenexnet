<?php

namespace App\Http\Requests;

use App\Models\Puerto;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyPuertoRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('puerto_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:puertos,id',
        ];
    }
}
