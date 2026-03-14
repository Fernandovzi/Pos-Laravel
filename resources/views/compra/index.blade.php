@extends('layouts.app')

@section('title','produccion-interna')

@section('content')
<div class="container-fluid px-4 page-shell">
    <x-ui.page-header title="Producción Interna">
        <x-slot name="actions">
            @can('crear-compra')
                <a href="{{ route('compras.create') }}">
                    <x-ui.button variant="primary" icon="fa-solid fa-plus">Registrar entrada</x-ui.button>
                </a>
            @endcan
        </x-slot>
    </x-ui.page-header>

    <x-ui.breadcrumbs :items="[['href' => route('panel'), 'label' => 'Inicio'], ['label' => 'Producción Interna', 'active' => true]]" />

    <x-ui.card title="Filtros">
        <form method="GET" class="row g-4">
            <div class="col-md-3">
                <label class="form-label">Fecha inicio</label>
                <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Fecha fin</label>
                <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Producto</label>
                <select name="producto_id" class="form-select">
                    <option value="">Todos</option>
                    @foreach($productos as $producto)
                    <option value="{{ $producto->id }}" @selected(request('producto_id') == $producto->id)>{{ $producto->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Usuario</label>
                <select name="user_id" class="form-select">
                    <option value="">Todos</option>
                    @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->id }}" @selected(request('user_id') == $usuario->id)>{{ $usuario->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 d-flex justify-content-end gap-2">
                <x-ui.button variant="secondary" icon="fa-solid fa-filter" type="submit">Filtrar</x-ui.button>
                <a href="{{ route('compras.index') }}">
                    <x-ui.button variant="secondary" icon="fa-solid fa-rotate-right">Limpiar</x-ui.button>
                </a>
            </div>
        </form>
    </x-ui.card>

    <x-ui.table title="Producciones registradas">
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha y hora</th>
                <th>Usuario</th>
                <th>Productos</th>
                <th class="text-end">Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($compras as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->fecha }} {{ $item->hora }}</td>
                <td>{{ $item->user->name }}</td>
                <td>
                    @foreach($item->productos as $producto)
                    <span class="badge bg-primary">{{ $producto->nombre }} ({{ $producto->pivot->cantidad }})</span>
                    @endforeach
                </td>
                <td class="text-end">{{ $item->total }}</td>
                <td>
                    <a href="{{ route('compras.show', $item) }}">
                        <x-ui.button variant="success" icon="fa-solid fa-eye">Ver</x-ui.button>
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted">Sin registros</td></tr>
            @endforelse
        </tbody>
    </x-ui.table>
</div>
@endsection
