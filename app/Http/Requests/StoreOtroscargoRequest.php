<?php

namespace App\Http\Requests;

use App\Models\Otroscargo;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreOtroscargoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('otroscargo_create');
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
