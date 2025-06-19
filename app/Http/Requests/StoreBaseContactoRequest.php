<?php

namespace App\Http\Requests;

use App\Models\BaseContacto;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreBaseContactoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('base_contacto_create');
    }

    public function rules()
    {
        return [
            'cliente_id' => [
                'required',
                'integer',
            ],
            'rut_recibidor' => [
                'string',
                'nullable',
            ],
            'direccion' => [
                'string',
                'required',
            ],
            'contacto' => [
                'string',
                'required',
            ],
            'telefono' => [
                'string',
                'nullable',
            ],
            'fax' => [
                'string',
                'nullable',
            ],
            'notify' => [
                'string',
                'nullable',
            ],
        ];
    }
}
