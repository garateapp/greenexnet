<?php

namespace App\Http\Requests;

use App\Models\EmisionBl;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreEmisionBlRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('emision_bl_create');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'nullable',
            ],
        ];
    }
}
