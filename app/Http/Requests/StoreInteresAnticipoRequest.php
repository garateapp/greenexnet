<?php

namespace App\Http\Requests;

use App\Models\InteresAnticipo;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreInteresAnticipoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('interes_anticipo_create');
    }

    public function rules()
    {
        return [
            'anticipo_id' => [
                'required',
                'integer',
            ],
            'valor' => [              
                'required',
            ],
        ];
    }
}
