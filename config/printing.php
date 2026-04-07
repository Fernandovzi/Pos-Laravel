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
];

