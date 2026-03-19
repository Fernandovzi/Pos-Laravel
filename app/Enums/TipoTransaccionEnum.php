<?php

namespace App\Enums;

enum TipoTransaccionEnum: string
{
    case ProduccionInterna = 'PRODUCCION_INTERNA';
    case Venta = 'VENTA';
    case Ajuste = 'AJUSTE';
    case Apertura = 'APERTURA';
    case Pedido = 'PEDIDO';
    case CancelacionPedido = 'CANCELACION_PEDIDO';
    case CancelacionVenta = 'CANCELACION_VENTA';
}
