@extends('layouts.app')

@section('title','Crear pedido')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
@endpush

@section('content')
<div class="container-fluid px-4 page-shell">
    <x-ui.page-header title="Nuevo pedido" />

    <x-ui.breadcrumbs :items="[
        ['href' => route('panel'), 'label' => 'Inicio'],
        ['href' => route('pedidos.index'), 'label' => 'Pedidos'],
        ['label' => 'Nuevo pedido', 'active' => true]
    ]" />

    <form action="{{ route('pedidos.store') }}" method="POST">
        @csrf

        <x-ui.card title="Datos generales del pedido">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Proveedor</label>
                    <select name="proveedore_id" class="form-control" required>
                        <option value="">Seleccione</option>
                        @foreach($proveedores as $proveedor)
                        <option value="{{ $proveedor->id }}">{{ $proveedor->nombre_documento }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Persona que recoge</label>
                    <input type="text" name="persona_recojo" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Fecha estimada de entrega</label>
                    <input type="datetime-local" name="fecha_entrega_estimada" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Observaciones</label>
                    <input type="text" name="observaciones" class="form-control">
                </div>
            </div>
        </x-ui.card>

        <x-ui.card title="Productos">
            <div class="row g-4 align-items-end" id="selector-producto">
                <div class="col-md-6">
                    <label class="form-label">Producto</label>
                    <select id="producto" class="form-control selectpicker" data-live-search="true" title="Busque un producto aquí">
                        @foreach($productos as $producto)
                        <option value="{{ $producto->id }}" data-stock="{{ $producto->cantidad }}" data-precio="{{ $producto->precio }}" data-texto="{{ $producto->codigo }} - {{ $producto->nombre }} {{ $producto->sigla }}">
                            {{ $producto->codigo }} - {{ $producto->nombre }} {{ $producto->sigla }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Cantidad</label>
                    <input type="number" min="1" id="cantidad" class="form-control" value="1">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Stock</label>
                    <input type="text" id="stockProducto" class="form-control" readonly>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary w-100" onclick="agregarProducto()">Agregar</button>
                </div>
            </div>
        </x-ui.card>

        <x-ui.table title="Detalle del pedido" id="tabla-productos">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th class="text-end">Cantidad</th>
                    <th class="text-end">Precio</th>
                    <th class="text-end">Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
                <tr><th colspan="3" class="text-end">Subtotal</th><th class="text-end"><span id="subtotal">0.00</span></th><th></th></tr>
                <tr><th colspan="3" class="text-end">Impuesto ({{ $empresa->porcentaje_impuesto ?? 0 }}%)</th><th class="text-end"><span id="impuesto">0.00</span></th><th></th></tr>
                <tr><th colspan="3" class="text-end">Total</th><th class="text-end"><span id="total">0.00</span></th><th></th></tr>
            </tfoot>
        </x-ui.table>

        <input type="hidden" name="subtotal" id="inputSubtotal" value="0">
        <input type="hidden" name="impuesto" id="inputImpuesto" value="0">
        <input type="hidden" name="total" id="inputTotal" value="0">

        <div class="page-toolbar mt-4">
            <x-ui.button variant="primary" type="submit" class="text-white">Guardar entrada</x-ui.button>
            <a href="{{ route('pedidos.index') }}">
            <x-ui.button variant="danger" class="text-white">Cancelar</x-ui.button>
            </a>
        </div>
    </form>
</div>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
const porcentajeImpuesto = Number(@json($empresa->porcentaje_impuesto ?? 0));

document.getElementById('producto').addEventListener('change', actualizarStockProducto);

function actualizarStockProducto() {
    const select = document.getElementById('producto');
    const option = select.options[select.selectedIndex];
    document.getElementById('stockProducto').value = option?.dataset?.stock ?? '';
}

function agregarProducto() {
    const select = document.getElementById('producto');
    const cantidad = Number(document.getElementById('cantidad').value);
    const option = select.options[select.selectedIndex];
    if (!option.value || cantidad <= 0) return;

    const stock = Number(option.dataset.stock);
    const precio = Number(option.dataset.precio);
    if (cantidad > stock) {
        alert('Cantidad mayor al stock disponible');
        return;
    }

    const tbody = document.querySelector('#tabla-productos tbody');
    const subtotal = (cantidad * precio).toFixed(2);

    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>${option.dataset.texto}<input type="hidden" name="arrayidproducto[]" value="${option.value}"></td>
        <td class="text-end">${cantidad}<input type="hidden" name="arraycantidad[]" value="${cantidad}"></td>
        <td class="text-end">${precio.toFixed(2)}<input type="hidden" name="arrayprecio[]" value="${precio}"></td>
        <td class="item-subtotal text-end">${subtotal}</td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove(); recalcularTotales();">Eliminar</button></td>`;
    tbody.appendChild(tr);

    recalcularTotales();
}

function recalcularTotales() {
    const subtotales = [...document.querySelectorAll('.item-subtotal')].map(td => Number(td.textContent));
    const subtotal = subtotales.reduce((acc, val) => acc + val, 0);
    const impuesto = subtotal * (porcentajeImpuesto / 100);
    const total = subtotal + impuesto;

    document.getElementById('subtotal').textContent = subtotal.toFixed(2);
    document.getElementById('impuesto').textContent = impuesto.toFixed(2);
    document.getElementById('total').textContent = total.toFixed(2);

    document.getElementById('inputSubtotal').value = subtotal.toFixed(2);
    document.getElementById('inputImpuesto').value = impuesto.toFixed(2);
    document.getElementById('inputTotal').value = total.toFixed(2);
}
</script>
@endpush
@endsection
