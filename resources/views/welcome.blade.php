<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <meta name="description"
        content="Catálogo visual de productos para punto de venta con información clave: precio, stock, unidad, código de barras y estatus." />

    <meta name="author" content="BlueCrow" />
    <title>BlueCrow | Catálogo POS</title>

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

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('panel') }}">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#catalogo-visual">Catálogo visual</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#catalogo-tabla">Vista tabular POS</a>
                    </li>
                </ul>

                <form class="d-flex" action="{{ route('login.index') }}" method="get">
                    <button class="btn btn-primary" type="submit">Iniciar sesión</button>
                </form>
            </div>
        </div>
    </nav>

    <header class="container py-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="badge rounded-pill text-bg-info-subtle text-info border border-info-subtle mb-3">Catálogo para clientes</span>
                <h1 class="display-6 fw-bold mb-3">Productos listos para venta en tu punto de atención</h1>
                <p class="text-body-secondary mb-4">Una presentación más visual para clientes, con tarjetas destacadas de producto y una
                    vista estructurada para operación POS.</p>
                <a href="#catalogo-visual" class="btn btn-info text-dark fw-semibold">Ver productos destacados</a>
            </div>
            <div class="col-lg-5">
                <div class="card border-0 overflow-hidden shadow-sm">
                    <img src="{{ asset('assets/img/paisaje.png') }}" class="img-fluid" alt="Catálogo visual para clientes">
                </div>
            </div>
        </div>
    </header>

    <main class="container pb-5">
        <section id="catalogo-visual" class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h2 class="h4 mb-0">Catálogo visual de productos</h2>
                <span class="text-body-secondary small">Imágenes de la ruta local y respaldo con logo Maleri</span>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-xl-4">
                    <article class="card h-100 border-0 shadow-sm">
                        <img src="{{ asset('assets/img/plano.png') }}" class="card-img-top" alt="Café molido premium" style="height: 210px; object-fit: cover;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge text-bg-dark">PRD-001</span>
                                <span class="badge text-bg-success">Activo</span>
                            </div>
                            <h3 class="h6">Café molido premium</h3>
                            <p class="small text-body-secondary mb-2">Categoría: Abarrotes · Unidad: pieza</p>
                            <p class="small mb-3">Bolsa sellada de 500 g, tueste medio.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-info">$145.00</strong>
                                <span class="small">Stock: 62</span>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="col-md-6 col-xl-4">
                    <article class="card h-100 border-0 shadow-sm">
                        <img src="{{ asset('assets/img/paisaje.png') }}" class="card-img-top" alt="Leche entera" style="height: 210px; object-fit: cover;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge text-bg-dark">PRD-002</span>
                                <span class="badge text-bg-success">Activo</span>
                            </div>
                            <h3 class="h6">Leche entera</h3>
                            <p class="small text-body-secondary mb-2">Categoría: Lácteos · Unidad: litro</p>
                            <p class="small mb-3">Presentación tetrapack de 1 litro.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-info">$28.50</strong>
                                <span class="small">Stock: 120</span>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="col-md-6 col-xl-4">
                    <article class="card h-100 border-0 shadow-sm">
                        <img src="{{ asset('assets/img/maleri-ticket.png') }}" class="card-img-top" alt="Detergente líquido" style="height: 210px; object-fit: cover;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge text-bg-dark">PRD-004</span>
                                <span class="badge text-bg-success">Activo</span>
                            </div>
                            <h3 class="h6">Detergente líquido</h3>
                            <p class="small text-body-secondary mb-2">Categoría: Limpieza · Unidad: litro</p>
                            <p class="small mb-3">Botella de 900 ml, aroma floral.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-info">$58.90</strong>
                                <span class="small">Stock: 48</span>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="col-md-6 col-xl-4">
                    <article class="card h-100 border-0 shadow-sm">
                        <img src="{{ asset('assets/img/maleri.png') }}" class="card-img-top" alt="Pan integral" style="height: 210px; object-fit: cover;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge text-bg-dark">PRD-005</span>
                                <span class="badge text-bg-secondary">Inactivo</span>
                            </div>
                            <h3 class="h6">Pan integral</h3>
                            <p class="small text-body-secondary mb-2">Categoría: Panadería · Unidad: pieza</p>
                            <p class="small mb-3">Paquete de 680 g con 18 rebanadas.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-info">$46.00</strong>
                                <span class="small">Stock: 0</span>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="col-md-6 col-xl-4">
                    <article class="card h-100 border-0 shadow-sm">
                        <img src="{{ asset('assets/img/maleri.png') }}" class="card-img-top" alt="Agua purificada" style="height: 210px; object-fit: cover;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge text-bg-dark">PRD-006</span>
                                <span class="badge text-bg-success">Activo</span>
                            </div>
                            <h3 class="h6">Agua purificada</h3>
                            <p class="small text-body-secondary mb-2">Categoría: Bebidas · Unidad: pieza</p>
                            <p class="small mb-3">Garrafa retornable de 20 litros.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-info">$42.00</strong>
                                <span class="small">Stock: 17</span>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="col-md-6 col-xl-4">
                    <article class="card h-100 border-0 shadow-sm">
                        <img src="{{ asset('assets/img/maleri.png') }}" class="card-img-top" alt="Chocolate en polvo" style="height: 210px; object-fit: cover;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge text-bg-dark">PRD-010</span>
                                <span class="badge text-bg-secondary">Inactivo</span>
                            </div>
                            <h3 class="h6">Chocolate en polvo</h3>
                            <p class="small text-body-secondary mb-2">Categoría: Bebidas · Unidad: pieza</p>
                            <p class="small mb-3">Tarro de 400 g para preparar bebidas calientes.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-info">$73.00</strong>
                                <span class="small">Stock: 0</span>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section id="catalogo-tabla">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-body-secondary d-flex flex-wrap gap-2 justify-content-between align-items-center py-3">
                    <h2 class="h5 mb-0">Vista tabular estructurada (POS)</h2>
                    <span class="text-body-secondary small">Incluye todos los campos requeridos para operación</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">ID / Código</th>
                                <th scope="col">Nombre del producto</th>
                                <th scope="col">Categoría</th>
                                <th scope="col">Descripción</th>
                                <th scope="col" class="text-end">Precio de venta</th>
                                <th scope="col" class="text-center">Stock</th>
                                <th scope="col">Unidad de medida</th>
                                <th scope="col">Código de barras</th>
                                <th scope="col" class="text-center">Estatus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>PRD-001</td>
                                <td>Café molido premium</td>
                                <td>Abarrotes</td>
                                <td>Bolsa sellada de 500 g, tueste medio.</td>
                                <td class="text-end">$145.00</td>
                                <td class="text-center">62</td>
                                <td>pieza</td>
                                <td>7501001001001</td>
                                <td class="text-center"><span class="badge text-bg-success">Activo</span></td>
                            </tr>
                            <tr>
                                <td>PRD-002</td>
                                <td>Leche entera</td>
                                <td>Lácteos</td>
                                <td>Presentación tetrapack de 1 litro.</td>
                                <td class="text-end">$28.50</td>
                                <td class="text-center">120</td>
                                <td>litro</td>
                                <td>7501001001002</td>
                                <td class="text-center"><span class="badge text-bg-success">Activo</span></td>
                            </tr>
                            <tr>
                                <td>PRD-003</td>
                                <td>Manzana roja</td>
                                <td>Frutas y verduras</td>
                                <td>Producto fresco de temporada.</td>
                                <td class="text-end">$42.00</td>
                                <td class="text-center">35</td>
                                <td>kg</td>
                                <td>-</td>
                                <td class="text-center"><span class="badge text-bg-success">Activo</span></td>
                            </tr>
                            <tr>
                                <td>PRD-004</td>
                                <td>Detergente líquido</td>
                                <td>Limpieza</td>
                                <td>Botella de 900 ml, aroma floral.</td>
                                <td class="text-end">$58.90</td>
                                <td class="text-center">48</td>
                                <td>litro</td>
                                <td>7501001001004</td>
                                <td class="text-center"><span class="badge text-bg-success">Activo</span></td>
                            </tr>
                            <tr>
                                <td>PRD-005</td>
                                <td>Pan integral</td>
                                <td>Panadería</td>
                                <td>Paquete de 680 g con 18 rebanadas.</td>
                                <td class="text-end">$46.00</td>
                                <td class="text-center">0</td>
                                <td>pieza</td>
                                <td>7501001001005</td>
                                <td class="text-center"><span class="badge text-bg-secondary">Inactivo</span></td>
                            </tr>
                            <tr>
                                <td>PRD-006</td>
                                <td>Agua purificada</td>
                                <td>Bebidas</td>
                                <td>Garrafa retornable de 20 litros.</td>
                                <td class="text-end">$42.00</td>
                                <td class="text-center">17</td>
                                <td>pieza</td>
                                <td>7501001001006</td>
                                <td class="text-center"><span class="badge text-bg-success">Activo</span></td>
                            </tr>
                            <tr>
                                <td>PRD-007</td>
                                <td>Azúcar estándar</td>
                                <td>Abarrotes</td>
                                <td>Bolsa de 1 kg refinada.</td>
                                <td class="text-end">$32.00</td>
                                <td class="text-center">80</td>
                                <td>kg</td>
                                <td>-</td>
                                <td class="text-center"><span class="badge text-bg-success">Activo</span></td>
                            </tr>
                            <tr>
                                <td>PRD-008</td>
                                <td>Yogur griego natural</td>
                                <td>Lácteos</td>
                                <td>Envase de 900 g sin azúcar añadida.</td>
                                <td class="text-end">$89.00</td>
                                <td class="text-center">15</td>
                                <td>pieza</td>
                                <td>7501001001008</td>
                                <td class="text-center"><span class="badge text-bg-success">Activo</span></td>
                            </tr>
                            <tr>
                                <td>PRD-009</td>
                                <td>Aceite vegetal</td>
                                <td>Abarrotes</td>
                                <td>Botella de 850 ml para cocina diaria.</td>
                                <td class="text-end">$54.50</td>
                                <td class="text-center">22</td>
                                <td>litro</td>
                                <td>7501001001009</td>
                                <td class="text-center"><span class="badge text-bg-success">Activo</span></td>
                            </tr>
                            <tr>
                                <td>PRD-010</td>
                                <td>Chocolate en polvo</td>
                                <td>Bebidas</td>
                                <td>Tarro de 400 g para preparar bebidas calientes.</td>
                                <td class="text-end">$73.00</td>
                                <td class="text-center">0</td>
                                <td>pieza</td>
                                <td>7501001001010</td>
                                <td class="text-center"><span class="badge text-bg-secondary">Inactivo</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <footer class="text-center text-white mt-auto">
        <div class="text-center p-3 bg-body-secondary">
            © {{ date('Y') }} Sistema de Ventas · Todos los derechos reservados
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
