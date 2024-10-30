<?php

namespace App\Http\Requests;

use App\Models\Estado;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateEstadoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('estado_edit');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
                'unique:estados,nombre,' . request()->route('estado')->id,
            ],
        ];
    }
}
