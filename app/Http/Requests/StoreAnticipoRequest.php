<?php

namespace App\Http\Requests;

use App\Models\Anticipo;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAnticipoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('anticipo_create');
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
            'num_docto' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'fecha_documento' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
        ];
    }
}
