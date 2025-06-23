<?php

namespace App\Http\Requests;

use App\Models\Multiresiduo;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateMultiresiduoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('multiresiduo_edit');
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
