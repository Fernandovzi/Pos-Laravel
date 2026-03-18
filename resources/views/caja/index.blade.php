@extends('layouts.app')

@section('title','cajas')

@push('css-datatable')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')


<div class="container-fluid px-4 page-shell">
    <x-ui.page-header title="Cajas" />
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Cajas</li>
    </ol>

    @can('aperturar-caja')
    <div class="page-toolbar mb-4">
        <a href="{{route('cajas.create')}}">
            <button type="button" class="btn btn-primary btn-ui">Aperturar caja</button>
        </a>
    </div>
    @endcan


    <div class="card">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla cajas
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped fs-6">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apertura</th>
                        <th>Cierre</th>
                        <th>Saldo inicial</th>
                        <th>Saldo final</th>
                        <th>Estado</th>
                        <th>Efectivo</th>
                        <th>Débito</th>
                        <th>Crédito</th>
                        <th>Transferencia</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cajas as $item)
                    @php
                    $ventas = $item->movimientos->filter(fn($mov) => $mov->tipo->value === 'VENTA');
                    @endphp
                    <tr>
                        <td>{{$item->nombre}}</td>
                        <td>
                            <p class="fw-semibold mb-1">
                                <span class="m-1"><i class="fa-solid fa-calendar-days"></i></span>
                                {{$item->fecha_apertura}}
                            </p>
                            <p class="fw-semibold mb-0"><span class="m-1"><i class="fa-solid fa-clock"></i></span>
                                {{$item->hora_apertura}}
                            </p>
                        </td>
                        <td>
                            @if ($item->fecha_hora_cierre)
                            <p class="fw-semibold mb-1">
                                <span class="m-1"><i class="fa-solid fa-calendar-days"></i></span>
                                {{$item->fecha_cierre}}
                            </p>
                            <p class="fw-semibold mb-0"><span class="m-1"><i class="fa-solid fa-clock"></i></span>
                                {{$item->hora_cierre}}
                            </p>
                            @else
                            <span class="text-muted">Pendiente</span>
                            @endif
                        </td>
                        <td>{{$item->saldo_inicial}}</td>
                        <td>{{$item->saldo_final}}</td>
                        <td>
                            <span class="badge rounded-pill {{ $item->estado == 1 ? 'text-bg-success' : 'text-bg-danger' }}">
                                {{$item->estado == 1 ? 'Aperturada' : 'Cerrada'}}
                            </span>
                        </td>
                        <td>{{$ventas->where('metodo_pago.value', 'EFECTIVO')->sum('monto')}}</td>
                        <td>{{$ventas->where('metodo_pago.value', 'TARJETA_DEBITO')->sum('monto')}}</td>
                        <td>{{$ventas->where('metodo_pago.value', 'TARJETA_CREDITO')->sum('monto')}}</td>
                        <td>{{$ventas->where('metodo_pago.value', 'TRANSFERENCIA_SPEI')->sum('monto')}}</td>
                        <td>
                            <div class="d-flex justify-content-around align-items-center">
                                <div>
                                    <button title="Opciones" class="btn btn-datatable btn-icon btn-transparent-dark me-2" data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg class="svg-inline--fa fa-ellipsis-vertical" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="ellipsis-vertical" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 512">
                                            <path fill="currentColor" d="M56 472a56 56 0 1 1 0-112 56 56 0 1 1 0 112zm0-160a56 56 0 1 1 0-112 56 56 0 1 1 0 112zM0 96a56 56 0 1 1 112 0A56 56 0 1 1 0 96z"></path>
                                        </svg>
                                    </button>
                                    <ul class="dropdown-menu text-bg-light dropdown-menu-sm">
                                        @can('ver-movimiento')
                                        <li>
                                            <form action="{{route('movimientos.index')}}" method="get">
                                                <input type="hidden" name="caja_id" value="{{$item->id}}">
                                                <button type="submit" class="dropdown-item">Ver movimientos</button>
                                            </form>
                                        </li>
                                        @endcan
                                    </ul>
                                </div>
                                <div>
                                    <div class="vr"></div>
                                </div>
                                <div>
                                    @can('cerrar-caja')
                                    @if ($item->estado == 1)
                                    <button type="button" title="Cerrar caja" class="btn btn-datatable btn-icon btn-transparent-dark" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$item->id}}">
                                        <i class="fa-solid fa-lock"></i>
                                    </button>
                                    @else
                                    <button type="button" title="Caja cerrada" class="btn btn-datatable btn-icon btn-transparent-dark" disabled>
                                        <i class="fa-solid fa-lock"></i>
                                    </button>
                                    @endif
                                    @endcan
                                </div>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="confirmModal-{{$item->id}}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5">Mensaje de confirmación</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    ¿Seguro que quieres cerrar la caja?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                                    <form action="{{route('cajas.destroy',['caja' => $item->id])}}" method="post">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Confirmar</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
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
