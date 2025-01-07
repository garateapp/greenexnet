<?php

namespace App\Http\Requests;

use App\Models\Familium;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreFamiliumRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('familium_create');
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
                'required',
            ],
        ];
    }
}
