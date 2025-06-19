<?php

namespace App\Http\Requests;

use App\Models\PuertoCorreo;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePuertoCorreoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('puerto_correo_edit');
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
