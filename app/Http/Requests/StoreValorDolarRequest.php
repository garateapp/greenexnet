<?php

namespace App\Http\Requests;

use App\Models\ValorDolar;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreValorDolarRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('valor_dolar_create');
    }

    public function rules()
    {
        return [
            'fecha_cambio' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'valor' => [
                'numeric',
                'required',
            ],
        ];
    }
}
