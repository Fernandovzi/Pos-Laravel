<?php

namespace App\Http\Requests;

use App\Enums\MetodoPagoEnum;
use App\Models\Cliente;
use App\Models\Comprobante;
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
            'descuento_total_porcentaje' => 'nullable|numeric|min:0|max:100',
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
            'arraydescuentoproducto' => 'required|array|min:1',
            'arraydescuentoproducto.*' => 'required|numeric|min:0|max:100',
        ];
    }

    /**
     * Normaliza entradas para reducir errores de formato y evitar datos vacíos.
     */
    protected function prepareForValidation(): void
    {
        $pagos = collect((array) $this->input('pagos', []))
            ->map(function (array $pago): array {
                return [
                    'metodo_pago' => $pago['metodo_pago'] ?? null,
                    'monto' => $pago['monto'] ?? null,
                    'referencia' => isset($pago['referencia']) ? trim((string) $pago['referencia']) : null,
                ];
            })
            ->values()
            ->all();

        $this->merge([
            'pagos' => $pagos,
            'arrayidproducto' => array_values((array) $this->input('arrayidproducto', [])),
            'arraycantidad' => array_values((array) $this->input('arraycantidad', [])),
            'arrayprecioventa' => array_values((array) $this->input('arrayprecioventa', [])),
            'arraydescuentoproducto' => array_values((array) $this->input('arraydescuentoproducto', [])),
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $ids = $this->input('arrayidproducto', []);
            $cantidades = $this->input('arraycantidad', []);
            $precios = $this->input('arrayprecioventa', []);
            $descuentos = $this->input('arraydescuentoproducto', []);

            if (count($ids) !== count($cantidades) || count($ids) !== count($precios) || count($ids) !== count($descuentos)) {
                $validator->errors()->add(
                    'arrayidproducto',
                    'El detalle de venta es inconsistente: los arreglos de producto, cantidad, precio y descuento deben tener la misma longitud.'
                );
            }

            $descuentoTotal = (float) $this->input('descuento_total_porcentaje', 0);
            $hayDescuentoPorProducto = collect($descuentos)->contains(fn($valor) => (float) $valor > 0);

            if ($descuentoTotal > 0 && $hayDescuentoPorProducto) {
                $validator->errors()->add(
                    'descuento_total_porcentaje',
                    'Solo se puede aplicar descuento por producto o descuento total, no ambos.'
                );
            }

            $pagos = $this->input('pagos', []);
            $totalVenta = (float) $this->input('total', 0);
            $sumaPagos = collect($pagos)->sum(fn($pago) => (float) ($pago['monto'] ?? 0));

            if (($sumaPagos + 0.01) < $totalVenta) {
                $validator->errors()->add('pagos', 'La suma de los pagos no puede ser menor al total de la venta.');
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

            $this->validarRequisitosFactura($validator);
        });
    }

    /**
     * Si el comprobante es factura, valida datos fiscales mínimos del cliente.
     */
    private function validarRequisitosFactura(Validator $validator): void
    {
        $comprobante = Comprobante::find($this->input('comprobante_id'));

        if (!$comprobante || !str_contains(strtoupper($comprobante->nombre), 'FACTURA')) {
            return;
        }

        $cliente = Cliente::with('persona')->find($this->input('cliente_id'));
        $persona = $cliente?->persona;

        if (!$persona) {
            $validator->errors()->add('cliente_id', 'No se encontró la información fiscal del cliente para facturar.');

            return;
        }

        if (blank($persona->rfc)) {
            $validator->errors()->add('cliente_id', 'Para facturar, el cliente debe tener RFC.');
        }

        if (blank($persona->regimen_fiscal)) {
            $validator->errors()->add('cliente_id', 'Para facturar, el cliente debe tener régimen fiscal.');
        }

        if (blank($persona->uso_cfdi)) {
            $validator->errors()->add('cliente_id', 'Para facturar, el cliente debe tener uso CFDI.');
        }

        if (blank($persona->codigo_postal_fiscal)) {
            $validator->errors()->add('cliente_id', 'Para facturar, el cliente debe tener código postal fiscal.');
        }
    }
}
