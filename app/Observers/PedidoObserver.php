<?php

namespace App\Observers;

use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;

class PedidoObserver
{
    /**
     * Inicializa datos automáticos del pedido antes de crearse.
     */
    public function creating(Pedido $pedido): void
    {
        $pedido->folio = Pedido::generarFolio();
        $pedido->user_id = Auth::id();
        $pedido->fecha_apartado = now();
    }
}
