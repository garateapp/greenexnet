<?php

namespace App\Http\Requests;

use App\Models\Tipoflete;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTipofleteRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('tipoflete_edit');
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
