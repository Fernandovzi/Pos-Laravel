@extends('layouts.app')

@section('title','Editar Producto')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
@endpush

@section('content')
<div class="container-fluid px-4 page-shell">
    <div class="page-heading">
    <h1 class="page-title">Editar Producto</h1>
</div>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('productos.index')}}">Productos</a></li>
        <li class="breadcrumb-item active">Editar producto</li>
    </ol>

    <div class="card text-bg-light">
        <form action="{{route('productos.update',['producto'=>$producto])}}" method="post" enctype="multipart/form-data">
            @method('PATCH')
            @csrf
            <div class="card-body">

                <div class="row g-4">

                    <!----Codigo---->
                    <div class="col-md-6">
                        <label for="codigo" class="form-label">Código:</label>
                        <input type="text" name="codigo" id="codigo"
                            class="form-control"
                            value="{{old('codigo',$producto->codigo)}}" readonly>
                        @error('codigo')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <p class="form-label">Código de barras</p>

                        <?php
                        require base_path('vendor/autoload.php');
                        $codigo = $producto->codigo;
                        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();

                        try {
                            echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($codigo, $generator::TYPE_EAN_13)) . '">';
                        } catch (Exception $e) {
                            echo "Formato no compatible";
                        }
                        ?>
                    </div>

                    <!---Nombre---->
                    <div class="col-12">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text"
                            name="nombre"
                            id="nombre"
                            class="form-control"
                            value="{{old('nombre',$producto->nombre)}}">
                        @error('nombre')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!---Descripción---->
                    <div class="col-12">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <textarea
                            name="descripcion"
                            id="descripcion"
                            rows="3"
                            class="form-control">{{old('descripcion',$producto->descripcion)}}</textarea>
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

                            <!---Proveedor---->
                            <div class="col-12">
                                <label for="proveedore_id" class="form-label">Proveedor:</label>
                                <select data-size="4"
                                    title="Seleccione un proveedor"
                                    data-live-search="true"
                                    name="proveedore_id"
                                    id="proveedore_id"
                                    class="form-control selectpicker show-tick">
                                    <option value="">No tiene proveedor</option>
                                    @foreach ($proveedores as $item)
                                    <option value="{{$item->id}}"
                                        {{$producto->proveedore_id == $item->id || old('proveedore_id') == $item->id ? 'selected' : '' }}>
                                        {{$item->nombre_documento}}
                                    </option>
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
                                    <option value="{{$item->id}}"
                                        {{$producto->presentacione_id == $item->id || old('presentacione_id') == $item->id ? 'selected' : '' }}>
                                        {{$item->nombre}}
                                    </option>
                                    @endforeach
                                </select>
                                @error('presentacione_id')
                                <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!---Categoría---->
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
                                    <option value="{{$item->id}}"
                                        {{ $producto->categoria_id == $item->id || old('categoria_id') == $item->id ? 'selected' : '' }}>
                                        {{$item->nombre}}
                                    </option>
                                    @endforeach
                                </select>
                                @error('categoria_id')
                                <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Costo -->
                        <div class="col-12">
                            <label for="costo" class="form-label">Costo:</label>
                            <input
                                type="number"
                                name="costo"
                                id="costo"
                                class="form-control"
                                value="{{ old('costo', $producto->costo) }}"
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
                                value="{{ old('precio', $producto->precio) }}"
                                step="0.01"
                                min="0">
                            @error('precio')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                    </div>

                    <div class="col-md-6">
                        <p>Imagen del producto:</p>
                        <img id="img-default"
                            class="img-fluid img-formulario"
                            src="{{ $producto->img_path ? asset($producto->img_path) : asset('assets/img/maleri.png') }}"
                            alt="Imagen por defecto">
                        <img id="img-preview"
                            class="img-fluid img-thumbnail img-formulario is-hidden"
                            src=""
                            alt="Ha cargado un archivo no compatible">

                    </div>

                </div>

            </div>
            <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <button type="reset" class="btn btn-secondary">Reiniciar</button>
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