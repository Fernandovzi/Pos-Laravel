<?php

namespace App\Enums;

enum EstadoPedidoEnum: string
{
    case Borrador = 'BORRADOR';
    case Apartado = 'APARTADO';
    case Entregado = 'ENTREGADO';
    case Cancelado = 'CANCELADO';
}
