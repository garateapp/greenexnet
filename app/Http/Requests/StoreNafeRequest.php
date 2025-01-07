<?php

namespace App\Http\Requests;

use App\Models\Nafe;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreNafeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('nafe_create');
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
        ];
    }
}
