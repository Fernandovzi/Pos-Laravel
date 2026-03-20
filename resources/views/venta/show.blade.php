@extends('layouts.app')

@section('title','Ver venta')

@section('content')
<div class="container-fluid px-4 page-shell">
    <x-ui.page-header title="Detalle de Venta" />
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ventas.index')}}">Ventas</a></li>
        <li class="breadcrumb-item active">Detalle</li>
    </ol>

    <div class="page-toolbar mb-4">
        <a href="{{ route('export.pdf-comprobante-venta',['id' => Crypt::encrypt($venta->id)]) }}" target="_blank" class="btn btn-secondary btn-ui text-white">
            <i class="fa-solid fa-file-pdf me-1"></i> Descargar PDF
        </a>

        @can('eliminar-venta')
            @if($venta->estado !== 'CANCELADA')
            <form action="{{ route('ventas.destroy', $venta) }}" method="post" onsubmit="return confirm('¿Deseas cancelar esta venta? Se regresará caja, inventario y kardex.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-ui">Cancelar venta</button>
            </form>
            @endif
        @endcan
    </div>

    <x-ui.card title="Datos generales de la venta">
        <p class="mb-2"><strong>Comprobante:</strong> {{$venta->comprobante->nombre}} ({{$venta->numero_comprobante}})</p>
        <p class="mb-2"><strong>Cliente:</strong> {{$venta->cliente->persona->razon_social}}</p>
        <p class="mb-2"><strong>Vendedor:</strong> {{$venta->user->name}}</p>
        <p class="mb-2"><strong>Método principal:</strong> {{$venta->metodo_pago?->label()}}</p>
        <p class="mb-2"><strong>Estado:</strong> <span class="badge {{$venta->estado === 'CANCELADA' ? 'bg-danger' : 'bg-success'}}">{{$venta->estado}}</span></p>
        <p class="mb-3"><strong>Fecha y hora:</strong> {{$venta->fecha}} - {{$venta->hora}}</p>

        <h6 class="mb-2">Desglose de pagos</h6>
        <ul class="list-group">
            @foreach ($venta->pagos as $pago)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>{{$pago->metodo_pago->label()}} @if($pago->referencia) - Ref: {{$pago->referencia}} @endif</span>
                <strong>{{$pago->monto}} {{$empresa->moneda->simbolo}}</strong>
            </li>
            @endforeach
        </ul>

        @if($venta->vuelto_entregado > 0)
        <div class="alert alert-info mt-3 mb-0">Cambio entregado: {{$venta->vuelto_entregado}} {{$empresa->moneda->simbolo}}</div>
        @endif
    </x-ui.card>

    <x-ui.table title="Detalle de productos vendidos">
        <thead>
            <tr><th>Producto</th><th>Presentación</th><th class="text-end">Cantidad</th><th class="text-end">Precio de venta</th><th class="text-end">Subtotal</th></tr>
        </thead>
        <tbody>
            @foreach ($venta->productos as $item)
            <tr>
                <td>{{$item->nombre}}</td>
                <td>{{$item->presentacione->sigla}}</td>
                <td class="text-end">{{$item->pivot->cantidad}}</td>
                <td class="text-end">{{$item->pivot->precio_venta}}</td>
                <td class="text-end">{{($item->pivot->cantidad) * ($item->pivot->precio_venta)}}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr><th colspan="4" class="text-end">Sumas</th><th class="text-end">{{$venta->subtotal}} {{$empresa->moneda->simbolo}}</th></tr>
            <tr><th colspan="4" class="text-end">Impuesto</th><th class="text-end">{{$venta->impuesto}} {{$empresa->moneda->simbolo}}</th></tr>
            <tr><th colspan="4" class="text-end">Total</th><th class="text-end">{{$venta->total}} {{$empresa->moneda->simbolo}}</th></tr>
        </tfoot>
    </x-ui.table>
</div>
@endsection
