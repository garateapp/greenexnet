<?php

namespace App\Http\Requests;

use App\Models\Embarcador;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyEmbarcadorRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('embarcador_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:embarcadors,id',
        ];
    }
}
