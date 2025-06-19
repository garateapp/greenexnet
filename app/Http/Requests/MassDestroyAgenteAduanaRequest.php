<?php

namespace App\Http\Requests;

use App\Models\AgenteAduana;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyAgenteAduanaRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('agente_aduana_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:agente_aduanas,id',
        ];
    }
}
