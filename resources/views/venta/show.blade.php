@extends('layouts.app')

@section('title','Ver venta')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Ver Venta</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ventas.index')}}">Ventas</a></li>
        <li class="breadcrumb-item active">Ver Venta</li>
    </ol>
</div>

<div class="container-fluid">
    <div class="card mb-4">
        <div class="card-header">Datos generales de la venta</div>
        <div class="card-body">
            <h6 class="card-subtitle mb-2 text-body-secondary">Comprobante: {{$venta->comprobante->nombre}} ({{$venta->numero_comprobante}})</h6>
            <h6 class="card-subtitle mb-2 text-body-secondary">Cliente: {{$venta->cliente->persona->razon_social}}</h6>
            <h6 class="card-subtitle mb-2 text-body-secondary">Vendedor: {{$venta->user->name}}</h6>
            <h6 class="card-subtitle mb-2 text-body-secondary">Método principal: {{$venta->metodo_pago?->label()}}</h6>
            <h6 class="card-subtitle mb-2 text-body-secondary">Fecha y hora: {{$venta->fecha}} - {{$venta->hora}}</h6>
            <hr>
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
        </div>
    </div>

    <div class="card mb-2">
        <div class="card-header"><i class="fas fa-table me-1"></i>Tabla de detalle de la venta</div>
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead class="bg-primary text-white">
                    <tr><th>Producto</th><th>Presentación</th><th>Cantidad</th><th>Precio de venta</th><th>Subtotal</th></tr>
                </thead>
                <tbody>
                    @foreach ($venta->productos as $item)
                    <tr>
                        <td>{{$item->nombre}}</td>
                        <td>{{$item->presentacione->sigla}}</td>
                        <td>{{$item->pivot->cantidad}}</td>
                        <td>{{$item->pivot->precio_venta}}</td>
                        <td>{{($item->pivot->cantidad) * ($item->pivot->precio_venta)}}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr><th colspan="4">Sumas:</th><th>{{$venta->subtotal}} {{$empresa->moneda->simbolo}}</th></tr>
                    <tr><th colspan="4">Impuesto:</th><th>{{$venta->impuesto}} {{$empresa->moneda->simbolo}}</th></tr>
                    <tr><th colspan="4">Total:</th><th>{{$venta->total}} {{$empresa->moneda->simbolo}}</th></tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
