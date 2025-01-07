<?php

namespace App\Http\Requests;

use App\Models\ItemEmbalaje;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateItemEmbalajeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('item_embalaje_edit');
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
