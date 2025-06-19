<?php

namespace App\Http\Requests;

use App\Models\ModVentum;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateModVentumRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('mod_ventum_edit');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
            ],
        ];
    }
}
