<?php

namespace App\Http\Requests;

use App\Models\Material;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreMaterialRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('material_create');
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
            'costo_ult_oc' => [
                'numeric',
            ],
        ];
    }
}
