@extends('layouts.app')

@section('title','Crear cliente')

@push('css')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<style>
    #box-razon-social {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Cliente</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('clientes.index')}}">Clientes</a></li>
        <li class="breadcrumb-item active">Crear cliente</li>
    </ol>

    <div class="card">
        <form action="{{ route('clientes.store') }}" method="post">
            @csrf
            <div class="card-body text-bg-light">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="tipo" class="form-label">Tipo de cliente:</label>
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

                    <div class="col-12" id="box-razon-social">
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
