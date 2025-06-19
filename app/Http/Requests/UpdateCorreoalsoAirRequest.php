<?php

namespace App\Http\Requests;

use App\Models\CorreoalsoAir;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCorreoalsoAirRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('correoalso_air_edit');
    }

    public function rules()
    {
        return [
            'cliente_id' => [
                'required',
                'integer',
            ],
            'transporte' => [
                'required',
            ],
        ];
    }
}
