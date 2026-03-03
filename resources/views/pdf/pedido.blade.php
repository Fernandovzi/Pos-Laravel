<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Pedido {{ $pedido->folio }}</title></head>
<body>
    <h2>{{ $empresa->nombre ?? 'Empresa' }}</h2>
    <h3>Pedido {{ $pedido->folio }}</h3>
    <p><strong>Cliente:</strong> {{ $pedido->cliente->persona->razon_social }}</p>
    <p><strong>RFC:</strong> {{ $pedido->cliente->persona->rfc ?? 'N/D' }}</p>
    <p><strong>Persona que recoge:</strong> {{ $pedido->persona_recojo }}</p>
    <p><strong>Fecha:</strong> {{ $pedido->fecha_format }}</p>
    <p><strong>Estado:</strong> {{ $pedido->estado->value }}</p>

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

    <p><strong>Subtotal:</strong> {{ number_format($pedido->subtotal, 2) }}</p>
    <p><strong>Impuesto:</strong> {{ number_format($pedido->impuesto, 2) }}</p>
    <p><strong>Total:</strong> {{ number_format($pedido->total, 2) }}</p>
</body>
</html>
