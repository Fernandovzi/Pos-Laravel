<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pedido {{ $pedido->folio }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 12px; }
        .header { width: 100%; margin-bottom: 16px; }
        .header td { vertical-align: top; }
        .logo { width: 120px; }
        .title { text-align: right; }
        .title h2 { margin: 0; color: #111827; }
        .meta { background: #f3f4f6; border: 1px solid #e5e7eb; padding: 10px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #111827; color: #fff; text-align: left; }
        th, td { border: 1px solid #d1d5db; padding: 6px; }
        .totales { margin-top: 12px; width: 45%; margin-left: auto; }
        .totales td { border: none; padding: 4px 0; }
        .totales .label { text-align: right; font-weight: bold; padding-right: 8px; }
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
    @endphp

    <table class="header">
        <tr>
            <td>
                @if(file_exists($logoPath))
                    <img src="{{ $logoPath }}" class="logo" alt="Maleri">
                @endif
            </td>
            <td class="title">
                <h2>{{ $empresa->nombre ?? 'Maleri' }}</h2>
                <div>Pedido {{ $pedido->folio }}</div>
            </td>
        </tr>
    </table>

    <div class="meta">
        <p><strong>Proveedor:</strong> {{ $proveedorNombre }}</p>
        <p><strong>RFC:</strong> {{ $proveedorRfc }}</p>
        <p><strong>Persona que recoge:</strong> {{ $pedido->persona_recojo }}</p>
        <p><strong>Fecha:</strong> {{ $pedido->fecha_format }}</p>
        <p><strong>Estado:</strong> {{ $pedido->estado->value }}</p>
    </div>

    <table width="100%" border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr>
        </thead>
        <tbody>
            @foreach($pedido->productos as $producto)
            <tr>
                <td>{{ $producto->nombre }}</td>
                <td>{{ $producto->pivot->cantidad }}</td>
                <td>{{ number_format($producto->pivot->precio, 2) }}</td>
                <td>{{ number_format($producto->pivot->cantidad * $producto->pivot->precio, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totales">
        <tr><td class="label">Subtotal:</td><td>{{ number_format($pedido->subtotal, 2) }}</td></tr>
        <tr><td class="label">Impuesto:</td><td>{{ number_format($pedido->impuesto, 2) }}</td></tr>
        <tr><td class="label">Total:</td><td><strong>{{ number_format($pedido->total, 2) }}</strong></td></tr>
    </table>
</body>
</html>
