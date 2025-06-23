<?php

namespace App\Http\Requests;

use App\Models\Bonificacion;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreBonificacionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('bonificacion_create');
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
