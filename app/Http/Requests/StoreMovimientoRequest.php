<?php

namespace App\Http\Requests;

use App\Enums\MetodoPagoEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class StoreMovimientoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'descripcion' => 'required|max:255',
            'monto' => 'numeric|min:1|required',
            'metodo_pago' => ['required', new Enum(MetodoPagoEnum::class)],
            'caja_id' => 'required',
            'tipo' => 'required'
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (!collect(MetodoPagoEnum::cashRegisterMethods())->map->value->contains($this->input('metodo_pago'))) {
                $validator->errors()->add('metodo_pago', 'Método de pago no permitido para caja.');
            }
        });
    }
}
