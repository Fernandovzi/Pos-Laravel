@extends('layouts.app')

@section('title','Ajustes de inventario')

@push('css-datatable')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')
<div class="container-fluid px-4 page-shell">
    <x-ui.page-header title="Crear Categoría" />
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ajustes-inventario.index')}}">Ajustes de inventario</a></li>
        <li class="breadcrumb-item active">Crear ajuste</li>
    </ol>

    @can('crear-ajuste-inventario')
    <div class="page-toolbar mb-4">
        <a href="{{ route('ajustes-inventario.create') }}" class="btn btn-primary btn-ui">Nuevo ajuste</a>
    </div>
    @endcan

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('ajustes-inventario.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="fecha_inicio" class="form-label">Fecha inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
                </div>
                <div class="col-md-3">
                    <label for="fecha_fin" class="form-label">Fecha fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
                </div>
                <div class="col-md-3">
                    <label for="producto_id" class="form-label">Producto</label>
                    <select name="producto_id" id="producto_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($productos as $producto)
                        <option value="{{ $producto->id }}" {{ (string)request('producto_id') === (string)$producto->id ? 'selected' : '' }}>
                            {{ $producto->nombre_completo }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="user_id" class="form-label">Usuario</label>
                    <select name="user_id" id="user_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ (string)request('user_id') === (string)$usuario->id ? 'selected' : '' }}>
                            {{ $usuario->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary">Filtrar</button>
                    <a href="{{ route('ajustes-inventario.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                    <button type="submit" name="exportar" value="1" class="btn btn-success">Exportar CSV</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fa-solid fa-sliders"></i>
            Historial de ajustes
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped fs-6">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Usuario</th>
                        <th>Stock sistema</th>
                        <th>Cantidad física</th>
                        <th>Diferencia</th>
                        <th>Movimiento</th>
                        <th>Motivo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ajustes as $ajuste)
                    <tr>
                        <td>{{ $ajuste->created_at?->format('d/m/Y H:i') }}</td>
                        <td>{{ $ajuste->producto?->nombre_completo }}</td>
                        <td>{{ $ajuste->user?->name }}</td>
                        <td>{{ $ajuste->stock_actual }}</td>
                        <td>{{ $ajuste->cantidad_fisica }}</td>
                        <td>{{ $ajuste->diferencia }}</td>
                        <td>{{ $ajuste->tipo_movimiento }}</td>
                        <td>{{ $ajuste->motivo }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush
