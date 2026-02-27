@extends('layouts.app')

@section('title','Pedidos')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Pedidos</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Pedidos</li>
    </ol>

    <a href="{{ route('pedidos.create') }}" class="btn btn-primary mb-3">Nuevo pedido</a>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Cliente</th>
                        <th>Persona de recojo</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)
                    <tr>
                        <td>{{ $pedido->folio }}</td>
                        <td>{{ $pedido->cliente->nombre_documento }}</td>
                        <td>{{ $pedido->persona_recojo }}</td>
                        <td>{{ $pedido->estado->value }}</td>
                        <td>{{ number_format($pedido->total, 2) }}</td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('pedidos.show', $pedido) }}" class="btn btn-sm btn-info">Ver</a>
                            <a href="{{ route('pedidos.pdf', $pedido) }}" class="btn btn-sm btn-secondary" target="_blank">PDF</a>
                            @if($pedido->estado->value === 'APARTADO')
                            <form action="{{ route('pedidos.destroy', $pedido) }}" method="POST" onsubmit="return confirm('¿Cancelar pedido y liberar stock?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Cancelar</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
