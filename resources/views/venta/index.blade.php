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

    @can('crear-presentacione')
    <div class="page-toolbar mb-4">
        <a href="{{route('presentaciones.create')}}">
            <button type="button" class="btn btn-primary btn-ui">Añadir nuevo registro</button>
        </a>

        <a href="{{ route('export.excel-ventas-all') }}">
            <x-ui.button variant="success" icon="fa-solid fa-file-excel" class="text-white">Exportar Excel</x-ui.button>
        </a>
    </div>
    @endcan

    <x-ui.table title="Tabla de ventas" id="datatablesSimple">
        <thead>
            <tr>
                <th>Comprobante</th>
                <th>Cliente</th>
                <th>Fecha y hora</th>
                <th>Vendedor</th>
                <th class="text-end">Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ventas as $item)
            <tr>
                <td>
                    <p class="fw-semibold mb-1">{{$item->comprobante->tipo_comprobante}}</p>
                    <p class="text-muted mb-0">{{$item->numero_comprobante}}</p>
                </td>
                <td>
                    <p class="fw-semibold mb-1">{{ ucfirst($item->cliente->persona->tipo_persona) }}</p>
                    <p class="text-muted mb-0">{{$item->cliente->persona->razon_social}}</p>
                </td>
                <td>
                    <p class="fw-semibold mb-1"><i class="fa-solid fa-calendar-days me-1"></i>{{$item->fecha}}</p>
                    <p class="fw-semibold mb-0"><i class="fa-solid fa-clock me-1"></i>{{$item->hora}}</p>
                </td>
                <td>{{$item->user->name}}</td>
                <td class="text-end">{{$item->total}}</td>
                <td>
                    <div class="d-flex flex-wrap gap-2">
                        @can('mostrar-venta')
                        <form action="{{route('ventas.show', ['venta'=>$item]) }}" method="get">
                            <x-ui.button variant="success" icon="fa-solid fa-eye" type="submit">Ver</x-ui.button>
                        </form>
                        @endcan

                        <a href="{{ route('export.pdf-comprobante-venta',['id' => Crypt::encrypt($item->id)]) }}" target="_blank">
                            <x-ui.button variant="secondary" icon="fa-solid fa-file-pdf">PDF</x-ui.button>
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </x-ui.table>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script>
    window.addEventListener('DOMContentLoaded', () => {
        new simpleDatatables.DataTable("#datatablesSimple", {});
    });
</script>
@endpush