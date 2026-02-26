<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ticket</title>

    <style>
        body {
            font-family: monospace;
            margin: 0;
            padding: 0;
            background: #fff;
        }

        .ticket {
            width: 220px;
            /* 58mm */
            /* width: 300px;  80mm */
            margin: auto;
            padding: 5px;
            font-size: 11px;
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
            width: 25px;
        }

        .desc {
            width: 120px;
        }

        .price {
            width: 40px;
            text-align: right;
        }

        .total {
            width: 45px;
            text-align: right;
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
        <div class="center bold">{{ strtoupper($empresa->nombre) }}</div>
        <div class="center">RUC: {{ $empresa->ruc }}</div>
        <div class="center">{{ strtoupper($empresa->direccion) }}</div>
        <div class="center">{{ strtoupper($empresa->ubicacion) }}</div>
        <div class="center">TEL: {{ $empresa->telefono ?? '' }}</div>

        <div class="divider"></div>

        <!-- COMPROBANTE -->
        <div class="center bold">
            {{ strtoupper($venta->comprobante->nombre) }}
        </div>
        <div class="center bold">
            {{ $venta->numero_comprobante }}
        </div>

        <div class="divider"></div>

        <!-- CLIENTE -->
        <div>Cliente: {{ strtoupper($venta->cliente->persona->razon_social) }}</div>
        <div>{{ $venta->cliente->persona->documento->nombre }}:
            {{ $venta->cliente->persona->numero_documento }}
        </div>
        <div>Fecha: {{ date('d/m/Y H:i', strtotime($venta->fecha_hora)) }}</div>

        <div class="divider"></div>

        <!-- PRODUCTOS -->
        <table>
            @foreach ($venta->productos as $detalle)
            <tr>

                <td class="desc"> {{$detalle->codigo}} - {{$detalle->nombre}}</td>
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
        <div>Pago: {{ $venta->metodo_pago }}</div>
        <div>Cajero: {{ $venta->user->empleado->razon_social ?? $venta->user->name }}</div>

        <div class="divider"></div>

        <!-- FOOTER -->
        <div class="center bold">GRACIAS POR SU COMPRA</div>

    </div>
</body>

</html>