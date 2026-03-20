<?php

namespace App\Enums;

enum UserRoleEnum: string
{
    case Administrador = 'administrador';
    case Inventarios = 'inventarios';

    public static function protected(): array
    {
        return [self::Administrador->value];
    }

    public static function protectedForMiddleware(): string
    {
        return implode('|', [
            self::Administrador->value,
            self::Inventarios->value,
        ]);
    }
}
