@extends('layouts.app')

@section('title','ventas')

@push('css-datatable')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush
@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')

<div class="container-fluid px-4 page-shell">
    <x-ui.page-header title="Ventas" />
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Ventas</li>
    </ol>

    @can('crear-venta')
    <div class="page-toolbar mb-4">
        <a href="{{route('ventas.create')}}">
            <button type="button" class="btn btn-primary btn-ui">Añadir venta</button>
        </a>

        <a href="{{ route('export.excel-ventas-all') }}">
            <x-ui.button variant="success" icon="fa-solid fa-file-excel" class="text-white">Exportar Excel</x-ui.button>
        </a>
    </div>
    @endcan

    <div class="card">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla de ventas
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped fs-6 align-middle">
                <thead>
                    <tr>
                        <th>Comprobante</th>
                        <th>Cliente</th>
                        <th>Fecha y hora</th>
                        <th>Vendedor</th>
                        <th>Estado</th>
                        <th class="text-end">Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ventas as $item)
                    <tr>
                        <td>
                            <p class="fw-semibold mb-1">{{ $item->comprobante->nombre }}</p>
                            <p class="text-muted mb-0">{{ $item->numero_comprobante }}</p>
                        </td>
                        <td>
                            <p class="fw-semibold mb-1">{{ ucfirst($item->cliente->persona->tipo->value ?? $item->cliente->persona->tipo) }}</p>
                            <p class="text-muted mb-0">{{ $item->cliente->persona->razon_social }}</p>
                        </td>
                        <td>
                            <p class="fw-semibold mb-1"><i class="fa-solid fa-calendar-days me-1"></i>{{ $item->fecha }}</p>
                            <p class="fw-semibold mb-0"><i class="fa-solid fa-clock me-1"></i>{{ $item->hora }}</p>
                        </td>
                        <td>{{ $item->user->name }}</td>
                        <td>
                            <span class="badge {{ $item->estado === 'CANCELADA' ? 'bg-danger' : 'bg-success' }}">
                                {{ $item->estado }}
                            </span>
                        </td>
                        <td class="text-end">{{ $item->total }}</td>
                        <td>
                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                @can('mostrar-venta')
                                <a href="{{ route('ventas.show', ['venta' => $item]) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fa-solid fa-eye me-1"></i>Ver detalle
                                </a>
                                @endcan

                                <a class="btn btn-outline-danger btn-sm" href="{{ route('export.pdf-comprobante-venta', ['id' => Crypt::encrypt($item->id)]) }}" target="_blank">
                                    <i class="fa-solid fa-file-pdf me-1"></i>PDF
                                </a>

                                @can('eliminar-venta')
                                    @if($item->estado !== 'CANCELADA')
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#confirmVentaModal-{{ $item->id }}">
                                        <i class="fa-solid fa-ban me-1"></i>Cancelar venta
                                    </button>
                                    @else
                                    <span class="text-muted small">Venta cancelada</span>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>

                    @can('eliminar-venta')
                        @if($item->estado !== 'CANCELADA')
                        <div class="modal fade" id="confirmVentaModal-{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5">Mensaje de confirmación</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        ¿Cancelar venta y regresar movimientos a caja, existencias y kardex?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                        <form action="{{ route('ventas.destroy', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Confirmar cancelación</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endcan
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
