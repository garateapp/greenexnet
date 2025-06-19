<?php

namespace App\Http\Requests;

use App\Models\ModVentum;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreModVentumRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('mod_ventum_create');
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
