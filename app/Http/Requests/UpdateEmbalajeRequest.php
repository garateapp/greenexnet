<?php

namespace App\Http\Requests;

use App\Models\Embalaje;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateEmbalajeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('embalaje_edit');
    }

    public function rules()
    {
        return [
            'c_embalaje' => [
                'string',
                'required',
            ],
            'kgxcaja' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'cajaxpallet' => [
                'string',
                'required',
            ],
            'altura_pallet' => [
                'numeric',
            ],
            'caja' => [
                'string',
                'required',
            ],
        ];
    }
}
