@extends('layouts.app')

@section('title','Ver producción interna')

@section('content')
<div class="container-fluid px-4 page-shell">
    <h1 class="mt-4 text-center">Detalle de Producción Interna</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('compras.index') }}">Producción Interna</a></li>
        <li class="breadcrumb-item active">Detalle</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">Datos generales</div>
        <div class="card-body">
            <p><strong>Registro:</strong> #{{ $compra->id }}</p>
            <p><strong>Usuario:</strong> {{ $compra->user->name }}</p>
            <p><strong>Fecha y hora:</strong> {{ $compra->fecha }} {{ $compra->hora }}</p>
            <p><strong>Total:</strong> {{ $compra->total }} {{ $empresa->moneda->simbolo }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Detalle de productos ingresados</div>
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Presentación</th>
                        <th>Cantidad</th>
                        <th>Costo unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($compra->productos as $item)
                    <tr>
                        <td>{{ $item->nombre }}</td>
                        <td>{{ $item->presentacione->sigla }}</td>
                        <td>{{ $item->pivot->cantidad }}</td>
                        <td>{{ $item->pivot->precio_compra }}</td>
                        <td>{{ $item->pivot->cantidad * $item->pivot->precio_compra }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
