<?php

namespace App\Http\Requests;

use App\Enums\MetodoPagoEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class StoreVentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id' => 'required|exists:clientes,id',
            'comprobante_id' => 'required|exists:comprobantes,id',
            'metodo_pago' => ['required', new Enum(MetodoPagoEnum::class)],
            'subtotal' => 'required|numeric|min:1',
            'impuesto' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:1',
            'monto_recibido' => 'required|numeric|min:0',
            'vuelto_entregado' => 'required|numeric|min:0',
            'pagos' => 'required|array|min:1',
            'pagos.*.metodo_pago' => ['required', new Enum(MetodoPagoEnum::class)],
            'pagos.*.monto' => 'required|numeric|min:0.01',
            'pagos.*.referencia' => 'nullable|string|max:255',
            'arrayidproducto' => 'required|array|min:1',
            'arrayidproducto.*' => 'required|integer|exists:productos,id',
            'arraycantidad' => 'required|array|min:1',
            'arraycantidad.*' => 'required|numeric|min:1',
            'arrayprecioventa' => 'required|array|min:1',
            'arrayprecioventa.*' => 'required|numeric|min:0',
        ];
    }

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

            $pagos = $this->input('pagos', []);
            $totalVenta = (float) $this->input('total', 0);
            $sumaPagos = collect($pagos)->sum(fn ($pago) => (float) ($pago['monto'] ?? 0));

            if (abs($sumaPagos - $totalVenta) > 0.01) {
                $validator->errors()->add('pagos', 'La suma de los pagos debe ser igual al total de la venta.');
            }


            if (collect($pagos)->pluck('metodo_pago')->contains(MetodoPagoEnum::PagoMixto->value)) {
                $validator->errors()->add('pagos', 'Pago mixto solo se usa como método principal de la venta, no como tipo de pago individual.');
            }


            $metodosPermitidosVenta = collect(MetodoPagoEnum::salesMethods())->map->value;
            if (!$metodosPermitidosVenta->contains($this->input('metodo_pago'))) {
                $validator->errors()->add('metodo_pago', 'Método de pago principal no permitido para ventas.');
            }

            $metodosPagoDetalle = collect($pagos)->pluck('metodo_pago')->filter();
            if ($metodosPagoDetalle->diff(collect(MetodoPagoEnum::cashRegisterMethods())->map->value)->isNotEmpty()) {
                $validator->errors()->add('pagos', 'Se detectó un método de pago no permitido en el desglose.');
            }

            $metodos = collect($pagos)
                ->pluck('metodo_pago')
                ->filter()
                ->unique()
                ->values();

            if ($metodos->count() > 1 && $this->input('metodo_pago') !== MetodoPagoEnum::PagoMixto->value) {
                $validator->errors()->add('metodo_pago', 'Cuando hay múltiples métodos de pago, el método principal debe ser Pago mixto.');
            }

            if ($metodos->count() === 1 && $this->input('metodo_pago') !== $metodos->first()) {
                $validator->errors()->add('metodo_pago', 'El método principal debe coincidir con el único método de pago registrado.');
            }
        });
    }
}
