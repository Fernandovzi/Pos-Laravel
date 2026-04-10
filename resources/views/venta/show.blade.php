@extends('layouts.app')

@section('title','Ver venta')

@section('content')
<div class="container-fluid px-4 page-shell">
    <x-ui.page-header title="Detalle de venta" />
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ventas.index') }}">Ventas</a></li>
        <li class="breadcrumb-item active">Detalle</li>
    </ol>

    <div class="page-toolbar mb-4">
        <a href="{{ route('export.pdf-comprobante-venta',['id' => Crypt::encrypt($venta->id)]) }}" target="_blank" class="btn btn-secondary btn-ui text-white">
            <i class="fa-solid fa-file-pdf me-1"></i>Descargar PDF
        </a>

        @can('eliminar-venta')
        @if($venta->estado !== 'CANCELADA')
        @if(!$tieneCajaAbierta)
        <div class="alert alert-warning mb-0 py-2 px-3">
            <i class="fa-solid fa-triangle-exclamation me-1"></i>
            Debe aperturar una caja para cancelar esta venta.
        </div>
        @endif
        <form action="{{ route('ventas.destroy', $venta) }}" method="post" onsubmit="return confirm('¿Deseas cancelar esta venta? Se regresará caja, inventario y kardex.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-ui" @disabled(!$tieneCajaAbierta) title="{{ !$tieneCajaAbierta ? 'Debe aperturar una caja para cancelar' : '' }}">
                <i class="fa-solid fa-ban me-1"></i>Cancelar venta
            </button>
        </form>
        @endif
        @endcan
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-7">
            <x-ui.card title="Datos generales de la venta" subtitle="Información principal del comprobante y del cliente.">
                <div class="row g-3">
                    <div class="col-md-6">
                        <p class="text-muted text-uppercase small fw-semibold mb-2">Comprobante</p>
                        <p class="fw-semibold mb-0">{{ $venta->comprobante->nombre }} ({{ $venta->numero_comprobante }})</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted text-uppercase small fw-semibold mb-2">Cliente</p>
                        <p class="fw-semibold mb-0">{{ $venta->cliente->persona->razon_social }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted text-uppercase small fw-semibold mb-2">Vendedor</p>
                        <p class="fw-semibold mb-0">{{ $venta->user->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted text-uppercase small fw-semibold mb-2">Método principal</p>
                        <p class="fw-semibold mb-0">{{ $venta->metodo_pago?->label() }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted text-uppercase small fw-semibold mb-2">Estado</p>
                        <span class="badge rounded-pill {{ $venta->estado === 'CANCELADA' ? 'text-bg-danger' : 'text-bg-success' }}">{{ $venta->estado }}</span>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted text-uppercase small fw-semibold mb-2">Fecha y hora</p>
                        <p class="fw-semibold mb-0">{{ $venta->fecha }} - {{ $venta->hora }}</p>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <div class="col-12 col-xl-5">
            <x-ui.card title="Desglose de pagos" subtitle="Métodos de pago aplicados a la venta.">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Método</th>
                                <th>Referencia</th>
                                <th class="text-end">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($venta->pagos as $pago)
                            <tr>
                                <td class="fw-semibold">{{ $pago->metodo_pago->label() }}</td>
                                <td>{{ $pago->referencia ?: 'Sin referencia' }}</td>
                                <td class="text-end">{{ $pago->monto }} {{ $empresa->moneda->simbolo }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($venta->vuelto_entregado > 0)
                <div class="alert alert-info mt-3 mb-0">Cambio entregado: {{ $venta->vuelto_entregado }} {{ $empresa->moneda->simbolo }}</div>
                @endif
            </x-ui.card>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
                <i class="fas fa-table me-1"></i>
                Detalle de productos vendidos
            </div>
            <span class="text-muted small">{{ $venta->productos->count() }} productos</span>
        </div>
        <div class="card-body">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Presentación</th>
                        <th class="text-end">Cantidad</th>
                        <th class="text-end">Precio original</th>
                        <th class="text-end">Precio de venta</th>
                        <th class="text-end">Descuento</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($venta->productos as $item)
                    <tr>
                        <td class="fw-semibold">{{ $item->nombre }}</td>
                        <td>{{ $item->presentacione->sigla }}</td>
                        <td class="text-end">{{ $item->pivot->cantidad }}</td>
                        <td class="text-end">{{ number_format($item->pivot->precio_original ?? $item->pivot->precio_venta, 2) }}</td>
                        <td class="text-end">{{ $item->pivot->precio_venta }}</td>
                        <td class="text-end">
                            @if(($item->pivot->descuento_porcentaje ?? 0) > 0)
                            {{ number_format($item->pivot->descuento_porcentaje, 2) }}%
                            @else
                            -
                            @endif
                        </td>
                        <td class="text-end">{{ ($item->pivot->cantidad) * ($item->pivot->precio_venta) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-end">Sumas</th>
                        <th class="text-end">{{ $venta->subtotal }} {{ $empresa->moneda->simbolo }}</th>
                    </tr>
                    <tr>
                        <th colspan="6" class="text-end">Descuento total</th>
                        <th class="text-end">-{{ number_format($venta->descuento_total_monto ?? 0, 2) }} {{ $empresa->moneda->simbolo }}</th>
                    </tr>
                    <tr>
                        <th colspan="6" class="text-end">Impuesto</th>
                        <th class="text-end">{{ $venta->impuesto }} {{ $empresa->moneda->simbolo }}</th>
                    </tr>
                    <tr>
                       <th colspan="6" class="text-end">Total</th>
                        <th class="text-end">{{ $venta->total }} {{ $empresa->moneda->simbolo }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection