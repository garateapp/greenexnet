<?php

namespace App\Http\Requests;

use App\Models\Naviera;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreNavieraRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('naviera_create');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
            ],
        ];
    }
}
