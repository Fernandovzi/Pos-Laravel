<?php

namespace App\Listeners;

use App\Enums\TipoMovimientoEnum;
use App\Events\CreateVentaEvent;
use App\Models\Caja;
use App\Models\Movimiento;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreateMovimientoVentaCajaListener
{
    public function __construct()
    {
        //
    }

    /**
     * Registra un movimiento por cada pago de la venta para mantener el corte por método.
     */
    public function handle(CreateVentaEvent $event): void
    {
        $caja = Caja::where('user_id', Auth::id())->where('estado', 1)->first();

        if (!$caja) {
            return;
        }

        try {
            foreach ($event->venta->pagos as $pago) {
                Movimiento::create([
                    'tipo' => TipoMovimientoEnum::Venta,
                    'descripcion' => 'Venta n° ' . $event->venta->numero_comprobante,
                    'monto' => $pago->monto,
                    'metodo_pago' => $pago->metodo_pago,
                    'caja_id' => $caja->id,
                ]);
            }
        } catch (Exception $e) {
            Log::error(
                'Error en el Listener CreateMovimientoVentaCajaListener',
                ['error' => $e->getMessage()]
            );
        }
    }
}
