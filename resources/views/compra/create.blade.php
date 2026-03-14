@extends('layouts.app')

@section('title','Registrar producción interna')

@push('css')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
@endpush

@section('content')
<div class="container-fluid px-4 page-shell">
    <x-ui.page-header title="Registrar Producción Interna" />

    <x-ui.breadcrumbs :items="[
        ['href' => route('panel'), 'label' => 'Inicio'],
        ['href' => route('compras.index'), 'label' => 'Producción Interna'],
        ['label' => 'Registrar', 'active' => true]
    ]" />

    <form action="{{ route('compras.store') }}" method="post">
        @csrf
        <x-ui.card title="Datos de entrada">
            <div class="row g-4 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Fecha y hora</label>
                    <input type="datetime-local" class="form-control" name="fecha_hora" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Producto</label>
                    <select id="producto_id" class="form-select">
                        <option value="">Seleccione</option>
                        @foreach($productos as $producto)
                        <option value="{{ $producto->id }}" data-costo="{{ $producto->costo ?? $producto->precio ?? 0 }}">
                            {{ $producto->codigo }} - {{ $producto->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cantidad</label>
                    <input type="number" min="1" id="cantidad" class="form-control">
                </div>
                <div class="col-md-1 d-grid">
                    <button type="button" id="agregar" class="btn btn-primary">+</button>
                </div>
            </div>
        </x-ui.card>

        <x-ui.table title="Detalle de producción" id="detalle">
            <thead>
                <tr>
                    <th>Producto</th><th class="text-end">Cantidad</th><th class="text-end">Costo usado</th><th class="text-end">Subtotal</th><th></th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
                <tr><th colspan="3" class="text-end">Total</th><th class="text-end" id="total">0</th><th></th></tr>
            </tfoot>
        </x-ui.table>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('compras.index') }}"><x-ui.button variant="secondary" icon="fa-solid fa-xmark">Cancelar</x-ui.button></a>
            <x-ui.button variant="success" icon="fa-solid fa-floppy-disk" type="submit">Guardar entrada</x-ui.button>
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
    const precio = Number(producto.data('costo') ?? 0);

    if (!productoId || cantidad <= 0) {
        alert('Completa producto y cantidad.');
        return;
    }

    if (precio <= 0) {
        alert('El producto no tiene costo/precio configurado.');
        return;
    }

    const subtotal = (cantidad * precio).toFixed(2);
    total += Number(subtotal);

    const row = `<tr>
        <td>
            <input type="hidden" name="arrayidproducto[]" value="${productoId}">
            ${productoTexto}
        </td>
        <td class="text-end"><input type="hidden" name="arraycantidad[]" value="${cantidad}">${cantidad}</td>
        <td class="text-end">${precio.toFixed(2)}</td>
        <td class="text-end">${subtotal}</td>
        <td>
            <input type="hidden" name="arrayfechavencimiento[]" value="">
            <button type="button" class="btn btn-danger btn-sm eliminar" data-subtotal="${subtotal}">Eliminar</button>
        </td>
    </tr>`;

    $('#detalle tbody').append(row);
    $('#total').text(total.toFixed(2));

    $('#producto_id').val('');
    $('#cantidad').val('');
});

$(document).on('click', '.eliminar', function () {
    total -= Number($(this).data('subtotal'));
    $('#total').text(total.toFixed(2));
    $(this).closest('tr').remove();
});
</script>
@endpush
