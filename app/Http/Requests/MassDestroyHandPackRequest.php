<?php

namespace App\Http\Requests;

use App\Models\HandPack;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyHandPackRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('hand_pack_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:hand_packs,id',
        ];
    }
}
