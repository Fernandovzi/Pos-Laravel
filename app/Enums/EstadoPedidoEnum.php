<?php

namespace App\Enums;

enum EstadoPedidoEnum: string
{
    case Apartado = 'APARTADO';
    case Entregado = 'ENTREGADO';
    case Cancelado = 'CANCELADO';
}
