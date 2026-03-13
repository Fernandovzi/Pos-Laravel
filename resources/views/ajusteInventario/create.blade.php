@extends('layouts.app')

@section('title','Crear ajuste de inventario')

@push('css-datatable')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
@endpush

@section('content')
<div class="container-fluid px-4 page-shell">
    <div class="page-heading">
    <h1 class="page-title">Crear ajuste de inventario</h1>
</div>

    <x-breadcrumb.template>
        <x-breadcrumb.item :href="route('panel')" content="Inicio" />
        <x-breadcrumb.item :href="route('ajustes-inventario.index')" content="Ajustes de inventario" />
        <x-breadcrumb.item active='true' content="Crear ajuste" />
    </x-breadcrumb.template>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('ajustes-inventario.store') }}" method="POST" id="ajusteForm">
                @csrf

                <div class="mb-3">
                    <label for="producto_id" class="form-label">Producto</label>
                    <select name="producto_id" id="producto_id" class="form-control selectpicker" data-live-search="true" title="Busque un producto" required>
                        @foreach ($productos as $producto)
                        <option value="{{ $producto->id }}"
                            data-stock="{{ $producto->inventario->cantidad ?? 0 }}"
                            {{ old('producto_id') == $producto->id ? 'selected' : '' }}>
                            {{ $producto->nombre_completo }}
                        </option>
                        @endforeach
                    </select>
                    @error('producto_id')
                    <small class="text-danger">{{ '*'.$message }}</small>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Stock actual</label>
                        <input type="text" class="form-control" id="stock_actual" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="cantidad_fisica" class="form-label">Cantidad física encontrada</label>
                        <input type="number" min="0" class="form-control" id="cantidad_fisica" name="cantidad_fisica" value="{{ old('cantidad_fisica') }}" required>
                        @error('cantidad_fisica')
                        <small class="text-danger">{{ '*'.$message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Diferencia</label>
                        <input type="text" class="form-control" id="diferencia" readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipo de movimiento (calculado)</label>
                    <input type="text" class="form-control" id="tipo_movimiento" readonly>
                </div>

                <div class="mb-3">
                    <label for="motivo" class="form-label">Motivo / observación</label>
                    <textarea name="motivo" id="motivo" rows="3" maxlength="500" class="form-control">{{ old('motivo') }}</textarea>
                    @error('motivo')
                    <small class="text-danger">{{ '*'.$message }}</small>
                    @enderror
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="confirmar_ajuste" id="confirmar_ajuste" value="1" required>
                    <label class="form-check-label" for="confirmar_ajuste">
                        Confirmo que el conteo físico es correcto y deseo aplicar el ajuste.
                    </label>
                    @error('confirmar_ajuste')
                    <br><small class="text-danger">{{ '*'.$message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Aplicar ajuste</button>
                <a href="{{ route('ajustes-inventario.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
    const productoSelect = document.getElementById('producto_id');
    const stockInput = document.getElementById('stock_actual');
    const cantidadFisicaInput = document.getElementById('cantidad_fisica');
    const diferenciaInput = document.getElementById('diferencia');
    const tipoMovimientoInput = document.getElementById('tipo_movimiento');

    function actualizarVista() {
        const option = productoSelect.options[productoSelect.selectedIndex];
        const stock = Number(option?.dataset?.stock || 0);
        const fisico = Number(cantidadFisicaInput.value || 0);
        const diferencia = fisico - stock;

        stockInput.value = stock;
        diferenciaInput.value = diferencia;

        if (diferencia > 0) {
            tipoMovimientoInput.value = 'ENTRADA';
        } else if (diferencia < 0) {
            tipoMovimientoInput.value = 'SALIDA';
        } else {
            tipoMovimientoInput.value = 'SIN CAMBIO';
        }
    }

    productoSelect.addEventListener('change', actualizarVista);
    cantidadFisicaInput.addEventListener('input', actualizarVista);

    document.getElementById('ajusteForm').addEventListener('submit', function(e) {
        if (!confirm('¿Está seguro de aplicar este ajuste de inventario?')) {
            e.preventDefault();
        }
    });

    actualizarVista();
</script>
@endpush
