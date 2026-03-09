<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Catálogo POS moderno para clientes con búsqueda, filtros y vista rápida." />
    <meta name="author" content="BlueCrow" />
    <title>BlueCrow | Catálogo POS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --pos-bg: #0f172a;
            --pos-card: #111827;
            --pos-muted: #94a3b8;
            --pos-primary: #22d3ee;
            --pos-accent: #6366f1;
        }

        body {
            background: radial-gradient(circle at top, #1e293b 0%, #0b1120 55%);
            color: #e2e8f0;
            min-height: 100vh;
        }

        .hero {
            background: linear-gradient(135deg, rgba(34, 211, 238, .12), rgba(99, 102, 241, .16));
            border: 1px solid rgba(148, 163, 184, .18);
            border-radius: 1rem;
        }

        .catalog-toolbar,
        .product-card,
        .featured-card {
            background: rgba(17, 24, 39, .88);
            border: 1px solid rgba(148, 163, 184, .16);
            backdrop-filter: blur(6px);
        }

        .catalog-toolbar {
            border-radius: 1rem;
        }

        .product-card {
            border-radius: 1rem;
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
            overflow: hidden;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(15, 23, 42, .35);
            border-color: rgba(34, 211, 238, .45);
        }

        .product-image {
            width: 100%;
            height: 210px;
            object-fit: cover;
            background: #0b1222;
        }

        .category-chip {
            border: 1px solid rgba(148, 163, 184, .25);
            color: #cbd5e1;
        }

        .category-chip.active,
        .category-chip:hover {
            border-color: rgba(34, 211, 238, .55);
            background: rgba(34, 211, 238, .12);
            color: #67e8f9;
        }

        .featured-card {
            border-radius: .85rem;
            padding: .75rem;
        }

        .out-stock {
            border: 1px solid rgba(248, 113, 113, .35) !important;
        }

        .out-stock-badge {
            background: rgba(248, 113, 113, .16);
            color: #fca5a5;
            border: 1px solid rgba(248, 113, 113, .35);
        }

        .in-stock-badge {
            background: rgba(74, 222, 128, .15);
            color: #86efac;
            border: 1px solid rgba(74, 222, 128, .35);
        }

        .muted {
            color: var(--pos-muted);
        }
    </style>
</head>

<body>
    @php
        $productos = $productosCatalogo ?? collect();
        $categorias = $productos
            ->map(fn($p) => $p->categoria?->caracteristica?->nombre)
            ->filter()
            ->unique()
            ->sort()
            ->values();
        $destacados = $productos->where('estado', 1)->take(3);
    @endphp

    <nav class="navbar navbar-expand-md sticky-top" style="background-color: rgba(15,23,42,.88); border-bottom: 1px solid rgba(148,163,184,.16);">
        <div class="container-fluid px-4">
            <a class="navbar-brand text-light" href="{{ route('panel') }}">
                <img src="{{ asset('assets/img/icon.png') }}" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
                BlueCrow POS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <div class="ms-auto">
                    <form action="{{ route('login.index') }}" method="get">
                        <button class="btn btn-info text-dark fw-semibold px-4" type="submit">Iniciar sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="container py-4 py-lg-5">
        <section class="hero p-4 p-lg-5 mb-4 mb-lg-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <span class="badge rounded-pill text-bg-info-subtle text-info border border-info-subtle mb-2">Catálogo para clientes</span>
                    <h1 class="display-6 fw-bold mb-2">Encuentra y vende más rápido</h1>
                    <p class="muted mb-0">Interfaz estilo e-commerce adaptada a POS: búsqueda instantánea, filtros por categoría, ordenamiento, vista rápida y paginación para catálogos extensos.</p>
                </div>
                <div class="col-lg-4">
                    <div class="featured-card">
                        <p class="small text-uppercase text-info mb-2">Productos registrados</p>
                        <h2 class="mb-1">{{ $productos->count() }}</h2>
                        <p class="muted small mb-0">Catálogo actualizado desde base de datos</p>
                    </div>
                </div>
            </div>
        </section>

        @if ($productos->isNotEmpty())
            <section class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h2 class="h4 mb-0">Productos destacados</h2>
                    <span class="muted small">Selección automática de productos activos</span>
                </div>
                <div class="row g-3">
                    @foreach ($destacados as $item)
                        <div class="col-md-4">
                            <div class="featured-card h-100">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $item->img_path ? asset($item->img_path) : asset('assets/img/maleri.png') }}" alt="{{ $item->nombre }}" width="72" height="72" class="rounded" style="object-fit:cover;">
                                    <div>
                                        <p class="mb-1 fw-semibold">{{ $item->nombre }}</p>
                                        <p class="small muted mb-1">{{ $item->categoria?->caracteristica?->nombre ?? 'Sin categoría' }}</p>
                                        <p class="mb-0 text-info fw-semibold">${{ number_format((float) ($item->precio ?? 0), 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="catalog-toolbar p-3 p-lg-4 mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-lg-5">
                    <label for="buscarProducto" class="form-label mb-1">Buscar producto</label>
                    <input id="buscarProducto" type="search" class="form-control form-control-lg" placeholder="Ej. café, leche, código..." autocomplete="off">
                </div>
                <div class="col-lg-4">
                    <label for="ordenProductos" class="form-label mb-1">Ordenar por</label>
                    <select id="ordenProductos" class="form-select form-select-lg">
                        <option value="recientes">Recientes</option>
                        <option value="precio_asc">Precio: menor a mayor</option>
                        <option value="precio_desc">Precio: mayor a menor</option>
                        <option value="popularidad">Popularidad</option>
                    </select>
                </div>
                <div class="col-lg-3 text-lg-end">
                    <button id="limpiarFiltros" class="btn btn-outline-light btn-lg w-100">Limpiar filtros</button>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-3" id="chipsCategorias">
                <button class="btn btn-sm category-chip active" data-category="all">Todas</button>
                @foreach ($categorias as $categoria)
                    <button class="btn btn-sm category-chip" data-category="{{ Str::slug($categoria) }}">{{ $categoria }}</button>
                @endforeach
            </div>
        </section>

        <section>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h4 mb-0">Catálogo de productos</h2>
                <span class="muted small" id="catalogCounter">0 productos</span>
            </div>

            @if ($productos->isEmpty())
                <div class="alert alert-secondary text-center" role="alert">No hay productos registrados por el momento.</div>
            @else
                <div class="row g-4" id="productsGrid">
                    @foreach ($productos as $index => $producto)
                        @php
                            $stock = (int) ($producto->inventario?->cantidad ?? 0);
                            $categoria = $producto->categoria?->caracteristica?->nombre ?? 'Sin categoría';
                            $categoriaSlug = Str::slug($categoria);
                            $precio = (float) ($producto->precio ?? 0);
                        @endphp
                        <div class="col-sm-6 col-lg-4 col-xxl-3 product-item"
                            data-name="{{ Str::lower($producto->nombre) }}"
                            data-code="{{ Str::lower($producto->codigo) }}"
                            data-category="{{ $categoriaSlug }}"
                            data-price="{{ $precio }}"
                            data-stock="{{ $stock }}"
                            data-index="{{ $index }}">
                            <article class="product-card {{ $stock <= 0 ? 'out-stock' : '' }}">
                                <img loading="lazy" src="{{ $producto->img_path ? asset($producto->img_path) : asset('assets/img/maleri.png') }}" class="product-image" alt="{{ $producto->nombre }}">
                                <div class="p-3 d-flex flex-column h-100">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge text-bg-dark">{{ $producto->codigo }}</span>
                                        <span class="badge {{ $stock > 0 ? 'in-stock-badge' : 'out-stock-badge' }}">{{ $stock > 0 ? 'Disponible' : 'Sin stock' }}</span>
                                    </div>
                                    <p class="small muted mb-1">{{ $categoria }}</p>
                                    <h3 class="h6 fw-semibold">{{ $producto->nombre }}</h3>
                                    <p class="text-info fw-bold fs-5 mb-2">${{ number_format($precio, 2) }}</p>
                                    <p class="small muted mb-3">{{ Str::limit($producto->descripcion ?: 'Sin descripción disponible', 85) }}</p>

                                    <div class="d-flex justify-content-between small muted mb-3">
                                        <span>Stock: {{ $stock }}</span>
                                        <span>{{ $producto->presentacione?->caracteristica?->nombre ?? ($producto->presentacione?->sigla ?? 'No definida') }}</span>
                                    </div>

                                    <button class="btn btn-info text-dark fw-semibold mt-auto w-100 quick-view-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#quickViewModal"
                                        data-nombre="{{ $producto->nombre }}"
                                        data-codigo="{{ $producto->codigo }}"
                                        data-categoria="{{ $categoria }}"
                                        data-precio="{{ number_format($precio, 2) }}"
                                        data-stock="{{ $stock }}"
                                        data-estado="{{ $producto->estado ? 'Activo' : 'Inactivo' }}"
                                        data-unidad="{{ $producto->presentacione?->caracteristica?->nombre ?? ($producto->presentacione?->sigla ?? 'No definida') }}"
                                        data-descripcion="{{ $producto->descripcion ?: 'Sin descripción' }}"
                                        data-imagen="{{ $producto->img_path ? asset($producto->img_path) : asset('assets/img/maleri.png') }}">
                                        Ver detalles
                                    </button>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    <nav aria-label="Paginación catálogo">
                        <ul class="pagination mb-0" id="catalogPagination"></ul>
                    </nav>
                </div>
            @endif
        </section>
    </main>

    <div class="modal fade" id="quickViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark border border-secondary-subtle">
                <div class="modal-header border-secondary-subtle">
                    <h5 class="modal-title">Vista rápida de producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-5">
                            <img id="modalImagen" src="" alt="Producto" class="img-fluid rounded">
                        </div>
                        <div class="col-md-7">
                            <p class="small muted mb-1" id="modalCategoria"></p>
                            <h3 class="h4" id="modalNombre"></h3>
                            <p class="text-info fw-bold fs-4" id="modalPrecio"></p>
                            <p class="mb-3" id="modalDescripcion"></p>
                            <div class="row g-2 small">
                                <div class="col-6"><span class="muted">Código:</span> <strong id="modalCodigo"></strong></div>
                                <div class="col-6"><span class="muted">Stock:</span> <strong id="modalStock"></strong></div>
                                <div class="col-6"><span class="muted">Estado:</span> <strong id="modalEstado"></strong></div>
                                <div class="col-6"><span class="muted">Unidad:</span> <strong id="modalUnidad"></strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center text-white mt-4">
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.25);">© {{ date('Y') }} Sistema de Ventas · Todos los derechos reservados</div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('buscarProducto');
            const sortSelect = document.getElementById('ordenProductos');
            const clearBtn = document.getElementById('limpiarFiltros');
            const categoryContainer = document.getElementById('chipsCategorias');
            const counter = document.getElementById('catalogCounter');
            const pagination = document.getElementById('catalogPagination');
            const productItems = Array.from(document.querySelectorAll('.product-item'));

            let activeCategory = 'all';
            let currentPage = 1;
            const perPage = 12;

            const normalize = value => (value || '').toString().toLowerCase();

            const sortItems = (items) => {
                const mode = sortSelect?.value || 'recientes';
                const sorted = [...items];

                if (mode === 'precio_asc') sorted.sort((a, b) => Number(a.dataset.price) - Number(b.dataset.price));
                if (mode === 'precio_desc') sorted.sort((a, b) => Number(b.dataset.price) - Number(a.dataset.price));
                if (mode === 'popularidad') sorted.sort((a, b) => Number(b.dataset.stock) - Number(a.dataset.stock));
                if (mode === 'recientes') sorted.sort((a, b) => Number(b.dataset.index) - Number(a.dataset.index));

                return sorted;
            };

            const applyFilters = () => {
                const query = normalize(searchInput?.value);

                const filtered = productItems.filter(item => {
                    const matchSearch = normalize(item.dataset.name).includes(query) || normalize(item.dataset.code).includes(query);
                    const matchCategory = activeCategory === 'all' || item.dataset.category === activeCategory;
                    return matchSearch && matchCategory;
                });

                const sorted = sortItems(filtered);
                const totalPages = Math.max(1, Math.ceil(sorted.length / perPage));
                if (currentPage > totalPages) currentPage = totalPages;

                productItems.forEach(item => item.classList.add('d-none'));

                const start = (currentPage - 1) * perPage;
                const pageItems = sorted.slice(start, start + perPage);
                pageItems.forEach(item => item.classList.remove('d-none'));

                if (counter) counter.textContent = `${filtered.length} producto(s)`;
                renderPagination(totalPages, filtered.length);
            };

            const renderPagination = (totalPages, totalItems) => {
                if (!pagination) return;
                pagination.innerHTML = '';

                if (totalItems <= perPage) return;

                for (let i = 1; i <= totalPages; i++) {
                    const li = document.createElement('li');
                    li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                    const btn = document.createElement('button');
                    btn.className = 'page-link';
                    btn.textContent = i;
                    btn.addEventListener('click', () => {
                        currentPage = i;
                        applyFilters();
                        window.scrollTo({ top: document.getElementById('productsGrid').offsetTop - 110, behavior: 'smooth' });
                    });
                    li.appendChild(btn);
                    pagination.appendChild(li);
                }
            };

            categoryContainer?.addEventListener('click', e => {
                const btn = e.target.closest('button[data-category]');
                if (!btn) return;
                activeCategory = btn.dataset.category;
                currentPage = 1;
                categoryContainer.querySelectorAll('button').forEach(chip => chip.classList.remove('active'));
                btn.classList.add('active');
                applyFilters();
            });

            searchInput?.addEventListener('input', () => {
                currentPage = 1;
                applyFilters();
            });

            sortSelect?.addEventListener('change', () => {
                currentPage = 1;
                applyFilters();
            });

            clearBtn?.addEventListener('click', () => {
                if (searchInput) searchInput.value = '';
                if (sortSelect) sortSelect.value = 'recientes';
                activeCategory = 'all';
                currentPage = 1;
                categoryContainer?.querySelectorAll('button').forEach(chip => chip.classList.remove('active'));
                categoryContainer?.querySelector('button[data-category="all"]')?.classList.add('active');
                applyFilters();
            });

            document.querySelectorAll('.quick-view-btn').forEach(button => {
                button.addEventListener('click', () => {
                    document.getElementById('modalImagen').src = button.dataset.imagen;
                    document.getElementById('modalNombre').textContent = button.dataset.nombre;
                    document.getElementById('modalCategoria').textContent = button.dataset.categoria;
                    document.getElementById('modalPrecio').textContent = `$${button.dataset.precio}`;
                    document.getElementById('modalDescripcion').textContent = button.dataset.descripcion;
                    document.getElementById('modalCodigo').textContent = button.dataset.codigo;
                    document.getElementById('modalStock').textContent = button.dataset.stock;
                    document.getElementById('modalEstado').textContent = button.dataset.estado;
                    document.getElementById('modalUnidad').textContent = button.dataset.unidad;
                });
            });

            applyFilters();
        });
    </script>
</body>

</html>
