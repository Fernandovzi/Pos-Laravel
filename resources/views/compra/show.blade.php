@extends('layouts.app')

@section('title','Ver producción interna')

@section('content')
<div class="container-fluid px-4 page-shell">
    <x-ui.page-header title="Detalle de producción interna" subtitle="Revisa el registro, usuario responsable y los productos ingresados a inventario." />
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('compras.index') }}">Producción interna</a></li>
        <li class="breadcrumb-item active">Detalle</li>
    </ol>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <x-ui.card title="Datos generales" subtitle="Información principal del registro de producción interna.">
                <div class="row g-3">
                    <div class="col-md-3">
                        <p class="text-muted text-uppercase small fw-semibold mb-2">Registro</p>
                        <p class="fw-semibold mb-0">#{{ $compra->id }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="text-muted text-uppercase small fw-semibold mb-2">Usuario</p>
                        <p class="fw-semibold mb-0">{{ $compra->user->name }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="text-muted text-uppercase small fw-semibold mb-2">Fecha y hora</p>
                        <p class="fw-semibold mb-0">{{ $compra->fecha }} {{ $compra->hora }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="text-muted text-uppercase small fw-semibold mb-2">Total</p>
                        <p class="fw-semibold mb-0">{{ $compra->total }} {{ $empresa->moneda->simbolo }}</p>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
                <i class="fas fa-table me-1"></i>
                Detalle de productos ingresados
            </div>
            <span class="text-muted small">{{ $compra->productos->count() }} productos</span>
        </div>
        <div class="card-body">
            <table class="table table-striped align-middle mb-0">
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
                        <td class="fw-semibold">{{ $item->nombre }}</td>
                        <td>{{ $item->presentacione->sigla }}</td>
                        <td class="text-end">{{ $item->pivot->cantidad }}</td>
                        <td class="text-end">{{ $item->pivot->precio_compra }}</td>
                        <td class="text-end">{{ $item->pivot->cantidad * $item->pivot->precio_compra }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
