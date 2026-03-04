<?php

namespace App\Enums;

enum TipoTransaccionEnum: string
{
    case Compra = 'COMPRA';
    case Venta = 'VENTA';
    case Ajuste = 'AJUSTE';
    case Apertura = 'APERTURA';
    case Pedido = 'PEDIDO';
    case CancelacionPedido = 'CANCELACION_PEDIDO';
}
