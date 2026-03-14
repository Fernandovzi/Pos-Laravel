@extends('layouts.app')

@section('title','Realizar venta')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="container-fluid px-4 page-shell">
    <x-ui.page-header title="Nueva venta" />

    <x-ui.breadcrumbs :items="[
        ['href' => route('panel'), 'label' => 'Inicio'],
        ['href' => route('ventas.index'), 'label' => 'Ventas'],
        ['label' => 'Nueva venta', 'active' => true]
    ]" />

    <form action="{{ route('ventas.store') }}" method="post" target="_blank">
        @csrf

        <x-ui.card title="1) Datos generales" class="sale-card">
            <div class="row g-3">
                <div class="col-lg-6">
                    <label class="form-label">Cliente</label>
                    <select name="cliente_id" id="cliente_id" class="form-control selectpicker" data-live-search="true" title="Selecciona">
                        @foreach ($clientes as $item)
                        <option
                            value="{{$item->id}}"
                            data-rfc="{{$item->persona->rfc ?? ''}}"
                            data-regimen="{{$item->persona->regimen_fiscal ?? ''}}"
                            data-uso-cfdi="{{$item->persona->uso_cfdi ?? ''}}"
                            data-cp-fiscal="{{$item->persona->codigo_postal_fiscal ?? ''}}"
                        >
                            {{$item->nombre_documento}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6">
                    <label class="form-label">Comprobante</label>
                    <select name="comprobante_id" id="comprobante_id" class="form-control selectpicker" title="Selecciona">
                        @foreach ($comprobantes as $item)
                        <option value="{{$item->id}}" data-nombre="{{$item->nombre}}">{{$item->nombre}}</option>
                        @endforeach
                    </select>
                    <small class="sale-help">Si seleccionas Factura, se validará RFC, régimen fiscal, uso CFDI y código postal fiscal del cliente.</small>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card title="2) Productos" class="sale-card">
            <div class="row g-3 align-items-end">
                <div class="col-lg-6">
                    <label class="form-label">Producto</label>
                    <select id="producto_id" class="form-control selectpicker" data-live-search="true" title="Busque un producto aquí">
                        @foreach ($productos as $item)
                        <option value="{{$item->id}}-{{$item->cantidad}}-{{$item->precio}}-{{$item->nombre}}-{{$item->sigla}}">
                            {{'Código: '. $item->codigo.' - '. $item->nombre.' - '.$item->sigla}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="form-label">Stock</label>
                    <input id="stock" disabled class="form-control" placeholder="Stock">
                </div>
                <div class="col-lg-2">
                    <label class="form-label">Precio</label>
                    <input id="precio" disabled class="form-control" placeholder="Precio">
                </div>
                <div class="col-lg-2">
                    <label class="form-label">Cantidad</label>
                    <input id="cantidad" type="number" class="form-control" placeholder="Cantidad">
                </div>
                <div class="col-12 text-end">
                    <button id="btn_agregar" class="btn btn-primary" type="button">Agregar producto</button>
                </div>
            </div>

            <x-ui.table title="Detalle de productos" id="tabla_detalle">
                <thead>
                    <tr><th>Producto</th><th>Presentación</th><th class="text-end">Cantidad</th><th class="text-end">Precio</th><th class="text-end">Subtotal</th><th width="70"></th></tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr><th colspan="4" class="text-end">Subtotal</th><th class="text-end" id="sumas">0</th><th></th></tr>
                    <tr><th colspan="4" class="text-end">Impuesto</th><th class="text-end" id="igv">0</th><th></th></tr>
                    <tr><th colspan="4" class="text-end">Total</th><th class="text-end" id="total">0</th><th></th></tr>
                </tfoot>
            </x-ui.table>
        </x-ui.card>

        <input type="hidden" name="subtotal" id="inputSubtotal" value="0">
        <input type="hidden" name="impuesto" id="inputImpuesto" value="0">
        <input type="hidden" name="total" id="inputTotal" value="0">

        <input type="hidden" name="metodo_pago" id="metodo_pago" value="">
        <input type="hidden" name="monto_recibido" id="monto_recibido" value="0">
        <input type="hidden" name="vuelto_entregado" id="vuelto" value="0">

        <x-ui.card title="3) Pagos" class="sale-card">
            <div class="row g-3 align-items-end">
                <div class="col-lg-4">
                    <label class="form-label">Método de pago</label>
                    <select id="pago_metodo" class="form-control selectpicker" title="Selecciona método">
                        @foreach ($optionsMetodoPago as $item)
                            @if ($item->value !== \App\Enums\MetodoPagoEnum::PagoMixto->value)
                            <option value="{{$item->value}}">{{$item->label()}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-5">
                    <label class="form-label">Referencia / autorización (tarjeta o SPEI)</label>
                    <input type="text" id="pago_referencia" class="form-control" placeholder="Ej: auth 12345 / folio SPEI">
                </div>
                <div class="col-lg-3">
                    <label class="form-label">Monto</label>
                    <input type="number" step="any" id="pago_monto" class="form-control">
                </div>
                <div class="col-12 text-end">
                    <button id="btn_agregar_pago" type="button" class="btn btn-success">Agregar pago</button>
                </div>
            </div>

            <x-ui.table title="Pagos agregados" id="tabla_pagos">
                <thead><tr><th>Método</th><th class="text-end" width="160">Monto</th><th>Referencia</th><th width="70"></th></tr></thead>
                <tbody></tbody>
            </x-ui.table>

            <div class="row g-3 mt-1">
                <div class="col-md-4"><div class="sale-total-box">Total venta<br><span class="value" id="box_total">0.00</span></div></div>
                <div class="col-md-4"><div class="sale-total-box">Pagado<br><span class="value" id="box_pagado">0.00</span></div></div>
                <div class="col-md-4"><div class="sale-total-box">Pendiente<br><span class="value" id="box_pendiente">0.00</span></div></div>
            </div>

            <div class="mt-3">
                <small class="sale-help">El monto se captura una sola vez por cada registro de pago. La suma de pagos debe coincidir con el total.</small>
            </div>
        </x-ui.card>

        @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <div class="d-flex justify-content-end gap-2 mb-4">
            <a href="{{ route('ventas.index') }}"><x-ui.button variant="secondary" icon="fa-solid fa-xmark">Cancelar</x-ui.button></a>
            <x-ui.button variant="primary" icon="fa-solid fa-cash-register" type="submit" id="guardar">Cobrar venta</x-ui.button>
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
    $('#comprobante_id, #cliente_id').on('changed.bs.select', validarRequisitosFactura);
    $('form').on('submit', validarPagosAntesDeEnviar);
    renderResumenPagos();
});

function mostrarValores() {
    const dataProducto = ($('#producto_id').val() || '').split('-');
    $('#stock').val(dataProducto[1] || '');
    $('#precio').val(dataProducto[2] || '');
}

function agregarProducto() {
    const dataProducto = ($('#producto_id').val() || '').split('-');
    const [idProducto, stock, precioVenta, nameProducto, presentacioneProducto] = dataProducto;
    const cantidad = $('#cantidad').val();

    if (!idProducto || !cantidad) return showModal('Completa producto y cantidad.');
    if (!(parseInt(cantidad) > 0 && (cantidad % 1 == 0))) return showModal('La cantidad debe ser un entero mayor a 0.');
    if (parseInt(cantidad) > parseInt(stock)) return showModal('La cantidad supera el stock disponible.');
    if (arrayIdProductos.includes(idProducto)) return showModal('El producto ya fue agregado.');

    subtotal[cont] = round(cantidad * precioVenta);
    sumas = round(sumas + subtotal[cont]);
    igv = round(sumas / 100 * impuesto);
    total = round(sumas + igv);

    const fila = `<tr id="fila${cont}">
        <td><input type="hidden" name="arrayidproducto[]" value="${idProducto}">${nameProducto}</td>
        <td>${presentacioneProducto}</td>
        <td class="text-end"><input type="hidden" name="arraycantidad[]" value="${cantidad}">${cantidad}</td>
        <td class="text-end"><input type="hidden" name="arrayprecioventa[]" value="${precioVenta}">${precioVenta}</td>
        <td class="text-end">${subtotal[cont]}</td>
        <td><button class="btn btn-danger btn-sm" type="button" onClick="eliminarProducto(${cont}, ${idProducto})">X</button></td>
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
    $('#sumas').html(sumas.toFixed(2));
    $('#igv').html(igv.toFixed(2));
    $('#total').html(total.toFixed(2));
    $('#inputImpuesto').val(igv);
    $('#inputTotal').val(total);
    $('#inputSubtotal').val(sumas);
    renderResumenPagos();
    sincronizarCamposOcultos();
}

function agregarPago() {
    const metodo = $('#pago_metodo').val();
    const monto = parseFloat($('#pago_monto').val());
    const referencia = $('#pago_referencia').val();

    if (!metodo || isNaN(monto) || monto <= 0) return showModal('Capture método y monto válidos.');

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
            <td class="text-end">${Number(pago.monto).toFixed(2)}<input type="hidden" name="pagos[${idx}][monto]" value="${pago.monto}"></td>
            <td>${pago.referencia || ''}<input type="hidden" name="pagos[${idx}][referencia]" value="${pago.referencia || ''}"></td>
            <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarPago(${idx})">X</button></td>
        </tr>`);
    });

    sincronizarMetodoPrincipal();
    renderResumenPagos();
    sincronizarCamposOcultos();
}

