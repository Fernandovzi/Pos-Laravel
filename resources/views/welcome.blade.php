<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <meta name="description"
        content="Sistema de ventas web para la gestión de compras, ventas, clientes, proveedores, productos e inventarios.
        Diseñado para pequeños y medianos negocios que buscan optimizar sus procesos y tomar mejores decisiones." />

    <meta name="author" content="BlueCrow" />
    <title>BlueCrow</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-md bg-body-secondary">
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
                        <a class="nav-link" href="#">Acerca del sistema</a>
                    </li>
                </ul>

                <form class="d-flex" action="{{ route('login.index') }}" method="get">
                    <button class="btn btn-primary" type="submit">Iniciar sesión</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Carrusel -->
    <div id="carouselExample" class="carousel slide carousel-fade">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('assets/img/img_carrusel_1.png') }}" class="d-block w-100"
                    alt="Gestión de ventas">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('assets/img/img_carrusel_2.png') }}" class="d-block w-100"
                    alt="Control de inventarios">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('assets/img/img_carrusel_3.png') }}" class="d-block w-100"
                    alt="Control total de tu negocio">
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample"
            data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <!-- Ventajas -->
    <div class="container-md">
        <div class="row my-4 g-5">
            <div class="col-lg-6">
                <div class="card border-0">
                    <div class="card-header text-center text-info fs-5 fw-semibold">
                        Con un sistema de ventas
                    </div>
                    <div class="card-body">
                        <ul class="text-light">
                            <li>Acceso al sistema 24/7 desde cualquier dispositivo.</li>
                            <li>Automatización de inventarios, ventas y reportes.</li>
                            <li>Información clara para tomar mejores decisiones.</li>
                            <li>Escalabilidad conforme crece tu negocio.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0">
                    <div class="card-header text-center text-info fs-5 fw-semibold">
                        Sin un sistema de ventas
                    </div>
                    <div class="card-body">
                        <ul class="text-light">
                            <li>Dependencia de horarios y procesos manuales.</li>
                            <li>Mayor riesgo de errores y pérdida de información.</li>
                            <li>Dificultad para analizar el desempeño del negocio.</li>
                            <li>Crecimiento limitado y menos control.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Llamado a la acción -->
    <section class="container-fluid bg-body-secondary text-center">
        <div class="container p-5">
            <h2 class="text-light mb-4">
                Dale un nuevo enfoque a tu negocio
                <span class="text-info">y usa la tecnología a tu favor</span>
            </h2>
            <a href="{{ route('login.index') }}" class="btn btn-primary">Comenzar ahora</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center text-white">
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            © {{ date('Y') }} Sistema de Ventas · Todos los derechos reservados
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
