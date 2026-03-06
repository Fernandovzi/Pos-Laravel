@extends('layouts.app')

@section('title','produccion-interna')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Producción Interna</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Producción Interna</li>
    </ol>

    @can('crear-compra')
    <div class="mb-3">
        <a href="{{ route('compras.create') }}" class="btn btn-primary">Registrar entrada</a>
    </div>
    @endcan

    <div class="card mb-4">
        <div class="card-header">Filtros</div>
        <div class="card-body">
            <form method="GET" class="row g-3">
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
                <div class="col-12">
                    <button class="btn btn-secondary">Filtrar</button>
                    <a href="{{ route('compras.index') }}" class="btn btn-light">Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Producciones</div>
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha y hora</th>
                        <th>Usuario</th>
                        <th>Productos</th>
                        <th>Total</th>
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
                        <td>{{ $item->total }}</td>
                        <td><a href="{{ route('compras.show', $item) }}" class="btn btn-success btn-sm">Ver</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">Sin registros</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
