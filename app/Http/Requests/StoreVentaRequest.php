<?php

namespace App\Http\Requests;

use App\Enums\MetodoPagoEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class StoreVentaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'cliente_id' => 'required|exists:clientes,id',
            'comprobante_id' => 'required|exists:comprobantes,id',
            'metodo_pago' => ['required', new Enum(MetodoPagoEnum::class)],
            'subtotal' => 'required|numeric|min:1',
            'impuesto' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:1',
            'monto_recibido' => 'required|numeric|min:1',
            'vuelto_entregado' => 'required|numeric|min:0',
            'arrayidproducto' => 'required|array|min:1',
            'arrayidproducto.*' => 'required|integer|exists:productos,id',
            'arraycantidad' => 'required|array|min:1',
            'arraycantidad.*' => 'required|numeric|min:1',
            'arrayprecioventa' => 'required|array|min:1',
            'arrayprecioventa.*' => 'required|numeric|min:0',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $ids = $this->input('arrayidproducto', []);
            $cantidades = $this->input('arraycantidad', []);
            $precios = $this->input('arrayprecioventa', []);

            if (count($ids) !== count($cantidades) || count($ids) !== count($precios)) {
                $validator->errors()->add(
                    'arrayidproducto',
                    'El detalle de venta es inconsistente: los arreglos de producto, cantidad y precio deben tener la misma longitud.'
                );
            }
        });
    }
}
