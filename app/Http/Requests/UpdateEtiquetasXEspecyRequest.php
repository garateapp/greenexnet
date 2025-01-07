<?php

namespace App\Http\Requests;

use App\Models\EtiquetasXEspecy;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateEtiquetasXEspecyRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('etiquetas_x_especy_edit');
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
