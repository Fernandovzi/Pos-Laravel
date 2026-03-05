<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura {{ $venta->numero_comprobante }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111; font-size: 12px; }
        .header, .section { margin-bottom: 16px; }
        .title { font-size: 18px; font-weight: bold; }
        .muted { color: #555; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 6px; }
        th { background: #f3f4f6; text-align: left; }
        .right { text-align: right; }
        .totals td { border: none; }
        .box { border: 1px solid #d1d5db; padding: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">FACTURA</div>
        <div><strong>Folio:</strong> {{ $venta->numero_comprobante }}</div>
        <div><strong>Fecha:</strong> {{ date('d/m/Y H:i', strtotime($venta->fecha_hora)) }}</div>
    </div>

    <div class="section box">
        <strong>Emisor</strong><br>
        {{ $empresa->nombre }}<br>
        RFC: {{ $empresa->ruc }}<br>
        {{ $empresa->direccion }}
    </div>

    <div class="section box">
        <strong>Receptor</strong><br>
        {{ $venta->cliente->persona->razon_social }}<br>
        RFC: {{ $venta->cliente->persona->rfc ?? 'N/D' }}<br>
        Régimen fiscal: {{ $venta->cliente->persona->regimenFiscalCatalogo->descripcion ?? ($venta->cliente->persona->regimen_fiscal ?? 'N/D') }}<br>
        Uso CFDI: {{ $venta->cliente->persona->usoCfdiCatalogo->descripcion ?? ($venta->cliente->persona->uso_cfdi ?? 'N/D') }}<br>
        C.P. fiscal: {{ $venta->cliente->persona->codigo_postal_fiscal ?? 'N/D' }}
    </div>

    <div class="section">
        <table>
            <thead>
                <tr>
                    <th>Clave</th>
                    <th>Descripción</th>
                    <th class="right">Cant.</th>
                    <th class="right">P. Unitario</th>
                    <th class="right">Importe</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($venta->productos as $detalle)
                <tr>
                    <td>{{ $detalle->codigo }}</td>
                    <td>{{ $detalle->nombre }}</td>
                    <td class="right">{{ number_format($detalle->pivot->cantidad, 2) }}</td>
                    <td class="right">{{ number_format($detalle->pivot->precio_venta, 2) }}</td>
                    <td class="right">{{ number_format($detalle->pivot->cantidad * $detalle->pivot->precio_venta, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <table class="totals">
            <tr><td class="right"><strong>Subtotal:</strong> {{ number_format($venta->subtotal, 2) }}</td></tr>
            <tr><td class="right"><strong>{{ $empresa->abreviatura_impuesto }}:</strong> {{ number_format($venta->impuesto, 2) }}</td></tr>
            <tr><td class="right"><strong>Total:</strong> {{ number_format($venta->total, 2) }}</td></tr>
        </table>
    </div>

    <div class="section box">
        <strong>Desglose de pagos</strong>
        <table>
            <thead>
                <tr>
                    <th>Método</th>
                    <th class="right">Monto</th>
                    <th>Referencia</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->pagos as $pago)
                <tr>
                    <td>{{ $pago->metodo_pago->label() }}</td>
                    <td class="right">{{ number_format($pago->monto, 2) }}</td>
                    <td>{{ $pago->referencia ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="muted">Cajero: {{ $venta->user->empleado->razon_social ?? $venta->user->name }}</div>
</body>
</html>
