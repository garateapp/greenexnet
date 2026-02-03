<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSolicitudCompraRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('solicitud_compra_create');
    }

    public function rules()
    {
        return [
            'titulo' => [
                'required',
                'string',
                'max:255',
            ],
            'descripcion' => [
                'nullable',
                'string',
            ],
            'monto_estimado' => [
                'required',
                'integer',
                'min:0',
            ],
            'centro_costo_id' => [
                Rule::requiredIf($this->isAdquisicionesUser()),
                'nullable',
                'integer',
                'exists:centro_costos,id',
                function ($attribute, $value, $fail) {
                    if (!$this->isAdquisicionesUser() && !is_null($value)) {
                        $fail('No tiene permisos para asignar centro de costo.');
                    }
                },
            ],
            'cotizaciones_por_adquisiciones' => [
                'boolean',
            ],
            'fecha_requerida' => [
                'nullable',
                'date_format:' . config('panel.date_format'),
            ],
        ];
    }

    protected function isAdquisicionesUser(): bool
    {
        $user = $this->user();
        if (!$user) {
            return false;
        }

        return $user->roles->pluck('title')->contains('Adquisiciones');
    }
}
