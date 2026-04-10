<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ticket</title>

    @php
    $ticketWidthMm = (float) config('printing.ticket_width_mm', 80);
    $ticketPaddingMm = (float) config('printing.ticket_padding_mm', 2);
    $printableWidthMm = (float) config('printing.ticket_printable_width_mm', 72);
    $extraInnerPaddingMm = 2.5;
    $safeTicketWidthMm = max(min($printableWidthMm, $ticketWidthMm) - (($ticketPaddingMm + $extraInnerPaddingMm) * 2), 36);
    @endphp

    <style>
        @page {
            margin: 1.5mm 0;

            size: {
                    {
                    $ticketWidthMm
                }
            }

            mm auto;
        }

        html,
        body {
            font-family: monospace;
            margin: 0;
            padding: 0;
            background: #fff;
            width: {{ $ticketWidthMm }}mm;
            font-weight: 700;
        }

        body,
        div,
        span,
        td {
            font-weight: 700 !important;
        }

        .ticket {
            width: {
                    {
                    $safeTicketWidthMm
                }
            }

            mm;
            margin: 3mm auto;

            padding: 0 {
                    {
                    $ticketPaddingMm + $extraInnerPaddingMm
                }
            }

            mm;
            box-sizing: border-box;
            font-size: 13px;
            font-weight: 900;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .title {
            font-size: 13px;
            letter-spacing: .3px;
        }

        .label {
            opacity: 1;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        img.logo {
            max-width: 100px;
            /* prueba 100–140 */
            height: auto;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 2px 0;
            vertical-align: top;
        }

        .qty {
            width: 12px;
        }

        .desc {
            width: 58px;
        }

        .price {
            width: 40px;
            text-align: right;
        }

        .total {
            width: 34px;
            text-align: right;
        }

        .inline-discount {
            font-size: 13px;
            font-weight: 700 !important;
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <div class="ticket">

        <!-- LOGO -->
        <div class="center">
            <img src="{{ public_path('assets/img/maleri-ticket.png') }}" class="logo">
        </div>

        <br>

        <!-- EMPRESA -->
        <div class="center bold title">{{ strtoupper($empresa->nombre) }}</div>
        <div class="center">RUC: {{ $empresa->ruc }}</div>
        <div class="center">{{ strtoupper($empresa->direccion) }}</div>
        <div class="center">{{ strtoupper($empresa->ubicacion) }}</div>
        <div class="center">TEL: {{ $empresa->telefono ?? '' }}</div>

        <div class="divider"></div>

        <!-- COMPROBANTE -->
        <div class="center bold">
            <span class="title">{{ strtoupper($venta->comprobante->nombre) }}</span>
        </div>
        <div class="center bold">
            {{ $venta->numero_comprobante }}
        </div>

        <div class="divider"></div>

        <!-- CLIENTE -->
        <div><span class="label">Cliente:</span> {{ strtoupper($venta->cliente->persona->razon_social) }}</div>
        <div><span class="label">RFC:</span> {{ $venta->cliente->persona->rfc ?? 'N/D' }}</div>
        <div><span class="label">Fecha:</span> {{ date('d/m/Y H:i', strtotime($venta->fecha_hora)) }}</div>

        <div class="divider"></div>

        <!-- PRODUCTOS -->
        <table>
            @foreach ($venta->productos as $detalle)
            <tr>
                <td class="desc">
                    {{ $detalle->codigo }} - {{ $detalle->nombre }}
                    @if(($detalle->pivot->descuento_porcentaje ?? 0) > 0)
                        <span class="inline-discount"> | Desc {{ number_format($detalle->pivot->descuento_porcentaje, 2) }}% ({{ number_format($detalle->pivot->precio_original ?? $detalle->pivot->precio_venta, 2) }} → {{ number_format($detalle->pivot->precio_venta, 2) }})</span>
                    @endif
                </td>
                <td class="qty">{{ $detalle->pivot->cantidad }}</td>
                <td class="total">{{ number_format($detalle->pivot->cantidad * $detalle->pivot->precio_venta, 2) }}</td>
            </tr>
            @endforeach
        </table>

        <div class="divider"></div>

        <!-- TOTALES -->
        <table>
            <tr>
                <td>SUBTOTAL</td>
                <td class="right">{{ number_format($venta->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>DESCUENTO</td>
                <td class="right">-{{ number_format($venta->descuento_total_monto ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td>{{ $empresa->abreviatura_impuesto }}</td>
                <td class="right">{{ number_format($venta->impuesto, 2) }}</td>
            </tr>
            <tr class="bold">
                <td>TOTAL</td>
                <td class="right">{{ number_format($venta->total, 2) }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <!-- EXTRA -->
        <div><span class="label">Pago:</span> {{ $venta->metodo_pago?->label() }}</div>
        <div><span class="label">Cajero:</span> {{ $venta->user->empleado->razon_social ?? $venta->user->name }}</div>

        <div class="divider"></div>

        <!-- FOOTER -->
        <div class="center bold">GRACIAS POR SU COMPRA</div>

    </div>
</body>

</html>
