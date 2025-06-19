<?php

namespace App\Http\Requests;

use App\Models\Condpago;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCondpagoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('condpago_edit');
    }

    public function rules()
    {
        return [
            'cond_pago' => [
                'string',
                'nullable',
            ],
        ];
    }
}
