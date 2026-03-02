@extends('layouts.app')

@section('title','Realizar venta')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .pos-sale-page {
        --pos-primary: #2563eb;
        --pos-secondary: #0f766e;
        --pos-success: #16a34a;
        --pos-warning: #d97706;
        --pos-danger: #dc2626;
        --pos-bg: #f3f6fb;
        --pos-surface: #ffffff;
        --pos-border: #dbe4f0;
        --pos-text: #0f172a;
        --pos-muted: #475569;
        --pos-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
        background: var(--pos-bg);
        color: var(--pos-text);
        min-height: 100%;
        padding-bottom: 2rem;
    }

    @media (prefers-color-scheme: dark) {
        .pos-sale-page {
            --pos-primary: #60a5fa;
            --pos-secondary: #2dd4bf;
            --pos-success: #4ade80;
            --pos-warning: #fbbf24;
            --pos-danger: #f87171;
            --pos-bg: #0b1220;
            --pos-surface: #111b2e;
            --pos-border: #243247;
            --pos-text: #e2e8f0;
            --pos-muted: #93a4bc;
            --pos-shadow: 0 12px 30px rgba(0, 0, 0, 0.35);
        }
    }

    .pos-card {
        background: var(--pos-surface);
        border: 1px solid var(--pos-border);
        border-radius: 16px;
        box-shadow: var(--pos-shadow);
        overflow: hidden;
    }

    .pos-card-header {
        background: linear-gradient(90deg, var(--pos-primary), #3b82f6);
        color: #fff;
        font-weight: 700;
        letter-spacing: .02em;
        padding: .85rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .pos-card-header--success {
        background: linear-gradient(90deg, var(--pos-secondary), #14b8a6);
    }

    .pos-card-body { padding: 1.25rem; }

    .pos-sale-page .form-label {
        font-weight: 600;
        color: var(--pos-muted);
    }

    .pos-sale-page .form-control,
    .pos-sale-page .bootstrap-select .dropdown-toggle {
        min-height: 48px;
        border-radius: 12px;
        border: 1px solid var(--pos-border);
        font-size: 1rem;
    }

    .pos-quick-btn {
        min-height: 52px;
        padding-inline: 1.4rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
    }

    .pos-table thead th {
        background: #1e3a8a;
        color: #fff;
        padding: .85rem .75rem;
        border: 0;
    }

    .pos-table tbody td, .pos-table tbody th {
        padding: .85rem .75rem;
        vertical-align: middle;
        font-size: .98rem;
    }

    .pos-table tfoot th {
        color: var(--pos-text);
        background: rgba(37,99,235,.06);
    }

    .pos-status {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: .25rem .65rem;
        font-size: .8rem;
        font-weight: 700;
        margin-left: .4rem;
    }

    .pos-status--active { background: rgba(22,163,74,.15); color: var(--pos-success); }
    .pos-status--paid { background: rgba(37,99,235,.15); color: var(--pos-primary); }
    .pos-status--pending { background: rgba(217,119,6,.18); color: var(--pos-warning); }
    .pos-status--cancelled { background: rgba(220,38,38,.18); color: var(--pos-danger); }

    .pos-total { font-size: 1.25rem; font-weight: 800; }
</style>

@endpush

@section('content')
<div class="pos-sale-page">
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center fw-bold">POS · Realizar Venta</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ventas.index')}}">Ventas</a></li>
        <li class="breadcrumb-item active">Realizar Venta</li>
    </ol>
</div>

<form action="{{ route('ventas.store') }}" method="post" class="pos-form">
    @csrf
    <div class="container-lg mt-4">
        <div class="row gy-4">

            <!-----Venta---->
            <div class="col-12">
                <div class="pos-card">
                    <div class="pos-card-header pos-card-header--success">
                        <span>Datos generales</span>
                        <span class="pos-status pos-status--active">Activo</span>
                    </div>
                    <div class="pos-card-body">
                    <div class="row g-4">

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
                </div>
                </div>
            </div>

            <!------venta producto---->
            <div class="col-12">
                <div class="pos-card">
                    <div class="pos-card-header">
                        <span>Detalles de la venta</span>
                        <div>
                            <span class="pos-status pos-status--pending">Pendiente</span>
                            <span class="pos-status pos-status--cancelled">Cancelado</span>
                        </div>
                    </div>
                    <div class="pos-card-body">
                    <div class="row gy-4">

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

                        <!-----Stock--->
                        <div class="d-flex justify-content-end">
                            <div class="col-12 col-sm-6">
                                <div class="row">
                                    <label for="stock" class="col-form-label col-4">
                                        En stock:</label>
                                    <div class="col-8">
                                        <input disabled id="stock"
                                            type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-----Precio -->
                        <div class="d-flex justify-content-end">
                            <div class="col-12 col-sm-6">
                                <div class="row">
                                    <label for="precio" class="col-form-label col-4">
                                        Precio:</label>
                                    <div class="col-8">
                                        <input disabled id="precio"
                                            type="number" class="form-control"
                                            step="any">
                                    </div>
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
                            <button id="btn_agregar" class="btn btn-primary pos-quick-btn" type="button">
                                <i class="fa-solid fa-plus me-1"></i>Agregar rápido</button>
                        </div>

                        <!-----Tabla para el detalle de la venta--->
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tabla_detalle" class="table table-hover pos-table">
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
                                            <th colspan="4" class="pos-total">Total</th>
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
                                class="btn btn-danger pos-quick-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Cancelar venta
                            </button>
                        </div>

                    </div>
                </div>
                </div>
            </div>

            <!----Finalizar venta-->
            <div class="col-12">
                <div class="pos-card">
                    <div class="pos-card-header">
                        <span>Finalizar venta</span>
                        <span class="pos-status pos-status--paid">Cobrado</span>
                    </div>

                    <div class="pos-card-body">

                    <div class="row gy-4">

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
                            <button type="submit" class="btn btn-success pos-quick-btn" id="guardar">
                                <i class="fa-solid fa-circle-check me-1"></i>Cobrar venta</button>
                        </div>
                    </div>
                </div>
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