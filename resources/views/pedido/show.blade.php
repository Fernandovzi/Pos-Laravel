@extends('layouts.app')

@section('title','Detalle pedido')

@section('content')
<div class="container-fluid px-4 page-shell">
    <x-ui.page-header :title="'Pedido ' . $pedido->folio" subtitle="Consulta la información general del pedido y el detalle de productos apartados." />

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('pedidos.index') }}">Pedidos</a></li>
        <li class="breadcrumb-item active">Detalle pedido</li>
    </ol>

    <div class="page-toolbar mb-4">
        <a href="{{ route('pedidos.pdf', $pedido) }}" target="_blank" class="btn btn-secondary btn-ui text-white">
            <i class="fa-solid fa-file-pdf me-1"></i>Descargar PDF
        </a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <x-ui.card title="Datos del pedido" subtitle="Resumen del proveedor, estado y fecha del apartado.">
                <div class="row g-3">
                    <div class="col-md-4">
                        <p class="text-muted text-uppercase small fw-semibold mb-2">Proveedor / cliente</p>
                        <p class="fw-semibold mb-0">{{ optional($pedido->proveedore)->nombre_documento ?? optional($pedido->cliente)->nombre_documento ?? 'N/D' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="text-muted text-uppercase small fw-semibold mb-2">Persona que recoge</p>
                        <p class="fw-semibold mb-0">{{ $pedido->persona_recojo }}</p>
                    </div>
                    <div class="col-md-2">
                        <p class="text-muted text-uppercase small fw-semibold mb-2">Estado</p>
                        <span class="badge rounded-pill text-bg-{{ $pedido->estado->value === 'APARTADO' ? 'success' : ($pedido->estado->value === 'CANCELADO' ? 'danger' : 'success') }}">
                            {{ $pedido->estado->value }}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <p class="text-muted text-uppercase small fw-semibold mb-2">Fecha</p>
                        <p class="fw-semibold mb-0">{{ $pedido->fecha_format }}</p>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
                <i class="fas fa-table me-1"></i>
                Productos del pedido
            </div>
            <span class="text-muted small">{{ $pedido->productos->count() }} productos</span>
        </div>
        <div class="card-body">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th class="text-end">Cantidad</th>
                        <th class="text-end">Precio</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedido->productos as $producto)
                    <tr>
                        <td class="fw-semibold">{{ $producto->nombre }}</td>
                        <td class="text-end">{{ $producto->pivot->cantidad }}</td>
                        <td class="text-end">{{ number_format($producto->pivot->precio, 2) }}</td>
                        <td class="text-end">{{ number_format($producto->pivot->cantidad * $producto->pivot->precio, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th class="text-end">{{ number_format($pedido->total, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
