<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Catálogo" />
    <meta name="author" content="BlueCrow" />
    <title>Maleri — Catálogo</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
</head>

<body>
    @php
        use Illuminate\Support\Str;

        $productos = collect($productosCatalogo ?? []);
        $productosCatalogoData = $productos
            ->values()
            ->map(function ($producto, $index) {
                $categoria = $producto->categoria?->caracteristica?->nombre ?? 'Sin categoría';
                $stock = (int) ($producto->inventario?->cantidad ?? 0);
                $precio = (float) ($producto->precio ?? 0);
                $unidad = $producto->presentacione?->caracteristica?->nombre ?? ($producto->presentacione?->sigla ?? 'No definida');

                return [
                    'id' => $producto->id ?? ($index + 1),
                    'index' => $index,
                    'nombre' => $producto->nombre ?? 'Producto sin nombre',
                    'codigo' => $producto->codigo ?? 'SIN-CODIGO',
                    'categoria' => $categoria,
                    'categoria_slug' => Str::slug($categoria),
                    'precio' => $precio,
                    'stock' => $stock,
                    'estado' => (int) ($producto->estado ?? 0),
                    'unidad' => $unidad,
                    'descripcion' => $producto->descripcion ?: 'Sin descripción disponible.',
                    'img' => $producto->img_path ? asset($producto->img_path) : asset('assets/img/maleri.png'),
                ];
            });

        $categorias = $productosCatalogoData->pluck('categoria')->filter()->unique()->sort()->values();
        $destacados = $productosCatalogoData->where('stock', '>', 0)->take(3)->values();
    @endphp

    <nav class="topbar">
        <a class="topbar-brand" href="{{ route('panel') }}">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <rect width="24" height="24" rx="5" fill="#0a0a0a" />
                <path d="M7 8h10M7 12h7M7 16h10" stroke="#fff" stroke-width="1.5" stroke-linecap="round" />
            </svg>
            Maleri
        </a>
        <a href="{{ route('login.index') }}" class="btn-login">Iniciar sesión</a>
    </nav>

    <header class="hero">
        <p class="hero-eyebrow">Fine &amp; Fashion Jewlery — Catálogo para clientes</p>
        <h1 class="hero-title">Maleri<br><em>Joyería</em></h1>
        <p class="hero-sub">Explora nuestra colección completa. Filtra por categoría, precio o disponibilidad para encontrar la pieza perfecta.</p>
        <div class="hero-meta">
            <div class="hero-stat">
                <span class="hero-stat-n" id="heroTotal">{{ $productosCatalogoData->count() }}</span>
                <span class="hero-stat-l">Productos</span>
            </div>
            <div class="hero-stat">
                <span class="hero-stat-n" id="heroCats">{{ $categorias->count() }}</span>
                <span class="hero-stat-l">Categorías</span>
            </div>
            <div class="hero-stat">
                <span class="hero-stat-n" id="heroInStock">{{ $productosCatalogoData->where('stock', '>', 0)->count() }}</span>
                <span class="hero-stat-l">Disponibles</span>
            </div>
        </div>
    </header>

    @if ($destacados->isNotEmpty())
        <section class="section-wrap" id="featuredSection">
            <p class="section-label">Destacados</p>
            <div class="featured-row" id="featuredRow">
                @foreach ($destacados as $item)
                    <article class="featured-item">
                        <img class="featured-thumb" src="{{ $item['img'] }}" alt="{{ $item['nombre'] }}" loading="lazy">
                        <div>
                            <p class="featured-cat">{{ $item['categoria'] }}</p>
                            <p class="featured-name">{{ $item['nombre'] }}</p>
                            <p class="featured-price">${{ number_format($item['precio'], 2) }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <div class="toolbar">
        <div class="toolbar-top">
            <div class="search-wrap">
                <svg class="search-icon" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                    <circle cx="6" cy="6" r="4.5" stroke="#8a8a8a" stroke-width="1.3" />
                    <path d="M9.5 9.5L12 12" stroke="#8a8a8a" stroke-width="1.3" stroke-linecap="round" />
                </svg>
                <input id="buscarProducto" class="form-input" type="search" placeholder="Buscar por nombre o código…" autocomplete="off">
            </div>
            <select id="ordenProductos" class="form-select">
                <option value="recientes">Recientes</option>
                <option value="precio_asc">Precio: menor a mayor</option>
                <option value="precio_desc">Precio: mayor a menor</option>
                <option value="popularidad">Mayor stock</option>
            </select>
            <button id="limpiarFiltros" class="btn-clear" type="button">Limpiar</button>
        </div>
        <div class="chips" id="chipsCategorias">
            <button class="chip active" data-category="all" type="button">Todas</button>
            @foreach ($categorias as $categoria)
                <button class="chip" data-category="{{ Str::slug($categoria) }}" type="button">{{ $categoria }}</button>
            @endforeach
        </div>
    </div>

    <div class="catalog-head">
        <span class="catalog-title">Catálogo</span>
        <span class="catalog-count" id="catalogCounter">0 productos</span>
    </div>

    <div class="products-grid" id="productsGrid">
        @if ($productosCatalogoData->isEmpty())
            <div class="empty-state">
                <p>No hay productos registrados por el momento.</p>
            </div>
        @endif
    </div>

    <div class="pagination-wrap">
        <ul id="catalogPagination" style="list-style:none;display:flex;gap:.35rem;padding:0;margin:0;"></ul>
    </div>

    <div class="modal-overlay" id="quickViewModal">
        <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modalNombre">
            <div class="modal-img-side">
                <img id="modalImagen" src="" alt="" class="modal-img">
                <button class="modal-close" id="modalClose" aria-label="Cerrar" type="button">✕</button>
            </div>
            <div class="modal-info-side">
                <p class="modal-eyebrow" id="modalCategoria"></p>
                <h2 class="modal-title" id="modalNombre"></h2>
                <p class="modal-price" id="modalPrecio"></p>
                <p class="modal-desc" id="modalDescripcion"></p>
                <div class="modal-meta">
                    <div class="modal-meta-item">
                        <p class="modal-meta-label">Código</p>
                        <p class="modal-meta-val" id="modalCodigo"></p>
                    </div>
                    <div class="modal-meta-item">
                        <p class="modal-meta-label">Stock</p>
                        <p class="modal-meta-val" id="modalStock"></p>
                    </div>
                    <div class="modal-meta-item">
                        <p class="modal-meta-label">Estado</p>
                        <p class="modal-meta-val" id="modalEstado"></p>
                    </div>
                    <div class="modal-meta-item">
                        <p class="modal-meta-label">Unidad</p>
                        <p class="modal-meta-val" id="modalUnidad"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>&copy; BlueCrow <span id="yr">{{ date('Y') }}</span></footer>

    <script>
        document.getElementById('yr').textContent = new Date().getFullYear();

        const PRODUCTS = @json($productosCatalogoData);

        const state = {
            query: '',
            category: 'all',
            sort: 'recientes',
            page: 1,
            perPage: 12,
        };

        const slugify = value => (value || '')
            .toString()
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)/g, '');

        const truncate = (text, length) => text && text.length > length ? `${text.slice(0, length)}…` : (text || '');
        const categories = [...new Set(PRODUCTS.map(product => product.categoria).filter(Boolean))].sort((a, b) => a.localeCompare(b));

        const getFiltered = () => {
            const filtered = PRODUCTS.filter(product => {
                const haystack = `${product.nombre} ${product.codigo}`.toLowerCase();
                const matchSearch = haystack.includes(state.query);
                const matchCategory = state.category === 'all' || slugify(product.categoria) === state.category;
                return matchSearch && matchCategory;
            });

            if (state.sort === 'precio_asc') filtered.sort((a, b) => a.precio - b.precio);
            if (state.sort === 'precio_desc') filtered.sort((a, b) => b.precio - a.precio);
            if (state.sort === 'popularidad') filtered.sort((a, b) => b.stock - a.stock);
            if (state.sort === 'recientes') filtered.sort((a, b) => b.index - a.index);

            return filtered;
        };

        const modal = document.getElementById('quickViewModal');

        const bindQuickView = () => {
            document.querySelectorAll('.quick-view-btn').forEach(button => {
                button.addEventListener('click', event => {
                    event.stopPropagation();
                    const data = PRODUCTS.find(product => String(product.id) === button.dataset.productId);
                    document.getElementById('modalImagen').src = data.img;
                    document.getElementById('modalImagen').alt = data.nombre;
                    document.getElementById('modalNombre').textContent = data.nombre;
                    document.getElementById('modalCategoria').textContent = data.categoria;
                    document.getElementById('modalPrecio').textContent = `$${Number(data.precio).toFixed(2)}`;
                    document.getElementById('modalDescripcion').textContent = data.descripcion;
                    document.getElementById('modalCodigo').textContent = data.codigo;
                    document.getElementById('modalStock').textContent = data.stock;
                    document.getElementById('modalEstado').textContent = data.estado ? 'Activo' : 'Inactivo';
                    document.getElementById('modalUnidad').textContent = data.unidad;
                    modal.classList.add('open');
                    document.body.style.overflow = 'hidden';
                });
            });
        };

        const renderPagination = (totalPages, totalItems) => {
            const pagination = document.getElementById('catalogPagination');
            pagination.innerHTML = '';

            if (totalItems <= state.perPage) return;

            for (let i = 1; i <= totalPages; i += 1) {
                const item = document.createElement('li');
                const button = document.createElement('button');
                button.className = `page-btn${i === state.page ? ' active' : ''}`;
                button.type = 'button';
                button.textContent = i;
                button.addEventListener('click', () => {
                    state.page = i;
                    render();
                    window.scrollTo({ top: document.getElementById('productsGrid').offsetTop - 120, behavior: 'smooth' });
                });
                item.appendChild(button);
                pagination.appendChild(item);
            }
        };

        const render = () => {
            const filtered = getFiltered();
            const totalPages = Math.max(1, Math.ceil(filtered.length / state.perPage));
            if (state.page > totalPages) state.page = totalPages;

            const start = (state.page - 1) * state.perPage;
            const items = filtered.slice(start, start + state.perPage);
            document.getElementById('catalogCounter').textContent = `${filtered.length} producto${filtered.length === 1 ? '' : 's'}`;

            const grid = document.getElementById('productsGrid');
            grid.innerHTML = '';

            if (!items.length) {
                grid.innerHTML = '<div class="empty-state"><p>No se encontraron productos.</p></div>';
            } else {
                items.forEach((product, index) => {
                    const card = document.createElement('article');
                    const inStock = product.stock > 0;
                    card.className = 'product-card';
                    card.style.animationDelay = `${index * 40}ms`;
                    card.innerHTML = `
                        <div class="card-img-wrap">
                            <img class="card-img" src="${product.img}" alt="${product.nombre}" loading="lazy">
                            <span class="card-badge ${inStock ? 'badge-in' : 'badge-out'}">${inStock ? 'Disponible' : 'Sin stock'}</span>
                        </div>
                        <div class="card-body">
                            <p class="card-cat">${product.categoria}</p>
                            <h3 class="card-name">${product.nombre}</h3>
                            <p class="card-price">$${Number(product.precio).toFixed(2)}</p>
                            <p class="card-desc">${truncate(product.descripcion, 90)}</p>
                            <div class="card-footer">
                                <span class="card-stock">Stock: ${product.stock}</span>
                                <span class="card-unit">${product.unidad}</span>
                            </div>
                            <button class="card-view-btn quick-view-btn" type="button" data-product-id="${product.id}">Ver detalles</button>
                        </div>`;
                    grid.appendChild(card);
                });
            }

            renderPagination(totalPages, filtered.length);
            bindQuickView();
        };

        const closeModal = () => {
            modal.classList.remove('open');
            document.body.style.overflow = '';
        };

        document.getElementById('buscarProducto').addEventListener('input', event => {
            state.query = event.target.value.toLowerCase();
            state.page = 1;
            render();
        });

        document.getElementById('ordenProductos').addEventListener('change', event => {
            state.sort = event.target.value;
            state.page = 1;
            render();
        });

        document.getElementById('chipsCategorias').addEventListener('click', event => {
            const button = event.target.closest('.chip');
            if (!button) return;

            state.category = button.dataset.category;
            state.page = 1;
            document.querySelectorAll('#chipsCategorias .chip').forEach(chip => chip.classList.remove('active'));
            button.classList.add('active');
            render();
        });

        document.getElementById('limpiarFiltros').addEventListener('click', () => {
            document.getElementById('buscarProducto').value = '';
            document.getElementById('ordenProductos').value = 'recientes';
            state.query = '';
            state.sort = 'recientes';
            state.category = 'all';
            state.page = 1;
            document.querySelectorAll('#chipsCategorias .chip').forEach(chip => chip.classList.remove('active'));
            document.querySelector('#chipsCategorias .chip[data-category="all"]').classList.add('active');
            render();
        });

        document.getElementById('modalClose').addEventListener('click', closeModal);
        modal.addEventListener('click', event => {
            if (event.target === modal) closeModal();
        });
        document.addEventListener('keydown', event => {
            if (event.key === 'Escape') closeModal();
        });

        render();
    </script>
</body>

</html>
