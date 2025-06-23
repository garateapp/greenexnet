<?php

namespace App\Http\Requests;

use App\Models\Bonificacion;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateBonificacionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('bonificacion_edit');
    }

    public function rules()
    {
        return [
            'valor' => [
                'numeric',
            ],
        ];
    }
}
