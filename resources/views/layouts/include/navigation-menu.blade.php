<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark modern-sidenav" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">

                <x-nav.heading>Inicio</x-nav.heading>

                <x-nav.nav-link content='Principal'
                    icon='fas fa-tachometer-alt'
                    :href="route('panel')" />

                <x-nav.heading>Modulos</x-nav.heading>

                @can('ver-categoria')
                <x-nav.nav-link content='Categorías'
                    icon='fa-solid fa-tag'
                    :href="route('categorias.index')" />
                @endcan

                @can('ver-presentacione')
                <x-nav.nav-link content='Presentaciones'
                    icon='fa-solid fa-layer-group'
                    :href="route('presentaciones.index')" />
                @endcan
                @can('ver-producto')
                <x-nav.nav-link content='Productos'
                    icon='fa-brands fa-shopify'
                    :href="route('productos.index')" />
                @endcan

                @can('ver-kardex')
                <x-nav.nav-link content='Kardex'
                    icon='fa-solid fa-file'
                    :href="route('kardex.index')" />
                @endcan

                <x-nav.nav-link content='Ajustes de inventario'
                    icon='fa-solid fa-sliders'
                    :href="route('ajustes-inventario.index')" />

                @can('ver-cliente')
                <x-nav.nav-link content='Clientes'
                    icon='fa-solid fa-users'
                    :href="route('clientes.index')" />
                @endcan

                @can('ver-proveedore')
                <x-nav.nav-link content='Proveedores'
                    icon='fa-solid fa-user-group'
                    :href="route('proveedores.index')" />
                @endcan

                @can('ver-caja')
                <x-nav.nav-link content='Cajas'
                    icon='fa-solid fa-money-bill'
                    :href="route('cajas.index')" />
                @endcan

                <!----Producción Interna---->
                @can('ver-compra')
                <x-nav.link-collapsed
                    id="collapseCompras"
                    icon="fa-solid fa-store"
                    content="Producción">
                    @can('ver-compra')
                    <x-nav.link-collapsed-item :href="route('compras.index')" content="Ver" />
                    @endcan

                    @can('crear-compra')
                    <x-nav.link-collapsed-item :href="route('compras.create')" content="Crear" />
                    @endcan
                </x-nav.link-collapsed>
                @endcan



                <!----Ventas---->
                @can('ver-venta')
                <x-nav.link-collapsed
                    id="collapseVentas"
                    icon="fa-solid fa-cart-shopping"
                    content="Ventas">
                    @can('ver-venta')
                    <x-nav.link-collapsed-item :href="route('ventas.index')" content="Ver" />
                    @endcan

                    @can('crear-venta')
                    <x-nav.link-collapsed-item :href="route('ventas.create')" content="Crear" />
                    @endcan
                </x-nav.link-collapsed>
                @endcan

                <x-nav.link-collapsed
                    id="collapsePedidos"
                    icon="fa-solid fa-receipt"
                    content="Pedidos">
                    <x-nav.link-collapsed-item :href="route('pedidos.index')" content="Ver" />
                    <x-nav.link-collapsed-item :href="route('pedidos.create')" content="Crear" />
                </x-nav.link-collapsed>

                @hasrole('administrador')
                <x-nav.heading>Datos de la empresa</x-nav.heading>
                @endhasrole

                @can('ver-empresa')
                <x-nav.nav-link content='Empresa'
                    icon='fa-solid fa-city'
                    :href="route('empresa.index')" />
                @endcan

                @can('ver-empleado')
                <x-nav.nav-link content='Empleados'
                    icon='fa-solid fa-users'
                    :href="route('empleados.index')" />
                @endcan

                @can('ver-user')
                <x-nav.nav-link content='Usuarios'
                    icon='fa-solid fa-user'
                    :href="route('users.index')" />
                @endcan

                @can('ver-role')
                <x-nav.nav-link content='Roles'
                    icon='fa-solid fa-person-circle-plus'
                    :href="route('roles.index')" />
                @endcan


            </div>
        </div>
    </nav>
</div>