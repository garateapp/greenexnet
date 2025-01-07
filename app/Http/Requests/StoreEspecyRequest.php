<?php

namespace App\Http\Requests;

use App\Models\Especy;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreEspecyRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('especy_create');
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
            'id_pro_p_familias'=>[
                'integer',
                'required',
            ],
        ];
    }
}
