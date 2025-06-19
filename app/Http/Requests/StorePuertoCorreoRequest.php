<?php

namespace App\Http\Requests;

use App\Models\PuertoCorreo;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePuertoCorreoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('puerto_correo_create');
    }

    public function rules()
    {
        return [
            'puerto_embarque_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
