@extends('layouts.app')

@section('title','Editar cliente')

@section('content')
<div class="container-fluid px-4 page-shell">
    <div class="page-heading">
    <h1 class="page-title">Editar Cliente</h1>
</div>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('clientes.index')}}">Clientes</a></li>
        <li class="breadcrumb-item active">Editar cliente</li>
    </ol>

    <div class="card text-bg-light">
        <form action="{{ route('clientes.update',['cliente'=>$cliente]) }}" method="post">
            @method('PUT')
            @csrf

            <div class="card-body">
                <p class="text-muted">Tipo: <span class="fw-bold">{{ strtoupper($cliente->persona->tipo->value)}}</span></p>

                <div class="row g-3">
                    <div class="col-12">
                        <label for="razon_social" class="form-label">
                            {{ $cliente->persona->tipo->value == 'FISICA' ? 'Nombres y apellidos:' : 'Nombre de la empresa:'}}
                        </label>
                        <input required type="text" name="razon_social" id="razon_social" class="form-control" value="{{old('razon_social', $cliente->persona->razon_social)}}">
                        @error('razon_social')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="direccion" class="form-label">Dirección:</label>
                        <input type="text" name="direccion" id="direccion" class="form-control" value="{{old('direccion', $cliente->persona->direccion)}}">
                        @error('direccion')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Correo eléctronico:</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{old('email', $cliente->persona->email)}}">
                        @error('email')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="telefono" class="form-label">Teléfono:</label>
                        <input type="number" name="telefono" id="telefono" class="form-control" value="{{old('telefono', $cliente->persona->telefono)}}">
                        @error('telefono')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="rfc" class="form-label">RFC:</label>
                        <input type="text" name="rfc" id="rfc" maxlength="13" class="form-control" value="{{old('rfc', $cliente->persona->rfc)}}">
                        @error('rfc')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="regimen_fiscal" class="form-label">Régimen fiscal (SAT):</label>
                        <select class="form-select" name="regimen_fiscal" id="regimen_fiscal">
                            <option value="">Seleccione una opción</option>
                            @foreach ($regimenesFiscales as $item)
                            <option value="{{ $item->clave }}" {{ old('regimen_fiscal', $cliente->persona->regimen_fiscal) == $item->clave ? 'selected' : '' }}>
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
                        <input type="text" name="codigo_postal_fiscal" id="codigo_postal_fiscal" maxlength="5" class="form-control" value="{{old('codigo_postal_fiscal', $cliente->persona->codigo_postal_fiscal)}}">
                        @error('codigo_postal_fiscal')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="uso_cfdi" class="form-label">Uso CFDI (SAT):</label>
                        <select class="form-select" name="uso_cfdi" id="uso_cfdi">
                            <option value="">Seleccione una opción</option>
                            @foreach ($usosCfdi as $item)
                            <option value="{{ $item->clave }}" {{ old('uso_cfdi', $cliente->persona->uso_cfdi) == $item->clave ? 'selected' : '' }}>
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
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection
