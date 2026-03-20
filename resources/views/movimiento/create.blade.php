@extends('layouts.app')

@section('title','Nuevo retiro')

@section('content')
<div class="container-fluid px-4 page-shell">
    <x-ui.page-header title="Nuevo retiro" subtitle="Registra salidas de efectivo o movimientos manuales de la caja activa." />

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cajas.index') }}">Cajas</a></li>
        <li class="breadcrumb-item"><a href="{{ route('movimientos.index', ['caja_id' => $caja_id]) }}">Movimientos de caja</a></li>
        <li class="breadcrumb-item active">Nuevo retiro</li>
    </ol>

    <x-forms.template :action="route('movimientos.store')" method="post">
        <x-slot name="header">
            <div>
                <h2 class="mb-1 fs-5">Datos del movimiento</h2>
                <p class="text-muted mb-0">Completa la información necesaria para registrar el retiro en la caja seleccionada.</p>
            </div>
        </x-slot>

        <div class="row g-4">
            <div class="col-12">
                <x-forms.input id="descripcion" required="true" labelText="Descripción del retiro" />
            </div>

            <div class="col-md-6">
                <label for="metodo_pago" class="form-label form-label-modern">Método de retiro <span class="text-danger">*</span></label>
                <select required name="metodo_pago" id="metodo_pago" class="form-select @error('metodo_pago') is-invalid @enderror">
                    <option value="" disabled {{ old('metodo_pago') ? '' : 'selected' }}>Selecciona un método</option>
                    @foreach ($optionsMetodoPago as $item)
                        <option value="{{ $item->value }}" @selected(old('metodo_pago') === $item->value)>{{ $item->label() }}</option>
                    @endforeach
                </select>
                @error('metodo_pago')
                    <small class="invalid-feedback d-block">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-6">
                <x-forms.input id="monto" required="true" labelText="Monto del retiro" type="number" />
            </div>

            <input type="hidden" name="caja_id" value="{{ $caja_id }}">
            <input type="hidden" name="tipo" value="RETIRO">
        </div>

        <x-slot name="footer">
            <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 w-100">
                <a href="{{ route('movimientos.index', ['caja_id' => $caja_id]) }}" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar retiro</button>
            </div>
        </x-slot>
    </x-forms.template>
</div>
@endsection
