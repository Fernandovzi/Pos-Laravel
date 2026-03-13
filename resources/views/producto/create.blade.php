@extends('layouts.app')

@section('title','Crear Producto')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<!-- CSS -->

@endpush

@section('content')
<div class="container-fluid px-4 page-shell">
    <h1 class="mt-4 text-center">Crear Producto</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('productos.index')}}">Productos</a></li>
        <li class="breadcrumb-item active">Crear producto</li>
    </ol>

    <div class="card">
        <form action="{{ route('productos.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card-body text-bg-light">

                <div class="row g-4">

                    <!---Nombre---->
                    <div class="col-12">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre')}}">
                        @error('nombre')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!---Descripción---->
                    <div class="col-12">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <textarea name="descripcion" id="descripcion" rows="3" class="form-control">{{old('descripcion')}}</textarea>
                        @error('descripcion')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                </div>

                <br>

                <div class="row g-4">

                    <div class="col-md-6">

                        <div class="row g-4">

                            <!---Imagen---->
                            <div class="col-12">
                                <label for="img_path" class="form-label">Imagen:</label>
                                <input type="file" name="img_path" id="img_path" class="form-control" accept="image/*">
                                @error('img_path')
                                <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!----Codigo---->
                            <div class="col-12">
                                <label for="codigo" class="form-label">Código:</label>
                                <input type="text" name="codigo" id="codigo" class="form-control" value="{{old('codigo')}}" readonly>
                                @error('codigo')
                                <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!---Proveedor---->
                            <div class="col-12">
                                <label for="proveedore_id" class="form-label">Proveedor:</label>
                                <select data-size="4"
                                    title="Seleccione proveedor"
                                    data-live-search="true"
                                    name="proveedore_id"
                                    id="proveedore_id"
                                    class="form-control selectpicker show-tick">
                                    <option value="">No tiene proveedor</option>
                                    @foreach ($proveedores as $item)
                                    <option value="{{$item->id}}" {{ old('proveedore_id') == $item->id ? 'selected' : '' }}>{{$item->nombre_documento}}</option>
                                    @endforeach
                                </select>
                                @error('proveedore_id')
                                <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!---Presentaciones---->
                            <div class="col-12">
                                <label for="presentacione_id" class="form-label">Presentación:</label>
                                <select data-size="4"
                                    title="Seleccione una presentación"
                                    data-live-search="true"
                                    name="presentacione_id"
                                    id="presentacione_id"
                                    class="form-control selectpicker show-tick">
                                    @foreach ($presentaciones as $item)
                                    <option value="{{$item->id}}" {{ old('presentacione_id') == $item->id ? 'selected' : '' }}>
                                        {{$item->nombre}}
                                    </option>
                                    @endforeach
                                </select>
                                @error('presentacione_id')
                                <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!---Categorías---->
                            <div class="col-12">
                                <label for="categoria_id" class="form-label">Categoría:</label>
                                <select data-size="4"
                                    title="Seleccione la categoría"
                                    data-live-search="true"
                                    name="categoria_id"
                                    id="categoria_id"
                                    class="form-control selectpicker show-tick">
                                    <option value="">No tiene categoría</option>
                                    @foreach ($categorias as $item)
                                    <option value="{{$item->id}}" {{ old('categoria_id') == $item->id ? 'selected' : '' }}>
                                        {{$item->nombre}}
                                    </option>
                                    @endforeach
                                </select>
                                @error('categoria_id')
                                <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!-- Costo -->
                            <div class="col-12">
                                <label for="costo" class="form-label">Costo:</label>
                                <input
                                    type="number"
                                    name="costo"
                                    id="costo"
                                    class="form-control"
                                    value="{{ old('costo') }}"
                                    step="0.01"
                                    min="0">
                                @error('costo')
                                <small class="text-danger">{{ '*'.$message }}</small>
                                @enderror
                            </div>

                            <!-- Precio -->
                            <div class="col-12">
                                <label for="precio" class="form-label">Precio:</label>
                                <input
                                    type="number"
                                    name="precio"
                                    id="precio"
                                    class="form-control"
                                    value="{{ old('precio') }}"
                                    step="0.01"
                                    min="0">
                                @error('precio')
                                <small class="text-danger">{{ '*'.$message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p>Imagen del producto:</p>
                        <img id="img-default"
                            class="img-fluid img-formulario"
                            src="{{ asset('assets/img/maleri.png') }}"
                            alt="Imagen por defecto">
                        <img id="img-preview"
                            class="img-fluid img-thumbnail img-formulario is-hidden"
                            src=""
                            alt="Ha cargado un archivo no compatible">
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
    const inputImagen = document.getElementById('img_path');
    const imagenPreview = document.getElementById('img-preview');
    const imagenDefault = document.getElementById('img-default');

    inputImagen.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                imagenPreview.src = e.target.result;
                imagenPreview.classList.remove('is-hidden');
                imagenDefault.classList.add('is-hidden');
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endpush