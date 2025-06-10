<?php

namespace App\Http\Requests;

use App\Models\Productor;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateProductorRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('productor_edit');
    }

    public function rules()
    {
        return [
            'rut' => [
                'string',
                'required',
            ],
            'nombre' => [
                'string',
                'required',
            ],
            'grupo_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
