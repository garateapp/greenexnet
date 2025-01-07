<?php

namespace App\Http\Requests;

use App\Models\Familium;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateFamiliumRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('familium_edit');
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
