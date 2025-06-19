<?php

namespace App\Http\Requests;

use App\Models\EmisionBl;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateEmisionBlRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('emision_bl_edit');
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
