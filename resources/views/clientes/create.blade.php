@extends('layouts.app')

@section('content')

<div class="container">
    <h3 class="mb-4">Registrar Cliente para Facturación CFDI 4.0</h3>

    <form action="{{ route('clientes.store') }}" method="POST">
        @csrf

        <div class="row">

            <div class="col-md-4 mb-3">
                <label>RFC *</label>
                <input type="text" name="rfc" class="form-control" required>
            </div>

            <div class="col-md-8 mb-3">
                <label>Razón Social *</label>
                <input type="text" name="razon_social" class="form-control" required>
            </div>

            <div class="col-md-4 mb-3">
                <label>Código Postal *</label>
                <input type="text" name="codigo_postal" class="form-control" required>
            </div>

            <div class="col-md-4 mb-3">
                <label>Uso de CFDI *</label>
                <select name="uso_cfdi" class="form-control">
                    <option value="G01">G01 - Adquisición de mercancías</option>
                    <option value="G03">G03 - Gastos en general</option>
                    <option value="P01">P01 - Por definir</option>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label>Régimen Fiscal</label>
                <input type="text" name="regimen_fiscal" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Correo de Facturación</label>
                <input type="email" name="correo_facturacion" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Teléfono</label>
                <input type="text" name="telefono" class="form-control">
            </div>

            <h5 class="mt-4">Dirección (Opcional)</h5>

            <div class="col-md-6 mb-3">
                <label>Calle</label>
                <input type="text" name="calle" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
                <label>Número Exterior</label>
                <input type="text" name="numero_exterior" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
                <label>Número Interior</label>
                <input type="text" name="numero_interior" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Colonia</label>
                <input type="text" name="colonia" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Municipio</label>
                <input type="text" name="municipio" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Estado</label>
                <input type="text" name="estado" class="form-control">
            </div>

        </div>

        <button class="btn btn-primary mt-3">Guardar Cliente</button>

    </form>
</div>

@endsection
