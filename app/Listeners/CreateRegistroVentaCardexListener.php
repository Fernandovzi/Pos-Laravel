<?php

namespace App\Listeners;

use App\Enums\TipoTransaccionEnum;
use App\Events\CreateVentaDetalleEvent;
use App\Models\Kardex;
use App\Models\Producto;
use Illuminate\Support\Facades\Log;

class CreateRegistroVentaCardexListener
{
    /**
     * Handle the event.
     */
    public function handle(CreateVentaDetalleEvent $event): void
    {
        $kardex = new Kardex();

        $ultimoRegistro = $kardex->where('producto_id', $event->producto_id)
            ->latest('id')
            ->first();

        /**
         * Evita error cuando no existe histórico en kardex:
         * 1) usa costo_unitario del último kardex si existe
         * 2) usa costo del producto
         * 3) último recurso: precio_venta del evento
         */
        $costoUnitario = $ultimoRegistro?->costo_unitario;

        if ($costoUnitario === null) {
            $producto = Producto::find($event->producto_id);
            $costoUnitario = $producto?->costo;
        }

        if ($costoUnitario === null) {
            $costoUnitario = (float) $event->precio_venta;

            Log::warning('No se encontró costo histórico para producto vendido; se usó precio_venta como respaldo.', [
                'venta_id' => $event->venta->id,
                'producto_id' => $event->producto_id,
                'precio_venta' => $event->precio_venta,
            ]);
        }

        $kardex->crearRegistro(
            [
                'venta_id' => $event->venta->id,
                'producto_id' => $event->producto_id,
                'cantidad' => $event->cantidad,
                'costo_unitario' => $costoUnitario,
            ],
            TipoTransaccionEnum::Venta
        );
    }
}
