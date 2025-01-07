<?php

namespace App\Http\Requests;

use App\Models\Etiquetum;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateEtiquetumRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('etiquetum_edit');
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
