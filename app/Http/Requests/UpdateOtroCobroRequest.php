<?php

namespace App\Http\Requests;

use App\Models\OtroCobro;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateOtroCobroRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('otro_cobro_edit');
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
