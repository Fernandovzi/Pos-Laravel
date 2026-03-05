<?php

namespace App\Enums;

enum MetodoPagoEnum: string
{
    case Efectivo = 'EFECTIVO';
    case Tarjeta = 'TARJETA'; // compatibilidad histórica para compras
    case TarjetaDebito = 'TARJETA_DEBITO';
    case TarjetaCredito = 'TARJETA_CREDITO';
    case TransferenciaSpei = 'TRANSFERENCIA_SPEI';
    case PagoMixto = 'PAGO_MIXTO';

    public function label(): string
    {
        return match ($this) {
            self::Efectivo => 'Efectivo',
            self::Tarjeta => 'Tarjeta',
            self::TarjetaDebito => 'Tarjeta de Débito',
            self::TarjetaCredito => 'Tarjeta de Crédito',
            self::TransferenciaSpei => 'Transferencia / SPEI',
            self::PagoMixto => 'Pago mixto',
        };
    }

    public function allowsReference(): bool
    {
        return in_array($this, [
            self::TarjetaDebito,
            self::TarjetaCredito,
            self::TransferenciaSpei,
            self::Tarjeta,
        ], true);
    }

    public static function salesMethods(): array
    {
        return [
            self::Efectivo,
            self::TarjetaDebito,
            self::TarjetaCredito,
            self::TransferenciaSpei,
            self::PagoMixto,
        ];
    }

    public static function cashRegisterMethods(): array
    {
        return [
            self::Efectivo,
            self::TarjetaDebito,
            self::TarjetaCredito,
            self::TransferenciaSpei,
        ];
    }
}
