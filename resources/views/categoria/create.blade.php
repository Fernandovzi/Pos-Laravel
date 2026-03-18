@extends('layouts.app')

@section('title','Crear categoría')

@section('content')
<div class="container-fluid px-4 page-shell">
    <x-ui.page-header title="Crear Categoría" />
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('categorias.index')}}">Categorías</a></li>
        <li class="breadcrumb-item active">Crear categoría</li>
    </ol>

    <x-forms.template :action="route('categorias.store')" method='post'>

        <div class="row g-4">

            <div class="col-md-6">
                <x-forms.input id="nombre" required='true' />
            </div>

            <div class="col-12">
                <x-forms.textarea id="descripcion"/>
            </div>
        </div>

       <x-slot name='footer'>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </x-slot>

    </x-forms.template>


</div>
@endsection

@push('js')

@endpush