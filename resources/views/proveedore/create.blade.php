@extends('layouts.app')

@section('title','Crear proveedor')

@push('css')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
@endpush

@section('content')
<div class="container-fluid px-4 page-shell">
    <div class="page-heading">
    <h1 class="page-title">Crear Proveedor</h1>
</div>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('proveedores.index')}}">Proveedor</a></li>
        <li class="breadcrumb-item active">Crear proveedor</li>
    </ol>

    <div class="card">
        <form action="{{ route('proveedores.store') }}" method="post">
            @csrf
            <div class="card-body text-bg-light">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="tipo" class="form-label">Tipo de proveedor:</label>
                        <select class="form-select" name="tipo" id="tipo">
                            <option value="" selected disabled>Seleccione una opción</option>
                            @foreach ($optionsTipoPersona as $item)
                            <option value="{{$item->value}}" {{ old('tipo') == $item->value ? 'selected' : '' }}>{{$item->name}}</option>
                            @endforeach
                        </select>
                        @error('tipo')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <div class="col-12 field-toggle-hidden" id="box-razon-social">
                        <label id="label-fisica" for="razon_social" class="form-label">Nombres y apellidos:</label>
                        <label id="label-moral" for="razon_social" class="form-label">Nombre de la empresa:</label>

                        <input required type="text" name="razon_social" id="razon_social" class="form-control" value="{{old('razon_social')}}">

                        @error('razon_social')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="direccion" class="form-label">Dirección:</label>
                        <input type="text" name="direccion" id="direccion" class="form-control" value="{{old('direccion')}}">
                        @error('direccion')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <x-forms.input id="email" type='email' labelText='Correo eléctronico' />
                    </div>

                    <div class="col-md-6">
                        <x-forms.input id="telefono" type='number' />
                    </div>

                    <div class="col-md-6">
                        <label for="rfc" class="form-label">RFC:</label>
                        <input type="text" name="rfc" id="rfc" maxlength="13" class="form-control" value="{{ old('rfc') }}">
                        @error('rfc')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="regimen_fiscal" class="form-label">Régimen fiscal (SAT):</label>
                        <select class="form-select" name="regimen_fiscal" id="regimen_fiscal">
                            <option value="">Seleccione una opción</option>
                            @foreach ($regimenesFiscales as $item)
                            <option value="{{ $item->clave }}" {{ old('regimen_fiscal') == $item->clave ? 'selected' : '' }}>
                                {{ $item->clave }} - {{ $item->descripcion }}
                            </option>
                            @endforeach
                        </select>
                        @error('regimen_fiscal')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="codigo_postal_fiscal" class="form-label">Código postal fiscal:</label>
                        <input type="text" name="codigo_postal_fiscal" id="codigo_postal_fiscal" maxlength="5" class="form-control" value="{{ old('codigo_postal_fiscal') }}">
                        @error('codigo_postal_fiscal')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="uso_cfdi" class="form-label">Uso CFDI (SAT):</label>
                        <select class="form-select" name="uso_cfdi" id="uso_cfdi">
                            <option value="">Seleccione una opción</option>
                            @foreach ($usosCfdi as $item)
                            <option value="{{ $item->clave }}" {{ old('uso_cfdi') == $item->clave ? 'selected' : '' }}>
                                {{ $item->clave }} - {{ $item->descripcion }}
                            </option>
                            @endforeach
                        </select>
                        @error('uso_cfdi')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#tipo').on('change', function() {
            let selectValue = $(this).val();
            if (selectValue == 'FISICA') {
                $('#label-moral').hide();
                $('#label-fisica').show();
            } else {
                $('#label-fisica').hide();
                $('#label-moral').show();
            }

            $('#box-razon-social').show();
        });
    });
</script>
@endpush
