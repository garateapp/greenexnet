<?php

namespace App\Http\Requests;

use App\Models\ValorFlete;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateValorFleteRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('valor_flete_edit');
    }

    public function rules()
    {
        return [
            'condicion' => [
                'numeric',
                'min:0',
                'max:1',
            ],
            'productor_id' => [
                'required',
                'integer',
            ],
            'valor' => [
                'numeric',
            ],
        ];
    }
}
