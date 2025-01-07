<?php

namespace App\Http\Requests;

use App\Models\CapturadorEstructura;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCapturadorEstructuraRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('capturador_estructura_create');
    }

    public function rules()
    {
        return [
            'capturador_id' => [
                'required',
                'integer',
            ],
            'propiedad' => [
                'string',
                'required',
            ],
            'coordenada' => [
                'string',
                'required',
            ],
            'orden' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'visible' => [
                'required',
            ],
            'formula' => [
                'string',
                'nullable',
            ],
        ];
    }
}
