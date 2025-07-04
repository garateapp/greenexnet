<?php

namespace App\Http\Requests;

use App\Models\BaseRecibidor;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateBaseRecibidorRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('base_recibidor_edit');
    }

    public function rules()
    {
        return [
            'cliente_id' => [
                'required',
                'integer',
            ],
            'codigo' => [
                'string',
                'required',
            ],
            'rut_sistema' => [
                'string',
                'nullable',
            ],
        ];
    }
}
