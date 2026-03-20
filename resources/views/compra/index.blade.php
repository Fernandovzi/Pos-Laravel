@extends('layouts.app')

@section('title','produccion-interna')

@push('css-datatable')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')

<div class="container-fluid px-4 page-shell">
    <x-ui.page-header title="Producción Interna" />
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Producción Interna</li>
    </ol>

    @can('crear-compra')
    <div class="page-toolbar mb-4">
        <a href="{{route('compras.create')}}">
            <button type="button" class="btn btn-primary btn-ui">Registrar entrada</button>
        </a>
    </div>
    @endcan

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
                    <option value="{{ $producto->id }}" @selected(request('producto_id')==$producto->id)>{{ $producto->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Usuario</label>
                <select name="user_id" class="form-select">
                    <option value="">Todos</option>
                    @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->id }}" @selected(request('user_id')==$usuario->id)>{{ $usuario->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 d-flex justify-content-end gap-2">
                <x-ui.button variant="primary" icon="fa-solid fa-filter" type="submit" class="text-white">Filtrar</x-ui.button>
                <a href="{{ route('compras.index') }}">
                    <x-ui.button variant="primary" icon="fa-solid fa-rotate-right" class="text-white">Limpiar</x-ui.button>
                </a>
            </div>
        </form>
    </x-ui.card>

    <div class="card">

        <div class="card-header">
            <i class="fa-solid fa-store"></i>
            Producciones registradas
        </div>

        <div class="card-body">

            <table id="datatablesSimple" class="table table-striped fs-6">
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
                        <td>
                            <p class="fw-semibold mb-1"><i class="fa-solid fa-calendar-days me-1"></i>{{ $item->fecha }}</p>
                            <p class="text-muted mb-0"><i class="fa-solid fa-clock me-1"></i>{{ $item->hora }}</p>
                        </td>
                        <td>{{ $item->user->name }}</td>
                        <td>
                            @foreach($item->productos as $producto)
                     
                                Producto: {{ $producto->nombre }} - Cantidad: ({{ $producto->pivot->cantidad }})
                           
                            @endforeach
                        </td>
                        <td class="text-end">{{ $item->total }}</td>
                        <td>
                            <div class="d-flex justify-content-around align-items-center">
                                <div>
                                    <button title="Opciones" class="btn btn-datatable btn-icon btn-transparent-dark me-2" data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg class="svg-inline--fa fa-ellipsis-vertical" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="ellipsis-vertical" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 512">
                                            <path fill="currentColor" d="M56 472a56 56 0 1 1 0-112 56 56 0 1 1 0 112zm0-160a56 56 0 1 1 0-112 56 56 0 1 1 0 112zM0 96a56 56 0 1 1 112 0A56 56 0 1 1 0 96z"></path>
                                        </svg>
                                    </button>
                                    <ul class="dropdown-menu text-bg-light dropdown-menu-sm">
                                        <li><a class="dropdown-item" href="{{ route('compras.show', $item) }}">Ver detalle</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <div class="vr"></div>
                                </div>
                                <div>
                                    <a href="{{ route('compras.show', $item) }}" title="Ver" class="btn btn-datatable btn-icon btn-transparent-dark">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Sin registros
                        </td>
                    </tr>
                    @endforelse
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
