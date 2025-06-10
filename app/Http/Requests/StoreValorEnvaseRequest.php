<?php

namespace App\Http\Requests;

use App\Models\ValorEnvase;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreValorEnvaseRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('valor_envase_create');
    }

    public function rules()
    {
        return [
            'productor_id' => [
                'required',
                'integer',
            ],
            'valor' => [
                
                'required',
            ],
        ];
    }
}
