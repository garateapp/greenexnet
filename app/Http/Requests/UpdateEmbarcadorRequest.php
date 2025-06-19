<?php

namespace App\Http\Requests;

use App\Models\Embarcador;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateEmbarcadorRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('embarcador_edit');
    }

    public function rules()
    {
        return [
            'codigo' => [
                'string',
                'required',
            ],
            'via' => [
                'required',
            ],
            'nombre' => [
                'string',
                'required',
            ],
            'rut' => [
                'string',
                'nullable',
            ],
            'attn' => [
                'string',
                'required',
            ],
            'email' => [
                'string',
                'nullable',
            ],
            'telefono' => [
                'string',
                'nullable',
            ],
            'p_sag_dir' => [
                'string',
                'required',
            ],
            'g_dir_a' => [
                'string',
                'required',
            ],
        ];
    }
}
