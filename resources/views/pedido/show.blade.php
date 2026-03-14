@extends('layouts.app')

@section('title','Detalle pedido')

@section('content')
<div class="container-fluid px-4 page-shell">
    <x-ui.page-header :title="'Pedido '.$pedido->folio">
        <x-slot name="actions">
            <a href="{{ route('pedidos.pdf', $pedido) }}" target="_blank">
                <x-ui.button variant="secondary" icon="fa-solid fa-file-pdf">Descargar PDF</x-ui.button>
            </a>
        </x-slot>
    </x-ui.page-header>

    <x-ui.breadcrumbs :items="[
        ['href' => route('panel'), 'label' => 'Inicio'],
        ['href' => route('pedidos.index'), 'label' => 'Pedidos'],
        ['label' => 'Detalle', 'active' => true]
    ]" />

    <x-ui.card title="Datos del pedido">
        <div class="row g-3">
            <div class="col-md-4"><strong>Proveedor:</strong> {{ optional($pedido->proveedore)->nombre_documento ?? optional($pedido->cliente)->nombre_documento ?? 'N/D' }}</div>
            <div class="col-md-3"><strong>Persona que recoge:</strong> {{ $pedido->persona_recojo }}</div>
            <div class="col-md-2"><strong>Estado:</strong> {{ $pedido->estado->value }}</div>
            <div class="col-md-3"><strong>Fecha:</strong> {{ $pedido->fecha_format }}</div>
        </div>
    </x-ui.card>

    <x-ui.table title="Productos del pedido">
        <thead>
            <tr><th>Producto</th><th class="text-end">Cantidad</th><th class="text-end">Precio</th><th class="text-end">Subtotal</th></tr>
        </thead>
        <tbody>
            @foreach($pedido->productos as $producto)
            <tr>
                <td>{{ $producto->nombre }}</td>
                <td class="text-end">{{ $producto->pivot->cantidad }}</td>
                <td class="text-end">{{ number_format($producto->pivot->precio, 2) }}</td>
                <td class="text-end">{{ number_format($producto->pivot->cantidad * $producto->pivot->precio, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr><th colspan="3" class="text-end">Total</th><th class="text-end">{{ number_format($pedido->total, 2) }}</th></tr>
        </tfoot>
    </x-ui.table>
</div>
@endsection
