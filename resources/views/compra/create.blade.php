@extends('layouts.app')

@section('title','Registrar producción interna')

@push('css')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Registrar Entrada por Producción Interna</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('compras.index') }}">Producción Interna</a></li>
        <li class="breadcrumb-item active">Registrar</li>
    </ol>

    <form action="{{ route('compras.store') }}" method="post">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Fecha y hora</label>
                        <input type="datetime-local" class="form-control" name="fecha_hora" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Producto</label>
                        <select id="producto_id" class="form-select">
                            <option value="">Seleccione</option>
                            @foreach($productos as $producto)
                            <option value="{{ $producto->id }}">{{ $producto->codigo }} - {{ $producto->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Cantidad</label>
                        <input type="number" min="1" id="cantidad" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Costo unitario</label>
                        <input type="number" min="0.01" step="0.01" id="precio_compra" class="form-control">
                    </div>
                    <div class="col-md-1 d-grid">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" id="agregar" class="btn btn-primary">+</button>
                    </div>
                </div>

                <div class="table-responsive mt-4">
                    <table class="table" id="detalle">
                        <thead>
                            <tr>
                                <th>Producto</th><th>Cantidad</th><th>Costo unitario</th><th>Subtotal</th><th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr><th colspan="3" class="text-end">Total</th><th id="total">0</th><th></th></tr>
                        </tfoot>
                    </table>
                </div>
                <button class="btn btn-success" type="submit">Guardar entrada</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('js')
<script>
let total = 0;

$('#agregar').on('click', function () {
    const producto = $('#producto_id option:selected');
    const productoId = producto.val();
    const productoTexto = producto.text();
    const cantidad = Number($('#cantidad').val());
    const precio = Number($('#precio_compra').val());

    if (!productoId || cantidad <= 0 || precio <= 0) {
        alert('Completa producto, cantidad y costo unitario.');
        return;
    }

    const subtotal = (cantidad * precio).toFixed(2);
    total += Number(subtotal);

    const row = `<tr>
        <td>
            <input type="hidden" name="arrayidproducto[]" value="${productoId}">
            ${productoTexto}
        </td>
        <td><input type="hidden" name="arraycantidad[]" value="${cantidad}">${cantidad}</td>
        <td><input type="hidden" name="arraypreciocompra[]" value="${precio.toFixed(2)}">${precio.toFixed(2)}</td>
        <td>${subtotal}</td>
        <td>
            <input type="hidden" name="arrayfechavencimiento[]" value="">
            <button type="button" class="btn btn-danger btn-sm eliminar" data-subtotal="${subtotal}">x</button>
        </td>
    </tr>`;

    $('#detalle tbody').append(row);
    $('#total').text(total.toFixed(2));

    $('#producto_id').val('');
    $('#cantidad').val('');
    $('#precio_compra').val('');
});

$(document).on('click', '.eliminar', function () {
    total -= Number($(this).data('subtotal'));
    $('#total').text(total.toFixed(2));
    $(this).closest('tr').remove();
});
</script>
@endpush
