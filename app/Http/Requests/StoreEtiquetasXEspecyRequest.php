<?php

namespace App\Http\Requests;

use App\Models\EtiquetasXEspecy;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreEtiquetasXEspecyRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('etiquetas_x_especy_create');
    }

    public function rules()
    {
        return [
            'etiqueta_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
