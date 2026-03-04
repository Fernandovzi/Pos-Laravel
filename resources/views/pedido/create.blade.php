@extends('layouts.app')

@section('title','Crear pedido')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Nuevo pedido</h1>
    <form action="{{ route('pedidos.store') }}" method="POST">
        @csrf
        <div class="row g-3">
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

        <hr>
        <h5>Productos</h5>
        <div class="row g-2 align-items-end" id="selector-producto">
            <div class="col-md-6">
                <label class="form-label">Producto (búsqueda por nombre)</label>
                <input type="text" id="buscarProducto" class="form-control mb-2" placeholder="Escriba el nombre del producto..." oninput="filtrarProductos()">
                <select id="producto" class="form-control">
                    <option value="">Seleccione</option>
                    @foreach($productos as $producto)
                    <option value="{{ $producto->id }}" data-stock="{{ $producto->cantidad }}" data-precio="{{ $producto->precio }}" data-texto="{{ $producto->codigo }} - {{ $producto->nombre }} {{ $producto->sigla }}">
                        {{ $producto->codigo }} - {{ $producto->nombre }} {{ $producto->sigla }} (Stock: {{ $producto->cantidad }})
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
                <button type="button" class="btn btn-primary" onclick="agregarProducto()">Agregar</button>
            </div>
        </div>

        <div class="table-responsive mt-3">
            <table class="table table-bordered" id="tabla-productos">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr><th colspan="3" class="text-end">Subtotal</th><th><span id="subtotal">0.00</span></th><th></th></tr>
                    <tr><th colspan="3" class="text-end">Impuesto ({{ $empresa->porcentaje_impuesto ?? 0 }}%)</th><th><span id="impuesto">0.00</span></th><th></th></tr>
                    <tr><th colspan="3" class="text-end">Total</th><th><span id="total">0.00</span></th><th></th></tr>
                </tfoot>
            </table>
        </div>

        <input type="hidden" name="subtotal" id="inputSubtotal" value="0">
        <input type="hidden" name="impuesto" id="inputImpuesto" value="0">
        <input type="hidden" name="total" id="inputTotal" value="0">

        <button class="btn btn-success">Guardar pedido</button>
    </form>
</div>

<script>
const porcentajeImpuesto = Number(@json($empresa->porcentaje_impuesto ?? 0));

document.getElementById('producto').addEventListener('change', actualizarStockProducto);

function filtrarProductos() {
    const texto = document.getElementById('buscarProducto').value.toLowerCase().trim();
    const select = document.getElementById('producto');

    [...select.options].forEach((option, index) => {
        if (index === 0) {
            option.hidden = false;
            return;
        }

        const nombre = option.textContent.toLowerCase();
        option.hidden = texto !== '' && !nombre.includes(texto);
    });
}

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
        <td>${cantidad}<input type="hidden" name="arraycantidad[]" value="${cantidad}"></td>
        <td>${precio.toFixed(2)}<input type="hidden" name="arrayprecio[]" value="${precio}"></td>
        <td class="item-subtotal">${subtotal}</td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove(); recalcularTotales();">X</button></td>`;
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
@endsection
