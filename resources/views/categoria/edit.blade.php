@extends('layouts.app')

@section('title','Editar categoría')



@section('content')
<div class="container-fluid px-4 page-shell">
    <div class="page-heading">
    <h1 class="page-title">Editar Categoría</h1>
</div>

    <x-breadcrumb.template>
        <x-breadcrumb.item :href="route('panel')" content="Inicio" />
        <x-breadcrumb.item :href="route('categorias.index')" content="Categorías" />
        <x-breadcrumb.item active='true' content="Editar categoría" />
    </x-breadcrumb.template>

    <x-forms.template :action="route('categorias.update',['categoria'=>$categoria])" method='post' patch='true'>

        <div class="row g-4">

            <div class="col-md-6">
                <x-forms.input id="nombre"
                    :defaultValue='$categoria->caracteristica->nombre'
                    required='true' />
            </div>

            <div class="col-12">
                <x-forms.textarea id="descripcion"
                    :defaultValue='$categoria->caracteristica->descripcion' />
            </div>

        </div>

        <x-slot name='footer'>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <button type="reset" class="btn btn-secondary">Reiniciar</button>
        </x-slot>


    </x-forms.template>

</div>
@endsection

@push('js')

@endpush