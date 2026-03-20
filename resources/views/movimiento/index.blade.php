@extends('layouts.app')

@section('title','Movimientos de caja')

@push('css-datatable')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')
<div class="container-fluid px-4 page-shell">
    <x-ui.page-header title="Movimientos de caja" subtitle="Consulta el resumen de la caja y el detalle de ventas y retiros registrados." />

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cajas.index') }}">Cajas</a></li>
        <li class="breadcrumb-item active">Movimientos de caja</li>
    </ol>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <p class="text-muted text-uppercase small fw-semibold mb-2">Caja seleccionada</p>
                            <h2 class="h4 mb-2">{{ $caja->nombre }}</h2>
                            <div class="d-flex flex-wrap gap-3 text-muted small">
                                <span><i class="fa-solid fa-calendar-days me-1"></i>Apertura: {{ $caja->fecha_apertura }} - {{ $caja->hora_apertura }}</span>
                                @if ($caja->fecha_hora_cierre)
                                    <span><i class="fa-solid fa-lock me-1"></i>Cierre: {{ $caja->fecha_cierre }} - {{ $caja->hora_cierre }}</span>
                                @else
                                    <span><i class="fa-solid fa-lock-open me-1"></i>Caja actualmente abierta</span>
                                @endif
                            </div>
                        </div>

                        @if ($caja->estado == 1)
                            <div class="d-flex flex-wrap gap-2">
                                @can('crear-venta')
                                    <a href="{{ route('ventas.create') }}" class="btn btn-primary btn-ui">
                                        <i class="fa-solid fa-cart-plus me-1"></i>Nueva venta
                                    </a>
                                @endcan
                                @can('crear-movimiento')
                                    <a href="{{ route('movimientos.create', ['caja_id' => $caja->id]) }}" class="btn btn-success btn-ui">
                                        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Nuevo retiro
                                    </a>
                                @endcan
                                @can('cerrar-caja')
                                    <button type="button" class="btn btn-danger btn-ui" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $caja->id }}">
                                        <i class="fa-solid fa-lock me-1"></i>Cerrar caja
                                    </button>
                                @endcan
                            </div>
                        @endif
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6 col-xl-3">
                            <div class="rounded-4 border bg-light-subtle p-3 h-100">
                                <p class="text-muted small text-uppercase fw-semibold mb-2">Saldo inicial</p>
                                <h3 class="h5 mb-0">{{ $caja->saldo_inicial }}</h3>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="rounded-4 border bg-light-subtle p-3 h-100">
                                <p class="text-muted small text-uppercase fw-semibold mb-2">Saldo final</p>
                                <h3 class="h5 mb-0">{{ $caja->saldo_final ?? 'Pendiente' }}</h3>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="rounded-4 border bg-light-subtle p-3 h-100">
                                <p class="text-muted small text-uppercase fw-semibold mb-2">Estado</p>
                                <span class="badge rounded-pill {{ $caja->estado == 1 ? 'text-bg-success' : 'text-bg-danger' }}">
                                    {{ $caja->estado == 1 ? 'Aperturada' : 'Cerrada' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="rounded-4 border bg-light-subtle p-3 h-100">
                                <p class="text-muted small text-uppercase fw-semibold mb-2">Movimientos</p>
                                <h3 class="h5 mb-0">{{ $caja->movimientos->count() }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6 col-xl-3">
                            <div class="rounded-4 border h-100 p-3">
                                <p class="text-muted small text-uppercase fw-semibold mb-2">Ventas en efectivo</p>
                                <h3 class="h5 mb-0">{{ $totalesPorMetodo['EFECTIVO'] ?? 0 }}</h3>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="rounded-4 border h-100 p-3">
                                <p class="text-muted small text-uppercase fw-semibold mb-2">Ventas con débito</p>
                                <h3 class="h5 mb-0">{{ $totalesPorMetodo['TARJETA_DEBITO'] ?? 0 }}</h3>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="rounded-4 border h-100 p-3">
                                <p class="text-muted small text-uppercase fw-semibold mb-2">Ventas con crédito</p>
                                <h3 class="h5 mb-0">{{ $totalesPorMetodo['TARJETA_CREDITO'] ?? 0 }}</h3>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="rounded-4 border h-100 p-3">
                                <p class="text-muted small text-uppercase fw-semibold mb-2">Transferencias</p>
                                <h3 class="h5 mb-0">{{ $totalesPorMetodo['TRANSFERENCIA_SPEI'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
                <i class="fas fa-table me-1"></i>
                Tabla de movimientos
            </div>
            <span class="text-muted small">{{ $caja->movimientos->count() }} registros</span>
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped fs-6 align-middle">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Método de pago</th>
                        <th class="text-end">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($caja->movimientos as $item)
                        <tr>
                            <td>
                                <span class="badge rounded-pill {{ $item->tipo->value === 'VENTA' ? 'text-bg-success' : 'text-bg-warning' }}">
                                    {{ $item->tipo->value }}
                                </span>
                            </td>
                            <td>
                                <p class="fw-semibold mb-1">{{ $item->descripcion }}</p>
                                <p class="text-muted mb-0 small">Caja: {{ $caja->nombre }}</p>
                            </td>
                            <td>{{ $item->metodo_pago?->label() ?? 'No definido' }}</td>
                            <td class="text-end">{{ $item->monto }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="confirmModal-{{ $caja->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Mensaje de confirmación</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Seguro que quieres cerrar la caja?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('cajas.destroy', ['caja' => $caja->id]) }}" method="post">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger">Confirmar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush
