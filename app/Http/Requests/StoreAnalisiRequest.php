<?php

namespace App\Http\Requests;

use App\Models\Analisi;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAnalisiRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('analisi_create');
    }

    public function rules()
    {
        return [
            'temporada' => [
                'string',
                'required',
            ],
            'especie' => [
                'string',
                'required',
            ],
            'csg' => [
                'string',
                'required',
            ],
            'valor' => [
                'string',
                'required',
            ],
        ];
    }
}
