<?php

namespace App\Http\Requests;

use App\Models\Etiquetum;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreEtiquetumRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('etiquetum_create');
    }

    public function rules()
    {
        return [
            'codigo' => [
                'string',
                'required',
            ],
            'nombre' => [
                'string',
                'required',
            ],
        ];
    }
}
