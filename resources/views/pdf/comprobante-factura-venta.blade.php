<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Factura {{ $venta->numero_comprobante }}</title>
    <style>
        @page {
            size: letter;
            margin: 20mm 15mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 11px;
            line-height: 1.35;
        }

        .section {
            border: 1px solid #dbe2ea;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .section-title {
            font-size: 11px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .row {
            width: 100%;
            display: table;
            table-layout: fixed;
        }

        .col {
            display: table-cell;
            vertical-align: top;
        }

        .col-65 {
            width: 65%;
            padding-right: 8px;
        }

        .col-35 {
            width: 35%;
            padding-left: 8px;
        }

        .company-title {
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 4px;
            color: #111827;
        }

        .logo {
            width: 110px;
            max-height: 70px;
            object-fit: contain;
            margin-bottom: 6px;
        }

        .meta-table,
        .concepts-table,
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .meta-label {
            width: 40%;
            font-weight: 700;
            color: #334155;
        }

        .concepts-table th,
        .concepts-table td {
            border: 1px solid #dbe2ea;
            padding: 6px;
        }

        .concepts-table th {
            background: #eef2f7;
            font-weight: 700;
            color: #0f172a;
            font-size: 10px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totals-wrap {
            width: 45%;
            margin-left: auto;
        }

        .totals-table td {
            border-bottom: 1px solid #e5e7eb;
            padding: 5px 6px;
        }

        .totals-table .label {
            font-weight: 700;
            color: #334155;
        }

        .totals-table .grand-total td {
            background: #e8f0fe;
            border-top: 2px solid #93c5fd;
            border-bottom: 2px solid #93c5fd;
            font-size: 13px;
            font-weight: 700;
            color: #0c4a6e;
        }

        .stamp-block {
            word-break: break-word;
            font-size: 9px;
            color: #374151;
        }

        .qr {
            width: 120px;
            height: 120px;
            border: 1px solid #dbe2ea;
            text-align: center;
            font-size: 10px;
            color: #64748b;
            line-height: 120px;
        }

        .footer {
            margin-top: 10px;
            border-top: 1px solid #cbd5e1;
            padding-top: 8px;
            font-size: 9px;
            color: #475569;
        }

        .notice {
            margin-top: 6px;
            font-weight: 700;
            text-align: center;
            color: #0f172a;
        }

        .page-number {
            margin-top: 6px;
            text-align: right;
        }
    </style>
</head>

<body>
    @php
    $descuento = $venta->descuento ?? 0;
    $retenciones = $venta->retenciones ?? 0;
    $ivaLabel = $empresa->abreviatura_impuesto ?? 'IVA';
    $ivaMonto = $venta->impuesto ?? 0;
    $metodoPago = $venta->metodo_pago?->label() ?? 'N/D';
    $formaPago = data_get($venta, 'forma_pago', 'N/D');
    $moneda = data_get($empresa, 'moneda.nombre', data_get($empresa, 'moneda.simbolo', 'MXN'));
    $tipoCambio = data_get($venta, 'tipo_cambio', '1.00');
    $uuid = data_get($venta, 'uuid', 'Pendiente de timbrado');
    $fechaCert = data_get($venta, 'fecha_certificacion', 'Pendiente de timbrado');
    $rfcPac = data_get($venta, 'rfc_pac', 'Pendiente de timbrado');
    $selloCfdi = data_get($venta, 'sello_cfdi', 'Pendiente de timbrado');
    $selloSat = data_get($venta, 'sello_sat', 'Pendiente de timbrado');
    $cadenaOriginal = data_get($venta, 'cadena_original', 'Pendiente de timbrado');
    $qrSat = data_get($venta, 'qr_sat');
    @endphp

    <div class="section">
        <div class="row">
            <div class="col col-65">
                <img src="{{ public_path('assets/img/maleri-ticket.png') }}" class="logo" alt="Logo empresa">
                <p class="company-title">{{ strtoupper($empresa->nombre ?? 'EMPRESA') }}</p>
                <table class="meta-table">
                    <tr>
                        <td class="meta-label">RFC:</td>
                        <td>{{ $empresa->ruc ?? 'N/D' }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Dirección fiscal:</td>
                        <td>{{ $empresa->direccion ?? 'N/D' }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Teléfono:</td>
                        <td>{{ $empresa->telefono ?? 'N/D' }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Correo:</td>
                        <td>{{ $empresa->email ?? 'N/D' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col col-35">
                <table class="meta-table">
                    <tr>
                        <td class="meta-label">Folio de factura:</td>
                        <td>{{ $venta->numero_comprobante }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Fecha de emisión:</td>
                        <td>{{ date('d/m/Y H:i', strtotime($venta->fecha_hora)) }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Tipo de comprobante:</td>
                        <td>{{ $venta->comprobante->nombre ?? 'FACTURA' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Datos del Cliente</div>
        <table class="meta-table">
            <tr>
                <td class="meta-label">Nombre / Razón social:</td>
                <td>{{ $venta->cliente->persona->razon_social ?? 'N/D' }}</td>
                <td class="meta-label">RFC:</td>
                <td>{{ $venta->cliente->persona->rfc ?? 'N/D' }}</td>
            </tr>
            <tr>
                <td class="meta-label">Dirección:</td>
                <td>{{ $venta->cliente->persona->direccion ?? 'N/D' }}</td>
                <td class="meta-label">Uso de CFDI:</td>
                <td>{{ $venta->cliente->persona->usoCfdiCatalogo->descripcion ?? ($venta->cliente->persona->uso_cfdi ?? 'N/D') }}</td>
            </tr>
            <tr>
                <td class="meta-label">Régimen fiscal:</td>
                <td>{{ $venta->cliente->persona->regimenFiscalCatalogo->descripcion ?? ($venta->cliente->persona->regimen_fiscal ?? 'N/D') }}</td>
                <td class="meta-label">C.P. fiscal:</td>
                <td>{{ $venta->cliente->persona->codigo_postal_fiscal ?? 'N/D' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table class="concepts-table">
            <thead>
                <tr>
                    <th class="text-center">Cantidad</th>
                    <th>Clave producto/servicio</th>
                    <th>Descripción</th>
                    <th class="text-center">Unidad</th>
                    <th class="text-right">Precio unitario</th>
                    <th class="text-right">Descuento</th>
                    <th class="text-right">Importe</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($venta->productos as $detalle)
                @php
                $cantidad = (float) $detalle->pivot->cantidad;
                $precioUnitario = (float) $detalle->pivot->precio_venta;
                $importe = $cantidad * $precioUnitario;
                @endphp
                <tr>
                    <td class="text-center">{{ number_format($cantidad, 2) }}</td>
                    <td>{{ $detalle->codigo ?? 'N/D' }}</td>
                    <td>{{ $detalle->nombre ?? 'N/D' }}</td>
                    <td class="text-center">{{ $detalle->presentacione->nombre ?? 'PZA' }}</td>
                    <td class="text-right">{{ number_format($precioUnitario, 2) }}</td>
                    <td class="text-right">{{ number_format(0, 2) }}</td>
                    <td class="text-right">{{ number_format($importe, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="totals-wrap">
            <table class="totals-table">
                <tr>
                    <td class="label">Subtotal</td>
                    <td class="text-right">{{ number_format($venta->subtotal ?? 0, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Descuento</td>
                    <td class="text-right">{{ number_format($descuento, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">{{ $ivaLabel }}</td>
                    <td class="text-right">{{ number_format($ivaMonto, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Retenciones</td>
                    <td class="text-right">{{ number_format($retenciones, 2) }}</td>
                </tr>
                <tr class="grand-total">
                    <td>TOTAL</td>
                    <td class="text-right">{{ number_format($venta->total ?? 0, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Información Fiscal</div>
        <table class="meta-table">
            <tr>
                <td class="meta-label">Método de pago:</td>
                <td>{{ $metodoPago }}</td>
                <td class="meta-label">Forma de pago:</td>
                <td>{{ $formaPago }}</td>
            </tr>
            <tr>
                <td class="meta-label">Moneda:</td>
                <td>{{ $moneda }}</td>
                <td class="meta-label">Tipo de cambio:</td>
                <td>{{ $tipoCambio }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Timbrado CFDI</div>
        <div class="row">
            <div class="col col-65 stamp-block">
                <p><strong>UUID:</strong> {{ $uuid }}</p>
                <p><strong>Fecha de certificación:</strong> {{ $fechaCert }}</p>
                <p><strong>RFC del PAC:</strong> {{ $rfcPac }}</p>
                <p><strong>Sello digital del CFDI:</strong> {{ $selloCfdi }}</p>
                <p><strong>Sello del SAT:</strong> {{ $selloSat }}</p>
            </div>
            <div class="col col-35 text-right">
                @if($qrSat)
                <img src="{{ $qrSat }}" class="qr" alt="Código QR SAT">
                @else
                <div class="qr" style="display:inline-block;">QR SAT</div>
                @endif
            </div>
        </div>
    </div>

    <div class="footer">
        <div><strong>Cadena original del complemento de certificación:</strong> {{ $cadenaOriginal }}</div>
        <div class="notice">Este documento es una representación impresa de un CFDI</div>
        <div class="page-number">Página 1 de 1</div>
    </div>
</body>

</html>
