@extends('layouts.app')

@section('title','Realizar venta')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="container-fluid px-4 pos-sale-page">
    <div class="sale-page-header mt-4">
        <h1 class="sale-page-title">Nueva venta</h1>
        <span class="sale-page-tag">POS</span>
    </div>

    <x-breadcrumb.template>
        <x-breadcrumb.item :href="route('panel')" content="Inicio" />
        <x-breadcrumb.item :href="route('ventas.index')" content="Ventas" />
        <x-breadcrumb.item active='true' content="Nueva venta" />
    </x-breadcrumb.template>

    <form action="{{ route('ventas.store') }}" method="post">
        @csrf

        <div class="row g-3">
            <div class="col-lg-4">
                <label class="form-label">Cliente</label>
                <select name="cliente_id" class="form-control selectpicker" data-live-search="true" title="Selecciona">
                    @foreach ($clientes as $item)
                    <option value="{{$item->id}}">{{$item->nombre_documento}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4">
                <label class="form-label">Comprobante</label>
                <select name="comprobante_id" class="form-control selectpicker" title="Selecciona">
                    @foreach ($comprobantes as $item)
                    <option value="{{$item->id}}">{{$item->nombre}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4">
                <label class="form-label">Método principal</label>
                <select required name="metodo_pago" id="metodo_pago" class="form-control selectpicker" title="Selecciona">
                    @foreach ($optionsMetodoPago as $item)
                    <option value="{{$item->value}}">{{$item->label()}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <hr>

        <div class="row g-3">
            <div class="col-md-6">
                <select id="producto_id" class="form-control selectpicker" data-live-search="true" title="Busque un producto aquí">
                    @foreach ($productos as $item)
                    <option value="{{$item->id}}-{{$item->cantidad}}-{{$item->precio}}-{{$item->nombre}}-{{$item->sigla}}">
                        {{'Código: '. $item->codigo.' - '. $item->nombre.' - '.$item->sigla}}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2"><input id="stock" disabled class="form-control" placeholder="Stock"></div>
            <div class="col-md-2"><input id="precio" disabled class="form-control" placeholder="Precio"></div>
            <div class="col-md-2"><input id="cantidad" type="number" class="form-control" placeholder="Cantidad"></div>
            <div class="col-12 text-end">
                <button id="btn_agregar" class="btn btn-primary" type="button">Agregar producto</button>
            </div>

            <div class="col-12 table-responsive">
                <table class="table" id="tabla_detalle">
                    <thead><tr><th>Producto</th><th>Pres.</th><th>Cant.</th><th>Precio</th><th>Subtotal</th><th></th></tr></thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr><th colspan="4">Subtotal</th><th id="sumas">0</th><th></th></tr>
                        <tr><th colspan="4">Impuesto</th><th id="igv">0</th><th></th></tr>
                        <tr><th colspan="4">Total</th><th id="total">0</th><th></th></tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <input type="hidden" name="subtotal" id="inputSubtotal" value="0">
        <input type="hidden" name="impuesto" id="inputImpuesto" value="0">
        <input type="hidden" name="total" id="inputTotal" value="0">

        <hr>

        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Método de pago</label>
                <select id="pago_metodo" class="form-control selectpicker" title="Selecciona método">
                    @foreach ($optionsMetodoPago as $item)
                        @if ($item->value !== \App\Enums\MetodoPagoEnum::PagoMixto->value)
                        <option value="{{$item->value}}">{{$item->label()}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Monto</label>
                <input type="number" step="any" id="pago_monto" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Referencia / autorización</label>
                <input type="text" id="pago_referencia" class="form-control">
            </div>
            <div class="col-md-2">
                <button id="btn_agregar_pago" type="button" class="btn btn-success w-100">Agregar pago</button>
            </div>

            <div class="col-12 table-responsive">
                <table class="table" id="tabla_pagos">
                    <thead><tr><th>Método</th><th>Monto</th><th>Referencia</th><th></th></tr></thead>
                    <tbody></tbody>
                </table>
                <small class="text-muted">La suma de pagos debe ser igual al total. En efectivo se calcula cambio automáticamente.</small>
            </div>

            <div class="col-md-6">
                <label class="form-label">Monto recibido (efectivo)</label>
                <input type="number" step="any" id="dinero_recibido" name="monto_recibido" class="form-control" value="0">
            </div>
            <div class="col-md-6">
                <label class="form-label">Cambio</label>
                <input readonly type="number" step="any" name="vuelto_entregado" id="vuelto" class="form-control" value="0">
            </div>
        </div>

        @if ($errors->any())
        <div class="alert alert-danger mt-3">{{ $errors->first() }}</div>
        @endif

        <div class="mt-3 text-center">
            <button type="submit" id="guardar" class="btn btn-primary">Cobrar venta</button>
        </div>
    </form>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
let cont = 0, subtotal = [], sumas = 0, igv = 0, total = 0, arrayIdProductos = [];
let pagos = [];
const impuesto = @json($empresa->porcentaje_impuesto);
const labels = {
    EFECTIVO: 'Efectivo',
    TARJETA_DEBITO: 'Tarjeta de Débito',
    TARJETA_CREDITO: 'Tarjeta de Crédito',
    TRANSFERENCIA_SPEI: 'Transferencia / SPEI',
};

$(function() {
    $('#producto_id').change(mostrarValores);
    $('#btn_agregar').click(agregarProducto);
    $('#btn_agregar_pago').click(agregarPago);
    $('#dinero_recibido').on('input', calcularCambio);
    $('form').on('submit', validarPagosAntesDeEnviar);
});

function mostrarValores() {
    let dataProducto = ($('#producto_id').val() || '').split('-');
    $('#stock').val(dataProducto[1] || '');
    $('#precio').val(dataProducto[2] || '');
}

function agregarProducto() {
    let dataProducto = ($('#producto_id').val() || '').split('-');
    let [idProducto, stock, precioVenta, nameProducto, presentacioneProducto] = dataProducto;
    let cantidad = $('#cantidad').val();

    if (!idProducto || !cantidad) return showModal('Le faltan campos por llenar');
    if (!(parseInt(cantidad) > 0 && (cantidad % 1 == 0))) return showModal('Valores incorrectos');
    if (parseInt(cantidad) > parseInt(stock)) return showModal('Cantidad incorrecta');
    if (arrayIdProductos.includes(idProducto)) return showModal('Ya ha ingresado el producto');

    subtotal[cont] = round(cantidad * precioVenta);
    sumas = round(sumas + subtotal[cont]);
    igv = round(sumas / 100 * impuesto);
    total = round(sumas + igv);

    let fila = `<tr id="fila${cont}">
        <td><input type="hidden" name="arrayidproducto[]" value="${idProducto}">${nameProducto}</td>
        <td>${presentacioneProducto}</td>
        <td><input type="hidden" name="arraycantidad[]" value="${cantidad}">${cantidad}</td>
        <td><input type="hidden" name="arrayprecioventa[]" value="${precioVenta}">${precioVenta}</td>
        <td>${subtotal[cont]}</td>
        <td><button class="btn btn-danger" type="button" onClick="eliminarProducto(${cont}, ${idProducto})">X</button></td>
    </tr>`;

    $('#tabla_detalle tbody').append(fila);
    arrayIdProductos.push(idProducto);
    cont++;
    limpiarCampos();
    renderTotales();
}

function eliminarProducto(indice, idProducto) {
    sumas = round(sumas - round(subtotal[indice] || 0));
    igv = round(sumas / 100 * impuesto);
    total = round(sumas + igv);
    $('#fila' + indice).remove();
    arrayIdProductos = arrayIdProductos.filter(item => item !== String(idProducto));
    renderTotales();
}

function renderTotales() {
    $('#sumas').html(sumas);
    $('#igv').html(igv);
    $('#total').html(total);
    $('#inputImpuesto').val(igv);
    $('#inputTotal').val(total);
    $('#inputSubtotal').val(sumas);
    calcularCambio();
}

function agregarPago() {
    const metodo = $('#pago_metodo').val();
    const monto = parseFloat($('#pago_monto').val());
    const referencia = $('#pago_referencia').val();

    if (!metodo || isNaN(monto) || monto <= 0) return showModal('Capture método y monto válidos');

    pagos.push({ metodo_pago: metodo, monto: round(monto), referencia });
    renderPagos();

    $('#pago_monto').val('');
    $('#pago_referencia').val('');
    $('#pago_metodo').selectpicker('val', '');
}

function renderPagos() {
    const tbody = $('#tabla_pagos tbody');
    tbody.empty();

    pagos.forEach((pago, idx) => {
        tbody.append(`<tr>
            <td>${labels[pago.metodo_pago] || pago.metodo_pago}<input type="hidden" name="pagos[${idx}][metodo_pago]" value="${pago.metodo_pago}"></td>
            <td>${pago.monto}<input type="hidden" name="pagos[${idx}][monto]" value="${pago.monto}"></td>
            <td>${pago.referencia || ''}<input type="hidden" name="pagos[${idx}][referencia]" value="${pago.referencia || ''}"></td>
            <td><button type="button" class="btn btn-danger" onclick="eliminarPago(${idx})">X</button></td>
        </tr>`);
    });

    sincronizarMetodoPrincipal();
    calcularCambio();
}

function eliminarPago(index) {
    pagos.splice(index, 1);
    renderPagos();
}

function sincronizarMetodoPrincipal() {
    const metodos = [...new Set(pagos.map(p => p.metodo_pago))];
    if (metodos.length > 1) {
        $('#metodo_pago').selectpicker('val', 'PAGO_MIXTO');
    } else if (metodos.length === 1) {
        $('#metodo_pago').selectpicker('val', metodos[0]);
    }
}

function calcularCambio() {
    const recibido = parseFloat($('#dinero_recibido').val()) || 0;
    const totalEfectivo = pagos.filter(p => p.metodo_pago === 'EFECTIVO').reduce((acc, p) => acc + Number(p.monto), 0);
    const base = recibido > 0 ? recibido : totalEfectivo;
    const cambio = base > 0 ? round(base - totalEfectivo) : 0;
    $('#vuelto').val(cambio >= 0 ? cambio : 0);
}

function validarPagosAntesDeEnviar(e) {
    const sumaPagos = pagos.reduce((acc, p) => acc + Number(p.monto), 0);
    if (total <= 0) {
        e.preventDefault();
        return showModal('Agregue al menos un producto');
    }
    if (pagos.length === 0) {
        e.preventDefault();
        return showModal('Debe agregar al menos un pago');
    }
    if (Math.abs(round(sumaPagos) - round(total)) > 0.01) {
        e.preventDefault();
        return showModal('La suma de pagos debe ser igual al total');
    }
}

function limpiarCampos() {
    $('#producto_id').selectpicker('val', '');
    $('#cantidad').val('');
    $('#precio').val('');
    $('#stock').val('');
}

function showModal(message, icon = 'error') {
    Swal.fire({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, icon, title: message });
}

function round(num, decimales = 2) {
    var signo = (num >= 0 ? 1 : -1);
    num = num * signo;
    if (decimales === 0) return signo * Math.round(num);
    num = num.toString().split('e');
    num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales) : decimales)));
    num = num.toString().split('e');
    return signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales));
}
</script>
@endpush
