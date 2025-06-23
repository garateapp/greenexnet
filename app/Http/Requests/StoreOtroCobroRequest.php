<?php

namespace App\Http\Requests;

use App\Models\OtroCobro;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreOtroCobroRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('otro_cobro_create');
    }

    public function rules()
    {
        return [
            'productor_id' => [
                'required',
                'integer',
            ],
            'valor' => [
                'numeric',
                'required',
            ],
        ];
    }
}
