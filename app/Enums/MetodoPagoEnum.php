<?php

namespace App\Enums;

enum MetodoPagoEnum: string
{
    case Efectivo = 'EFECTIVO';
    case Tarjeta = 'TARJETA';
    case Transferencia = 'TRANSFERENCIA';
    case dolares = 'DOLARES';
}
