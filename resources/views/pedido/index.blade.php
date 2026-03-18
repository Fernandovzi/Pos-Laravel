@extends('layouts.app')

@section('title','Pedidos')

@section('content')

<div class="container-fluid px-4 page-shell">
    <x-ui.page-header title="Pedidos" />
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Pedidos</li>
    </ol>

    @can('crear-presentacione')
    <div class="page-toolbar mb-4">
        <a href="{{route('pedidos.create')}}">
            <button type="button" class="btn btn-primary btn-ui">Nuevo pedido</button>
        </a>
    </div>
    @endcan

    <x-ui.table title="Listado de pedidos">
        <thead>
            <tr>
                <th>Folio</th>
                <th>Proveedor</th>
                <th>Persona de recojo</th>
                <th>Estado</th>
                <th class="text-end">Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedidos as $pedido)
            <tr>
                <td>{{ $pedido->folio }}</td>
                <td>{{ optional($pedido->proveedore)->nombre_documento ?? optional($pedido->cliente)->nombre_documento ?? 'N/D' }}</td>
                <td>{{ $pedido->persona_recojo }}</td>
                <td>{{ $pedido->estado->value }}</td>
                <td class="text-end">{{ number_format($pedido->total, 2) }}</td>
                <td>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('pedidos.show', $pedido) }}">
                            <x-ui.button variant="success" icon="fa-solid fa-eye">Ver</x-ui.button>
                        </a>
                        <a href="{{ route('pedidos.pdf', $pedido) }}" target="_blank">
                            <x-ui.button variant="secondary" icon="fa-solid fa-file-pdf">PDF</x-ui.button>
                        </a>
                        @if($pedido->estado->value === 'APARTADO')
                        <form action="{{ route('pedidos.destroy', $pedido) }}" method="POST" onsubmit="return confirm('¿Cancelar pedido y liberar stock?')">
                            @csrf
                            @method('DELETE')
                            <x-ui.button variant="danger" icon="fa-solid fa-ban" type="submit">Cancelar</x-ui.button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </x-ui.table>
</div>
@endsection