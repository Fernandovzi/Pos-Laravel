<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pedido {{ $pedido->folio }}</title>
    <style>
        @page { margin: 20px; }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: #f8fafc;
            color: #1e293b;
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.45;
        }

        .document {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
        }

        .hero {
            background: #0f172a;
            border-radius: 14px;
            color: #ffffff;
            padding: 22px 24px;
            width: 100%;
        }

        .hero td { vertical-align: middle; }
        .logo { max-height: 58px; max-width: 135px; }
        .company-name { color: #ffffff; font-size: 23px; font-weight: 700; margin: 0; }
        .document-title { color: #cbd5e1; font-size: 12px; letter-spacing: 1.6px; margin: 3px 0 0; text-transform: uppercase; }
        .folio-badge { background: #2563eb; border-radius: 999px; color: #ffffff; display: inline-block; font-size: 10px; font-weight: 700; padding: 7px 14px; }
        .text-right { text-align: right; }

        .section { margin-top: 14px; }
        .section-title { color: #0f172a; font-size: 13px; font-weight: 700; letter-spacing: .6px; margin: 0 0 8px; text-transform: uppercase; }

        .info-grid { width: 100%; border-collapse: separate; border-spacing: 0 5px; }
        .info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 7px 9px;
            width: 33.333%;
        }
        .info-label { color: #64748b; display: block; font-size: 8px; font-weight: 700; letter-spacing: .6px; margin-bottom: 2px; text-transform: uppercase; }
        .info-value { color: #0f172a; font-size: 12px; font-weight: 700; }

        .summary-table { width: 100%; border-collapse: separate; border-spacing: 10px 0; margin-left: -10px; margin-right: -10px; }
        .summary-card {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 14px;
            padding: 14px;
            width: 33.333%;
        }
        .summary-card.total { background: #0f172a; border-color: #0f172a; color: #ffffff; }
        .summary-label { color: #64748b; font-size: 9px; font-weight: 700; letter-spacing: .8px; text-transform: uppercase; }
        .summary-value { color: #1d4ed8; font-size: 20px; font-weight: 800; margin-top: 4px; }
        .summary-card.total .summary-label { color: #cbd5e1; }
        .summary-card.total .summary-value { color: #ffffff; }

        .products-table { border-collapse: separate; border-spacing: 0; width: 100%; }
        .products-table th {
            background: #1e293b;
            border: none;
            color: #ffffff;
            font-size: 9px;
            letter-spacing: .65px;
            padding: 10px 8px;
            text-align: left;
            text-transform: uppercase;
        }
        .products-table th:first-child { border-radius: 10px 0 0 0; }
        .products-table th:last-child { border-radius: 0 10px 0 0; }
        .products-table td {
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
            padding: 10px 8px;
        }
        .products-table tbody tr:nth-child(even) td { background: #f8fafc; }
        .product-code { color: #2563eb; font-weight: 800; }
        .product-name { color: #0f172a; font-weight: 700; }
        .number { text-align: right; }
        .quantity { color: #0f172a; font-weight: 800; text-align: center; }

        .totals { margin-left: auto; margin-top: 16px; width: 270px; }
        .totals td { border: none; padding: 6px 0; }
        .totals .label { color: #64748b; font-weight: 700; text-align: right; }
        .totals .amount { color: #0f172a; font-weight: 700; text-align: right; }
        .totals .grand-total td { border-top: 2px solid #0f172a; font-size: 14px; padding-top: 9px; }

        .footer-note { border-top: 1px solid #e2e8f0; color: #64748b; font-size: 9px; margin-top: 22px; padding-top: 10px; text-align: center; }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('img/maleri.png');
        $proveedorNombre = optional(optional($pedido->proveedore)->persona)->razon_social
            ?? optional(optional($pedido->cliente)->persona)->razon_social
            ?? 'N/D';
        $proveedorRfc = optional(optional($pedido->proveedore)->persona)->rfc
            ?? optional(optional($pedido->cliente)->persona)->rfc
            ?? 'N/D';
        $totalProductosRegistrados = $pedido->productos->count();
        $totalCantidadProductos = $pedido->productos->sum(fn ($producto) => $producto->pivot->cantidad);
    @endphp

    <div class="document">
        <table class="hero">
            <tr>
                <td width="25%">
                    @if(file_exists($logoPath))
                        <img src="{{ $logoPath }}" class="logo" alt="Maleri">
                    @endif
                </td>
                <td width="45%">
                    <h1 class="company-name">{{ $empresa->nombre ?? 'Maleri' }}</h1>
                    <p class="document-title">Comprobante de pedido</p>
                </td>
                <td width="30%" class="text-right">
                    <span class="folio-badge">{{ $pedido->folio }}</span>
                </td>
            </tr>
        </table>

        <div class="section">
            <p class="section-title">Información del pedido</p>
            <table class="info-grid">
                <tr>
                    <td class="info-card"><span class="info-label">Proveedor / Cliente</span><span class="info-value">{{ $proveedorNombre }}</span></td>
                    <td class="info-card"><span class="info-label">RFC</span><span class="info-value">{{ $proveedorRfc }}</span></td>
                    <td class="info-card"><span class="info-label">Estado</span><span class="info-value">{{ $pedido->estado->value }}</span></td>
                </tr>
                <tr>
                    <td class="info-card"><span class="info-label">Persona que recoge</span><span class="info-value">{{ $pedido->persona_recojo }}</span></td>
                    <td class="info-card"><span class="info-label">Fecha</span><span class="info-value">{{ $pedido->fecha_format }}</span></td>
                    <td class="info-card"><span class="info-label">Registró</span><span class="info-value">{{ $pedido->user->name ?? 'N/D' }}</span></td>
                </tr>
            </table>
        </div>

        <div class="section">
            <table class="summary-table">
                <tr>
                    <td class="summary-card"><div class="summary-label">Productos registrados</div><div class="summary-value">{{ number_format($totalProductosRegistrados) }}</div></td>
                    <td class="summary-card"><div class="summary-label">Cantidad total</div><div class="summary-value">{{ number_format($totalCantidadProductos) }}</div></td>
                    <td class="summary-card total"><div class="summary-label">Total del pedido</div><div class="summary-value">${{ number_format($pedido->total, 2) }}</div></td>
                </tr>
            </table>
        </div>

        <div class="section">
            <p class="section-title">Detalle de productos</p>
            <table class="products-table">
                <thead>
                    <tr>
                        <th width="16%">Código</th>
                        <th width="40%">Producto</th>
                        <th width="12%" class="number">Cantidad</th>
                        <th width="16%" class="number">Precio</th>
                        <th width="16%" class="number">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedido->productos as $producto)
                    <tr>
                        <td class="product-code">{{ $producto->codigo ?? 'N/D' }}</td>
                        <td class="product-name">{{ $producto->nombre }}</td>
                        <td class="quantity">{{ number_format($producto->pivot->cantidad) }}</td>
                        <td class="number">${{ number_format($producto->pivot->precio, 2) }}</td>
                        <td class="number">${{ number_format($producto->pivot->cantidad * $producto->pivot->precio, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <table class="totals">
            <tr><td class="label">Subtotal:</td><td class="amount">${{ number_format($pedido->subtotal, 2) }}</td></tr>
            <tr><td class="label">Impuesto:</td><td class="amount">${{ number_format($pedido->impuesto, 2) }}</td></tr>
            <tr class="grand-total"><td class="label">Total:</td><td class="amount">${{ number_format($pedido->total, 2) }}</td></tr>
        </table>

        <div class="footer-note">
            Documento generado automáticamente por el sistema POS.
        </div>
    </div>
</body>
</html>