function eliminarPago(index) {
    pagos.splice(index, 1);
    renderPagos();
}

function sincronizarMetodoPrincipal() {
    const metodos = [...new Set(pagos.map(p => p.metodo_pago))];

    if (metodos.length > 1) {
        $('#metodo_pago').val('PAGO_MIXTO');
        return;
    }

    if (metodos.length === 1) {
        $('#metodo_pago').val(metodos[0]);
        return;
    }

    $('#metodo_pago').val('');
}

function sincronizarCamposOcultos() {
    const sumaPagos = pagos.reduce((acc, p) => acc + Number(p.monto), 0);
    const efectivo = pagos
        .filter(p => p.metodo_pago === 'EFECTIVO')
        .reduce((acc, p) => acc + Number(p.monto), 0);

    $('#monto_recibido').val(round(sumaPagos));
    $('#vuelto').val(round(Math.max(0, efectivo - Math.min(efectivo, total))));
}

function renderResumenPagos() {
    const pagado = pagos.reduce((acc, p) => acc + Number(p.monto), 0);
    const pendiente = round(total - pagado);

    $('#box_total').text(Number(total).toFixed(2));
    $('#box_pagado').text(Number(pagado).toFixed(2));
    $('#box_pendiente').text((pendiente > 0 ? pendiente : 0).toFixed(2));
}

