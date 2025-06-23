<?php

namespace App\Http\Requests;

use App\Models\Multiresiduo;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreMultiresiduoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('multiresiduo_create');
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
