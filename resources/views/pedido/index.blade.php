@extends('layouts.app')

@section('title','Pedidos')

@push('css-datatable')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

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

    <div class="card">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Listado de pedidos
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped fs-6">
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
                        <td>
                            <span class="badge rounded-pill text-bg-{{ $pedido->estado->value === 'APARTADO' ? 'warning' : ($pedido->estado->value === 'CANCELADO' ? 'danger' : 'success') }}">
                                {{ $pedido->estado->value }}
                            </span>
                        </td>
                        <td class="text-end">{{ number_format($pedido->total, 2) }}</td>
                        <td>
                            <div class="d-flex justify-content-around align-items-center">
                                <div>
                                    <button title="Opciones" class="btn btn-datatable btn-icon btn-transparent-dark me-2" data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg class="svg-inline--fa fa-ellipsis-vertical" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="ellipsis-vertical" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 512">
                                            <path fill="currentColor" d="M56 472a56 56 0 1 1 0-112 56 56 0 1 1 0 112zm0-160a56 56 0 1 1 0-112 56 56 0 1 1 0 112zM0 96a56 56 0 1 1 112 0A56 56 0 1 1 0 96z"></path>
                                        </svg>
                                    </button>
                                    <ul class="dropdown-menu text-bg-light dropdown-menu-sm">
                                        <li><a class="dropdown-item" href="{{ route('pedidos.show', $pedido) }}">Ver detalle</a></li>
                                        <li><a class="dropdown-item" href="{{ route('pedidos.pdf', $pedido) }}" target="_blank">Descargar PDF</a></li>
                                    </ul>
                                </div>
                                @if($pedido->estado->value === 'APARTADO')
                                <div>
                                    <div class="vr"></div>
                                </div>
                                <div>
                                    <button type="button" title="Cancelar pedido" class="btn btn-datatable btn-icon btn-transparent-dark" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $pedido->id }}">
                                        <i class="fa-solid fa-ban"></i>
                                    </button>
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>

                    @if($pedido->estado->value === 'APARTADO')
                    <div class="modal fade" id="confirmModal-{{ $pedido->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5">Mensaje de confirmación</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    ¿Cancelar pedido y liberar stock?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <form action="{{ route('pedidos.destroy', $pedido) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Confirmar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
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
