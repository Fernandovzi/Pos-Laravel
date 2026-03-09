<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Catálogo de productos para punto de venta." />
    <meta name="author" content="BlueCrow" />
    <title>BlueCrow | Catálogo de productos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-md bg-body-secondary sticky-top border-bottom border-secondary-subtle">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('panel') }}">
                <img src="{{ asset('assets/img/icon.png') }}" alt="Logo" width="30" height="30"
                    class="d-inline-block align-text-top">
                BlueCrow
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="#catalogo">Catálogo</a>
                    </li>
                </ul>

                <form class="d-flex" action="{{ route('login.index') }}" method="get">
                    <button class="btn btn-primary" type="submit">Iniciar sesión</button>
                </form>
            </div>
        </div>
    </nav>

    <header class="container py-5 text-center">
        <span class="badge rounded-pill text-bg-info-subtle text-info border border-info-subtle mb-3">Punto de venta</span>
        <h1 class="display-6 fw-bold mb-2">Catálogo de productos</h1>
        <p class="text-body-secondary mb-0">Productos registrados en la base de datos, listos para consulta de clientes.</p>
    </header>

    <main class="container pb-5" id="catalogo">
        @if ($productosCatalogo->isEmpty())
            <div class="alert alert-secondary text-center" role="alert">
                No hay productos registrados por el momento.
            </div>
        @else
            <div class="row g-4">
                @foreach ($productosCatalogo as $producto)
                    <div class="col-md-6 col-xl-4">
                        <article class="card h-100 border-0 shadow-sm overflow-hidden">
                            <img
                                src="{{ $producto->img_path ? asset($producto->img_path) : asset('assets/img/maleri.png') }}"
                                class="card-img-top"
                                alt="{{ $producto->nombre }}"
                                style="height: 220px; object-fit: cover;">

                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge text-bg-dark">{{ $producto->codigo }}</span>
                                    <span class="badge text-bg-{{ $producto->estado ? 'success' : 'secondary' }}">
                                        {{ $producto->estado ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>

                                <h2 class="h5 mb-2">{{ $producto->nombre }}</h2>
                                <p class="small text-body-secondary mb-3">{{ $producto->descripcion ?: 'Sin descripción' }}</p>

                                <ul class="list-group list-group-flush small mb-3">
                                    <li class="list-group-item bg-transparent px-0 d-flex justify-content-between">
                                        <span>Categoría</span>
                                        <strong>{{ $producto->categoria?->caracteristica?->nombre ?? 'Sin categoría' }}</strong>
                                    </li>
                                    <li class="list-group-item bg-transparent px-0 d-flex justify-content-between">
                                        <span>Precio de venta</span>
                                        <strong>${{ number_format((float) ($producto->precio ?? 0), 2) }}</strong>
                                    </li>
                                    <li class="list-group-item bg-transparent px-0 d-flex justify-content-between">
                                        <span>Stock</span>
                                        <strong>{{ $producto->inventario?->cantidad ?? 0 }}</strong>
                                    </li>
                                    <li class="list-group-item bg-transparent px-0 d-flex justify-content-between">
                                        <span>Unidad de medida</span>
                                        <strong>{{ $producto->presentacione?->caracteristica?->nombre ?? ($producto->presentacione?->sigla ?? 'No definida') }}</strong>
                                    </li>
                                    <li class="list-group-item bg-transparent px-0 d-flex justify-content-between">
                                        <span>Código de barras</span>
                                        <strong>No registrado</strong>
                                    </li>
                                </ul>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    <footer class="text-center text-white mt-auto">
        <div class="text-center p-3 bg-body-secondary">
            © {{ date('Y') }} Sistema de Ventas · Todos los derechos reservados
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
