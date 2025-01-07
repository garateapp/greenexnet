<?php

namespace App\Http\Requests;

use App\Models\Puerto;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePuertoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('puerto_create');
    }

    public function rules()
    {
        return [
            'codigo' => [
                'string',
                'required',
            ],
            'nombre' => [
                'string',
                'required',
            ],
            'cap' => [
                'string',
                'nullable',
            ],
        ];
    }
}
