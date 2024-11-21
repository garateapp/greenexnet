<?php

namespace App\Http\Requests;

use App\Models\RecibeMaster;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyRecibeMasterRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('recibe_master_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:recibe_masters,id',
        ];
    }
}
