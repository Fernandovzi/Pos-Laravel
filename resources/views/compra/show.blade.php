@extends('layouts.app')

@section('title','Ver producción interna')

@section('content')
<div class="container-fluid px-4 page-shell">
    <x-ui.page-header title="Detalle de Producción Interna" />

    <x-ui.breadcrumbs :items="[
        ['href' => route('panel'), 'label' => 'Inicio'],
        ['href' => route('compras.index'), 'label' => 'Producción Interna'],
        ['label' => 'Detalle', 'active' => true]
    ]" />

    <x-ui.card title="Datos generales">
        <div class="row g-3">
            <div class="col-md-3"><strong>Registro:</strong> #{{ $compra->id }}</div>
            <div class="col-md-3"><strong>Usuario:</strong> {{ $compra->user->name }}</div>
            <div class="col-md-3"><strong>Fecha y hora:</strong> {{ $compra->fecha }} {{ $compra->hora }}</div>
            <div class="col-md-3"><strong>Total:</strong> {{ $compra->total }} {{ $empresa->moneda->simbolo }}</div>
        </div>
    </x-ui.card>

    <x-ui.table title="Detalle de productos ingresados">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Presentación</th>
                <th class="text-end">Cantidad</th>
                <th class="text-end">Costo unitario</th>
                <th class="text-end">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($compra->productos as $item)
            <tr>
                <td>{{ $item->nombre }}</td>
                <td>{{ $item->presentacione->sigla }}</td>
                <td class="text-end">{{ $item->pivot->cantidad }}</td>
                <td class="text-end">{{ $item->pivot->precio_compra }}</td>
                <td class="text-end">{{ $item->pivot->cantidad * $item->pivot->precio_compra }}</td>
            </tr>
            @endforeach
        </tbody>
    </x-ui.table>
</div>
@endsection
