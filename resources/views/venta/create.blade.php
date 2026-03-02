@extends('layouts.app')

@section('title','Realizar venta')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .pos-sale-page {
        --sale-primary: #ff6c37;
        --sale-primary-soft: rgba(255, 108, 55, 0.12);
        --sale-bg: #f5f7fb;
        --sale-surface: #ffffff;
        --sale-border: #e5e7eb;
        --sale-text: #111827;
        --sale-muted: #6b7280;
        --sale-success: #15803d;
        --sale-danger: #dc2626;
        --sale-shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
        background: var(--sale-bg);
        min-height: 100%;
        color: var(--sale-text);
    }

    .sale-page-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
        margin-bottom: 1rem;
    }

    .sale-page-title {
        margin: 0;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .sale-page-tag {
        background: var(--sale-primary-soft);
        color: #c2410c;
        font-size: .82rem;
        padding: .35rem .7rem;
        border-radius: 999px;
        font-weight: 700;
    }

    .sale-shell {
        background: var(--sale-surface);
        border: 1px solid var(--sale-border);
        border-radius: 16px;
        box-shadow: var(--sale-shadow);
    }

    .sale-section {
        border-bottom: 1px solid var(--sale-border);
        padding: 1rem 1.1rem;
    }

    .sale-section:last-child { border-bottom: 0; }

    .sale-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: .5rem;
        margin-bottom: .9rem;
    }

    .sale-section-title {
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
    }

    .sale-state {
        font-size: .78rem;
        font-weight: 700;
        border-radius: 999px;
        padding: .3rem .6rem;
    }

    .sale-state--active { background: #dcfce7; color: #166534; }
    .sale-state--pending { background: #fef3c7; color: #92400e; }
    .sale-state--paid { background: #dbeafe; color: #1d4ed8; }

    .pos-sale-page .form-label {
        margin-bottom: .35rem;
        color: #4b5563;
        font-weight: 600;
        font-size: .9rem;
    }

    .pos-sale-page .form-control,
    .pos-sale-page .bootstrap-select .dropdown-toggle,
    .pos-sale-page .input-group-text {
        min-height: 48px;
        border-radius: 12px;
        border: 1px solid var(--sale-border);
    }

    .pos-sale-page .form-control:focus,
    .pos-sale-page .bootstrap-select .dropdown-toggle:focus {
        border-color: rgba(255, 108, 55, 0.5);
        box-shadow: 0 0 0 .2rem rgba(255, 108, 55, 0.12);
    }

    .sale-btn {
        min-height: 50px;
        border-radius: 12px;
        font-weight: 700;
        padding-inline: 1.1rem;
    }

    .sale-btn-primary { background: var(--sale-primary); border-color: var(--sale-primary); }
    .sale-btn-primary:hover { background: #e55623; border-color: #e55623; }

    .sale-grid-info {
        background: #f9fafb;
        border: 1px solid var(--sale-border);
        border-radius: 12px;
        padding: .85rem;
    }

    .sale-table-wrap {
        border: 1px solid var(--sale-border);
        border-radius: 14px;
        overflow: hidden;
    }

    .sale-table thead th {
        background: #111827;
        color: #fff;
        border: 0;
        font-size: .8rem;
        text-transform: uppercase;
        letter-spacing: .02em;
        padding: .8rem .65rem;
    }

    .sale-table tbody td,
    .sale-table tbody th,
    .sale-table tfoot th {
        vertical-align: middle;
        padding: .8rem .65rem;
    }

    .sale-table tfoot tr:last-child th {
        border-top: 2px solid #d1d5db;
        font-size: 1rem;
    }

    .sale-total-amount {
        color: #111827;
        font-size: 1.25rem;
        font-weight: 800;
    }

    @media (max-width: 768px) {
        .sale-section { padding: .9rem; }
        .sale-btn { width: 100%; }
    }
</style>

@endpush

@section('content')
<div class="pos-sale-page">
<div class="container-fluid px-4">
    <div class="sale-page-header mt-3">
        <h1 class="sale-page-title">Crear venta</h1>
        <span class="sale-page-tag">Flujo rápido POS</span>
    </div>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ventas.index')}}">Ventas</a></li>
        <li class="breadcrumb-item active">Realizar Venta</li>
    </ol>
</div>

<form action="{{ route('ventas.store') }}" method="post" class="pos-form">
    @csrf
    <div class="container-lg mt-3">
        <div class="row gy-4">

            <!-----Venta---->
            <div class="col-12">
                <div class="sale-shell">
                    <section class="sale-section">
                        <div class="sale-section-header">
                            <h2 class="sale-section-title">Datos generales</h2>
                            <span class="sale-state sale-state--active">Activo</span>
                        </div>
                    <div class="row g-3">

                        <!--Cliente-->
                        <div class="col-12">
                            <label for="cliente_id" class="form-label">
                                Cliente:</label>
                            <select name="cliente_id" id="cliente_id"
                                class="form-control selectpicker show-tick"
                                data-live-search="true" title="Selecciona"
                                data-size='2'>
                                @foreach ($clientes as $item)
                                <option value="{{$item->id}}">{{$item->nombre_documento}}</option>
                                @endforeach
                            </select>
                            @error('cliente_id')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <!--Tipo de comprobante-->
                        <div class="col-md-6">
                            <label for="comprobante_id" class="form-label">
                                Comprobante:</label>
                            <select name="comprobante_id" id="comprobante_id"
                                class="form-control selectpicker"
                                title="Selecciona">
                                @foreach ($comprobantes as $item)
                                <option value="{{$item->id}}">{{$item->nombre}}</option>
                                @endforeach
                            </select>
                            @error('comprobante_id')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <!--Método de pago-->
                        <div class="col-md-6">
                            <label for="metodo_pago" class="form-label">
                                Método de pago:</label>
                            <select required name="metodo_pago"
                                id="metodo_pago"
                                class="form-control selectpicker"
                                title="Selecciona">
                                @foreach ($optionsMetodoPago as $item)
                                <option value="{{$item->value}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            @error('metodo_pago')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>
                    </div>
                    </section>
                </div>
            </div>

            <!------venta producto---->
            <div class="col-12">
                <div class="sale-shell">
                    <section class="sale-section">
                        <div class="sale-section-header">
                            <h2 class="sale-section-title">Detalle de productos</h2>
                            <span class="sale-state sale-state--pending">Pendiente de cobro</span>
                        </div>
                    <div class="row gy-3">

                        <!-----Producto---->
                        <div class="col-12">
                            <select id="producto_id"
                                class="form-control selectpicker"
                                data-live-search="true" data-size="1"
                                title="Busque un producto aquí">
                                @foreach ($productos as $item)
                                <option value="{{$item->id}}-{{$item->cantidad}}-{{$item->precio}}-{{$item->nombre}}-{{$item->sigla}}">
                                    {{'Código: '. $item->codigo.' - '. $item->nombre.' - '.$item->sigla}}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <div class="row g-3 sale-grid-info">
                                <div class="col-sm-6">
                                    <label for="stock" class="form-label">En stock</label>
                                    <input disabled id="stock" type="text" class="form-control">
                                </div>
                                <div class="col-sm-6">
                                    <label for="precio" class="form-label">Precio</label>
                                    <input disabled id="precio" type="number" class="form-control" step="any">
                                </div>
                            </div>
                        </div>

                        <!-----Cantidad---->
                        <div class="col-md-6">
                            <label for="cantidad" class="form-label">
                                Cantidad:</label>
                            <input type="number" id="cantidad"
                                class="form-control">
                        </div>

                        <!-----botón para agregar--->
                        <div class="col-12 text-end">
                            <button id="btn_agregar" class="btn sale-btn sale-btn-primary" type="button">
                                <i class="fa-solid fa-plus me-1"></i>Agregar rápido</button>
                        </div>

                        <!-----Tabla para el detalle de la venta--->
                        <div class="col-12">
                            <div class="table-responsive sale-table-wrap">
                                <table id="tabla_detalle" class="table table-hover sale-table">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th class="text-white">Producto</th>
                                            <th class="text-white">Presentación</th>
                                            <th class="text-white">Cantidad</th>
                                            <th class="text-white">Precio</th>
                                            <th class="text-white">Subtotal</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th></th>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4">Sumas</th>
                                            <th colspan="2">
                                                <input type="hidden" name="subtotal"
                                                    value="0"
                                                    id="inputSubtotal">
                                                <span id="sumas">0</span>
                                                <span>{{$empresa->moneda->simbolo}}</span>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="4">
                                                {{$empresa->abreviatura_impuesto}} ({{$empresa->porcentaje_impuesto}})%
                                            </th>
                                            <th colspan="2">
                                                <input type="hidden" name="impuesto"
                                                    id="inputImpuesto"
                                                    value="0">
                                                <span id="igv">0</span>
                                                <span>{{$empresa->moneda->simbolo}}</span>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="4" class="sale-total-amount">Total</th>
                                            <th colspan="2">
                                                <input type="hidden" name="total" value="0" id="inputTotal">
                                                <span id="total">0</span>
                                                <span>{{$empresa->moneda->simbolo}}</span>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!--Boton para cancelar venta--->
                        <div class="col-12">
                            <button id="cancelar" type="button"
                                class="btn btn-danger sale-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Cancelar venta
                            </button>
                        </div>

                    </div>
                </section>
                </div>
            </div>

            <!----Finalizar venta-->
            <div class="col-12">
                <div class="sale-shell">
                    <section class="sale-section">
                        <div class="sale-section-header">
                            <h2 class="sale-section-title">Finalizar venta</h2>
                            <span class="sale-state sale-state--paid">Cobrado</span>
                        </div>

                    <div class="row gy-3">

                        <div class="col-md-6">
                            <label for="dinero_recibido" class="form-label">
                                Ingrese dinero recibido:</label>
                            <input type="number" id="dinero_recibido"
                                name="monto_recibido" class="form-control"
                                step="any">
                        </div>

                        <div class="col-md-6">
                            <label for="vuelto" class="form-label">
                                Vuelto:</label>
                            <input readonly type="number" name="vuelto_entregado"
                                id="vuelto" class="form-control" step="any">
                        </div>

                        <!--Botones--->
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-success sale-btn" id="guardar">
                                <i class="fa-solid fa-circle-check me-1"></i>Cobrar venta</button>
                        </div>
                    </div>
                    </section>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal para cancelar la venta -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Advertencia</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Seguro que quieres cancelar la venta?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button id="btnCancelarVenta" type="button" class="btn btn-danger" data-bs-dismiss="modal">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

</form>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
    $(document).ready(function() {

        $('#producto_id').change(mostrarValores);


        $('#btn_agregar').click(function() {
            agregarProducto();
        });

        $('#btnCancelarVenta').click(function() {
            cancelarVenta();
        });

        disableButtons();

        $('#dinero_recibido').on('input', function() {
            let dineroRecibido = parseFloat($(this).val());

            if (!isNaN(dineroRecibido) && dineroRecibido >= total && total > 0) {
                let vuelto = dineroRecibido - total;
                $('#vuelto').val(vuelto.toFixed(2));
            } else {
                $('#vuelto').val(''); 
            }
        });

    });

    //Variables
    let cont = 0;
    let subtotal = [];
    let sumas = 0;
    let igv = 0;
    let total = 0;
    let arrayIdProductos = [];

    //Constantes
    const impuesto = @json($empresa->porcentaje_impuesto);

    function mostrarValores() {
        let dataProducto = document.getElementById('producto_id').value.split('-');
        $('#stock').val(dataProducto[1]);
        $('#precio').val(dataProducto[2]);
    }

    function agregarProducto() {
        let dataProducto = document.getElementById('producto_id').value.split('-');
        //Obtener valores de los campos
        let idProducto = dataProducto[0];
        let nameProducto = dataProducto[3];
        let presentacioneProducto = dataProducto[4];
        let cantidad = $('#cantidad').val();
        let precioVenta = $('#precio').val();
        let stock = $('#stock').val();

        //Validaciones 
        //1.Para que los campos no esten vacíos
        if (idProducto != '' && cantidad != '') {

            //2. Para que los valores ingresados sean los correctos
            if (parseInt(cantidad) > 0 && (cantidad % 1 == 0)) {

                //3. Para que la cantidad no supere el stock
                if (parseInt(cantidad) <= parseInt(stock)) {

                    //4.No permitir el ingreso del mismo producto 
                    if (!arrayIdProductos.includes(idProducto)) {

                        //Calcular valores
                        subtotal[cont] = round(cantidad * precioVenta);
                        sumas = round(sumas + subtotal[cont]);
                        igv = round(sumas / 100 * impuesto);
                        total = round(sumas + igv);

                        //Crear la fila
                        let fila = '<tr id="fila' + cont + '">' +
                            '<td><input type="hidden" name="arrayidproducto[]" value="' + idProducto + '">' + nameProducto + '</td>' +
                            '<td>' + presentacioneProducto + '</td>' +
                            '<td><input type="hidden" name="arraycantidad[]" value="' + cantidad + '">' + cantidad + '</td>' +
                            '<td><input type="hidden" name="arrayprecioventa[]" value="' + precioVenta + '">' + precioVenta + '</td>' +
                            '<td>' + subtotal[cont] + '</td>' +
                            '<td><button class="btn btn-danger" type="button" onClick="eliminarProducto(' + cont + ',' + idProducto + ')"><i class="fa-solid fa-trash"></i></button></td>' +
                            '</tr>';

                        //Acciones después de añadir la fila
                        $('#tabla_detalle').append(fila);
                        limpiarCampos();
                        cont++;
                        disableButtons();

                        //Mostrar los campos calculados
                        $('#sumas').html(sumas);
                        $('#igv').html(igv);
                        $('#total').html(total);
                        $('#inputImpuesto').val(igv);
                        $('#inputTotal').val(total);
                        $('#inputSubtotal').val(sumas);

                        //Agregar el id del producto al arreglo
                        arrayIdProductos.push(idProducto);
                    } else {
                        showModal('Ya ha ingresado el producto');
                    }

                } else {
                    showModal('Cantidad incorrecta');
                }

            } else {
                showModal('Valores incorrectos');
            }

        } else {
            showModal('Le faltan campos por llenar');
        }

    }

    function eliminarProducto(indice, idProducto) {
        //Calcular valores
        sumas -= round(subtotal[indice]);
        igv = round(sumas / 100 * impuesto);
        total = round(sumas + igv);

        //Mostrar los campos calculados
        $('#sumas').html(sumas);
        $('#igv').html(igv);
        $('#total').html(total);
        $('#inputImpuesto').val(igv);
        $('#inputTotal').val(total);
        $('#inputSubtotal').val(sumas);

        //Eliminar el fila de la tabla
        $('#fila' + indice).remove();

        //Eliminar id del arreglo
        let index = arrayIdProductos.indexOf(idProducto.toString());
        arrayIdProductos.splice(index, 1);

        disableButtons();
    }

    function cancelarVenta() {
        //Elimar el tbody de la tabla
        $('#tabla_detalle tbody').empty();

        //Añadir una nueva fila a la tabla
        let fila = '<tr>' +
            '<th></th>' +
            '<td></td>' +
            '<td></td>' +
            '<td></td>' +
            '<td></td>' +
            '<td></td>' +
            '</tr>';
        $('#tabla_detalle').append(fila);

        //Reiniciar valores de las variables
        cont = 0;
        subtotal = [];
        sumas = 0;
        igv = 0;
        total = 0;
        arrayIdProductos = [];

        //Mostrar los campos calculados
        $('#sumas').html(sumas);
        $('#igv').html(igv);
        $('#total').html(total);
        $('#inputImpuesto').val(igv);
        $('#inputTotal').val(total);
        $('#inputSubtotal').val(sumas);

        limpiarCampos();
        disableButtons();
    }

    function disableButtons() {
        if (total == 0) {
            $('#guardar').hide();
            $('#cancelar').hide();
        } else {
            $('#guardar').show();
            $('#cancelar').show();
        }
    }

    function limpiarCampos() {
        let select = $('#producto_id');
        select.selectpicker('val', '');
        $('#cantidad').val('');
        $('#precio').val('');
        $('#stock').val('');
    }

    function showModal(message, icon = 'error') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: icon,
            title: message
        })
    }

    function round(num, decimales = 2) {
        var signo = (num >= 0 ? 1 : -1);
        num = num * signo;
        if (decimales === 0) //con 0 decimales
            return signo * Math.round(num);
        // round(x * 10 ^ decimales)
        num = num.toString().split('e');
        num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales) : decimales)));
        // x * 10 ^ (-decimales)
        num = num.toString().split('e');
        return signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales));
    }
    //Fuente: https://es.stackoverflow.com/questions/48958/redondear-a-dos-decimales-cuando-sea-necesario
</script>
@endpush