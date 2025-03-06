<?php

namespace App\Http\Requests;

use App\Models\HandPack;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreHandPackRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('hand_pack_create');
    }

    public function rules()
    {
        return [
            'rut' => [
                'string',
                'required',
            ],
            'fecha' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'embalaje' => [
                'required',
            ],
            'guuid' => [
                'string',
                'required',
            ],
        ];
    }
}
