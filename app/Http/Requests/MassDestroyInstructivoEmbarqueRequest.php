<?php

namespace App\Http\Requests;

use App\Models\InstructivoEmbarque;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyInstructivoEmbarqueRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('instructivo_embarque_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:instructivo_embarques,id',
        ];
    }
}
