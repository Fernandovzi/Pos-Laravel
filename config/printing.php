<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Ticket POS Width (mm)
    |--------------------------------------------------------------------------
    |
    | Define el ancho del ticket térmico en milímetros. Valores comunes:
    | - 58 mm (impresora pequeña)
    | - 80 mm (impresora estándar)
    |
    */
    'ticket_width_mm' => (float) env('POS_TICKET_WIDTH_MM', 80),

    /*
    |--------------------------------------------------------------------------
    | Ticket POS Horizontal Padding (mm)
    |--------------------------------------------------------------------------
    |
    | Espacio de seguridad interno para evitar recortes en bordes por
    | tolerancias del área imprimible de algunas impresoras térmicas.
    |
    */
    'ticket_padding_mm' => (float) env('POS_TICKET_PADDING_MM', 2),

    /*
    |--------------------------------------------------------------------------
    | Ticket POS Printable Width (mm)
    |--------------------------------------------------------------------------
    |
    | Algunas impresoras de 80mm no imprimen realmente los 80mm completos.
    | Este valor permite ajustar explícitamente el ancho útil para evitar
    | recortes laterales (ejemplo recomendado: 72mm para rollo de 80mm).
    |
    */
    'ticket_printable_width_mm' => (float) env('POS_TICKET_PRINTABLE_WIDTH_MM', 72),
];
