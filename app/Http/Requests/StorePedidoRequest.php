<?php

namespace App\Http\Requests;

use App\Models\Inventario;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StorePedidoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proveedore_id' => ['required', 'exists:proveedores,id'],
            'persona_recojo' => ['required', 'string', 'max:255'],
            'observaciones' => ['nullable', 'string', 'max:1000'],
            'fecha_entrega_estimada' => ['nullable', 'date'],
            'subtotal' => ['required', 'numeric', 'min:0.01'],
            'impuesto' => ['required', 'numeric', 'min:0'],
            'total' => ['required', 'numeric', 'min:0.01'],
            'arrayidproducto' => ['required', 'array', 'min:1'],
            'arrayidproducto.*' => ['required', 'exists:productos,id'],
            'arraycantidad' => ['required', 'array', 'min:1'],
            'arraycantidad.*' => ['required', 'integer', 'min:1'],
            'arrayprecio' => ['required', 'array', 'min:1'],
            'arrayprecio.*' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Comprueba que las cantidades solicitadas todavía estén disponibles.
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $productoIds = $this->input('arrayidproducto', []);
                $cantidades = $this->input('arraycantidad', []);

                if (! is_array($productoIds) || ! is_array($cantidades) || count($productoIds) !== count($cantidades)) {
                    $validator->errors()->add('arraycantidad', 'El detalle de productos es inconsistente.');

                    return;
                }

                $solicitadoPorProducto = [];

                foreach ($productoIds as $index => $productoId) {
                    if (! is_numeric($productoId) || ! isset($cantidades[$index]) || ! is_numeric($cantidades[$index])) {
                        continue;
                    }

                    $solicitadoPorProducto[$productoId] = ($solicitadoPorProducto[$productoId] ?? 0) + (int) $cantidades[$index];
                }

                $existencias = Inventario::query()
                    ->whereIn('producto_id', array_keys($solicitadoPorProducto))
                    ->pluck('cantidad', 'producto_id');

                foreach ($solicitadoPorProducto as $productoId => $cantidadSolicitada) {
                    if ($cantidadSolicitada > (int) ($existencias[$productoId] ?? 0)) {
                        $validator->errors()->add(
                            'arraycantidad',
                            "La cantidad solicitada del producto {$productoId} supera la existencia disponible."
                        );
                    }
                }
            },
        ];
    }
}
