@extends('layouts.app')

@section('title','Detalle pedido')

@section('content')
<div class="container-fluid px-4 page-shell">
    <x-ui.page-header :title="'Pedido '.$pedido->folio" />

    <p><b>Proveedor:</b> {{ optional($pedido->proveedore)->nombre_documento ?? optional($pedido->cliente)->nombre_documento ?? 'N/D' }}</p>
    <p><b>Persona que recoge:</b> {{ $pedido->persona_recojo }}</p>
    <p><b>Estado:</b> {{ $pedido->estado->value }}</p>
    <p><b>Fecha:</b> {{ $pedido->fecha_format }}</p>

    <table class="table table-bordered">
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

    <p><b>Total:</b> {{ number_format($pedido->total, 2) }}</p>
    <a href="{{ route('pedidos.pdf', $pedido) }}" target="_blank" class="btn btn-secondary">Descargar PDF</a>
</div>
@endsection