function validarRequisitosFactura() {
    const comprobanteSel = $('#comprobante_id option:selected');
    const clienteSel = $('#cliente_id option:selected');
    const nombreComprobante = (comprobanteSel.data('nombre') || '').toString().toUpperCase();

    if (!nombreComprobante.includes('FACTURA')) {
        return true;
    }

    const rfc = (clienteSel.data('rfc') || '').toString().trim();
    const regimen = (clienteSel.data('regimen') || '').toString().trim();
    const usoCfdi = (clienteSel.data('uso-cfdi') || '').toString().trim();
    const cpFiscal = (clienteSel.data('cp-fiscal') || '').toString().trim();

    const faltantes = [];
    if (!rfc) faltantes.push('RFC');
    if (!regimen) faltantes.push('Régimen fiscal');
    if (!usoCfdi) faltantes.push('Uso CFDI');
    if (!cpFiscal) faltantes.push('Código postal fiscal');

    if (faltantes.length > 0) {
        showModal(`Para FACTURA faltan datos del cliente: ${faltantes.join(', ')}`);
        return false;
    }

    return true;
}

function validarPagosAntesDeEnviar(e) {
    const sumaPagos = pagos.reduce((acc, p) => acc + Number(p.monto), 0);

    if (!validarRequisitosFactura()) {
        e.preventDefault();
        return;
    }

    if (total <= 0) {
        e.preventDefault();
        return showModal('Agregue al menos un producto.');
    }

    if (pagos.length === 0) {
        e.preventDefault();
        return showModal('Debe agregar al menos un pago.');
    }

    if (Math.abs(round(sumaPagos) - round(total)) > 0.01) {
        e.preventDefault();
        return showModal('La suma de pagos debe ser igual al total.');
    }

    sincronizarCamposOcultos();
}

function limpiarCampos() {
    $('#producto_id').selectpicker('val', '');
    $('#cantidad').val('');
    $('#precio').val('');
    $('#stock').val('');
}

function showModal(message, icon = 'error') {
    Swal.fire({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, icon, title: message });
}

function round(num, decimales = 2) {
    let signo = (num >= 0 ? 1 : -1);
    num = num * signo;
    if (decimales === 0) return signo * Math.round(num);
    num = num.toString().split('e');
    num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales) : decimales)));
    num = num.toString().split('e');
    return signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales));
}
</script>
@endpush
