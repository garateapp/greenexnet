<?php

namespace App\Http\Requests;

use App\Models\PesoEmbalaje;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePesoEmbalajeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('peso_embalaje_edit');
    }

    public function rules()
    {
        return [
            'especie_id' => [
                'required',
                'integer',
            ],
            'etiqueta_id' => [
                'required',
                'integer',
            ],
            'embalajes' => [
                'string',
                'nullable',
            ],
            'peso_neto' => [
                'numeric',
            ],
            'peso_bruto' => [
                'numeric',
                'required',
            ],
        ];
    }
}
