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

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --ink: #0a0a0a;
            --ink-soft: #3d3d3d;
            --ink-muted: #8a8a8a;
            --rule: #e4e4e4;
            --surface: #f8f7f5;
            --white: #ffffff;
            --accent: #0a0a0a;
            --tag-bg: #f0efec;
            --danger: #c0392b;
            --success: #1a7f4b;
            --serif: 'DM Serif Display', Georgia, serif;
            --sans: 'DM Sans', system-ui, sans-serif;
            --ease: cubic-bezier(.25, .46, .45, .94);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--sans);
            font-weight: 400;
            background: var(--white);
            color: var(--ink);
            line-height: 1.6;
            min-height: 100vh;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255, 255, 255, .92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--rule);
            padding: .9rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .topbar-brand {
            font-family: var(--serif);
            font-size: 1.35rem;
            letter-spacing: -.01em;
            color: var(--ink);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: .55rem;
        }

        .btn-login {
            font-family: var(--sans);
            font-weight: 500;
            font-size: .8rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: .5rem 1.4rem;
            border: 1px solid var(--ink);
            background: transparent;
            color: var(--ink);
            border-radius: 2rem;
            cursor: pointer;
            transition: background .2s var(--ease), color .2s var(--ease);
            text-decoration: none;
        }

        .btn-login:hover {
            background: var(--ink);
            color: var(--white);
        }

        .hero {
            padding: 5rem 2rem 4rem;
            max-width: 1200px;
            margin: 0 auto;
            border-bottom: 1px solid var(--rule);
        }

        .hero-eyebrow {
            font-size: .7rem;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--ink-muted);
            margin-bottom: .8rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .hero-eyebrow::before {
            content: '';
            display: inline-block;
            width: 1.8rem;
            height: 1px;
            background: var(--ink-muted);
        }

        .hero-title {
            font-family: var(--serif);
            font-size: clamp(3rem, 7vw, 5.5rem);
            line-height: 1;
            letter-spacing: -.03em;
            color: var(--ink);
            margin-bottom: 1.2rem;
        }

        .hero-title em {
            font-style: italic;
            color: var(--ink-soft);
        }

        .hero-sub {
            font-size: .95rem;
            color: var(--ink-muted);
            max-width: 440px;
            line-height: 1.7;
        }

        .hero-meta {
            margin-top: 2.5rem;
            display: flex;
            gap: 2.5rem;
            flex-wrap: wrap;
        }

        .hero-stat {
            display: flex;
            flex-direction: column;
        }

        .hero-stat-n {
            font-family: var(--serif);
            font-size: 2.4rem;
            line-height: 1;
            letter-spacing: -.04em;
            color: var(--ink);
        }

        .hero-stat-l {
            font-size: .7rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--ink-muted);
            margin-top: .25rem;
        }

        .section-wrap {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 2rem;
        }

        .section-label {
            font-size: .68rem;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--ink-muted);
            margin-bottom: 1.6rem;
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--rule);
        }

        .featured-row {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1px;
            border: 1px solid var(--rule);
            border-radius: 12px;
            overflow: hidden;
            background: var(--rule);
        }

        .featured-item {
            background: var(--white);
            padding: 1.4rem 1.6rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: background .18s var(--ease);
        }

        .featured-item:hover {
            background: var(--surface);
        }

        .featured-thumb {
            width: 58px;
            height: 58px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
            background: var(--surface);
        }

        .featured-name {
            font-weight: 500;
            font-size: .88rem;
            color: var(--ink);
            margin-bottom: .15rem;
            line-height: 1.3;
        }

        .featured-cat {
            font-size: .72rem;
            color: var(--ink-muted);
            letter-spacing: .04em;
            margin-bottom: .3rem;
        }

        .featured-price {
            font-family: var(--serif);
            font-size: 1.05rem;
            color: var(--ink);
            letter-spacing: -.01em;
        }

        .toolbar {
            padding: 1.5rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
            border-bottom: 1px solid var(--rule);
        }

        .toolbar-top {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center;
            margin-bottom: 1.2rem;
        }

        .search-wrap {
            position: relative;
            flex: 1;
            min-width: 220px;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--ink-muted);
            pointer-events: none;
            font-size: .9rem;
        }

        .form-input {
            width: 100%;
            padding: .7rem 1rem .7rem 2.8rem;
            border: 1px solid var(--rule);
            border-radius: 8px;
            font-family: var(--sans);
            font-size: .88rem;
            color: var(--ink);
            background: var(--surface);
            outline: none;
            transition: border-color .18s;
        }

        .form-input:focus {
            border-color: var(--ink);
            background: var(--white);
        }

        .form-select {
            padding: .7rem 2.2rem .7rem 1rem;
            border: 1px solid var(--rule);
            border-radius: 8px;
            font-family: var(--sans);
            font-size: .88rem;
            color: var(--ink);
            background: var(--surface) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%238a8a8a'/%3E%3C/svg%3E") no-repeat right 1rem center;
            appearance: none;
            outline: none;
            cursor: pointer;
            transition: border-color .18s;
            min-width: 195px;
        }

        .form-select:focus {
            border-color: var(--ink);
        }

        .btn-clear {
            padding: .7rem 1.3rem;
            border: 1px solid var(--rule);
            border-radius: 8px;
            font-family: var(--sans);
            font-size: .8rem;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: var(--ink-muted);
            background: var(--white);
            cursor: pointer;
            transition: all .18s;
            white-space: nowrap;
        }

        .btn-clear:hover {
            border-color: var(--ink);
            color: var(--ink);
        }

        .chips {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
        }

        .chip {
            padding: .35rem .9rem;
            border-radius: 2rem;
            border: 1px solid var(--rule);
            font-size: .75rem;
            font-family: var(--sans);
            letter-spacing: .04em;
            color: var(--ink-muted);
            background: var(--white);
            cursor: pointer;
            transition: all .18s var(--ease);
        }

        .chip:hover {
            border-color: var(--ink-soft);
            color: var(--ink);
        }

        .chip.active {
            background: var(--ink);
            color: var(--white);
            border-color: var(--ink);
        }

        .catalog-head {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.8rem 2rem .8rem;
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            gap: 1rem;
        }

        .catalog-title {
            font-family: var(--serif);
            font-size: 1.2rem;
            letter-spacing: -.01em;
            color: var(--ink);
        }

        .catalog-count {
            font-size: .75rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--ink-muted);
        }

        .products-grid {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem 4rem;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1px;
            border-top: 1px solid var(--rule);
            background: var(--rule);
        }

        .product-card {
            background: var(--white);
            display: flex;
            flex-direction: column;
            cursor: pointer;
            transition: background .2s var(--ease);
            position: relative;
            overflow: hidden;
            opacity: 0;
            animation: fade-up .4s var(--ease) forwards;
        }

        .product-card:hover {
            background: var(--surface);
        }

        .product-card:hover .card-img {
            transform: scale(1.03);
        }

        .card-img-wrap {
            aspect-ratio: 1;
            overflow: hidden;
            background: var(--surface);
            position: relative;
        }

        .card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .5s var(--ease);
        }

        .card-badge {
            position: absolute;
            top: .75rem;
            right: .75rem;
            font-size: .65rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: .25rem .6rem;
            border-radius: 2rem;
            font-family: var(--sans);
            font-weight: 500;
        }

        .badge-in {
            background: rgba(26, 127, 75, .1);
            color: var(--success);
            border: 1px solid rgba(26, 127, 75, .2);
        }

        .badge-out {
            background: rgba(192, 57, 43, .08);
            color: var(--danger);
            border: 1px solid rgba(192, 57, 43, .18);
        }

        .card-body {
            padding: 1rem 1.1rem 1.2rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .card-cat {
            font-size: .7rem;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--ink-muted);
            margin-bottom: .3rem;
        }

        .card-name {
            font-family: var(--serif);
            font-size: 1.05rem;
            line-height: 1.25;
            color: var(--ink);
            letter-spacing: -.01em;
            margin-bottom: .6rem;
        }

        .card-price {
            font-size: 1.1rem;
            font-weight: 600;
            letter-spacing: -.02em;
            color: var(--ink);
            margin-bottom: .5rem;
        }

        .card-desc {
            font-size: .78rem;
            color: var(--ink-muted);
            line-height: 1.55;
            margin-bottom: .9rem;
            flex: 1;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: .75rem;
            border-top: 1px solid var(--rule);
            margin-top: auto;
            gap: .75rem;
        }

        .card-stock,
        .card-unit {
            font-size: .72rem;
            color: var(--ink-muted);
        }

        .card-view-btn {
            width: 100%;
            margin-top: .9rem;
            padding: .55rem 0;
            border: 1px solid var(--rule);
            border-radius: 6px;
            background: transparent;
            font-family: var(--sans);
            font-size: .75rem;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--ink);
            cursor: pointer;
            transition: all .18s var(--ease);
        }

        .card-view-btn:hover {
            background: var(--ink);
            color: var(--white);
            border-color: var(--ink);
        }

        .pagination-wrap {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.5rem 2rem 4rem;
            display: flex;
            justify-content: center;
            gap: .35rem;
        }

        .page-btn {
            width: 36px;
            height: 36px;
            border: 1px solid var(--rule);
            border-radius: 6px;
            background: var(--white);
            font-family: var(--sans);
            font-size: .8rem;
            color: var(--ink-muted);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .16s;
        }

        .page-btn:hover {
            border-color: var(--ink);
            color: var(--ink);
        }

        .page-btn.active {
            background: var(--ink);
            color: var(--white);
            border-color: var(--ink);
        }

        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            backdrop-filter: blur(4px);
            z-index: 200;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal-box {
            background: var(--white);
            border-radius: 14px;
            width: 100%;
            max-width: 820px;
            max-height: 92vh;
            overflow-y: auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            animation: modal-in .25s var(--ease);
            position: relative;
        }

        @keyframes modal-in {
            from {
                opacity: 0;
                transform: translateY(20px) scale(.97);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-img-side {
            position: relative;
        }

        .modal-img {
            width: 100%;
            height: 100%;
            min-height: 340px;
            object-fit: cover;
            border-radius: 14px 0 0 14px;
            display: block;
        }

        .modal-info-side {
            padding: 2.5rem 2rem;
            display: flex;
            flex-direction: column;
        }

        .modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .9);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: var(--ink);
            transition: background .15s;
        }

        .modal-close:hover {
            background: var(--rule);
        }

        .modal-eyebrow {
            font-size: .68rem;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: var(--ink-muted);
            margin-bottom: .5rem;
        }

        .modal-title {
            font-family: var(--serif);
            font-size: 1.8rem;
            line-height: 1.1;
            letter-spacing: -.02em;
            color: var(--ink);
            margin-bottom: .8rem;
        }

        .modal-price {
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: -.03em;
            color: var(--ink);
            margin-bottom: 1rem;
        }

        .modal-desc {
            font-size: .85rem;
            color: var(--ink-muted);
            line-height: 1.7;
            margin-bottom: 1.5rem;
            flex: 1;
        }

        .modal-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .75rem;
            padding: 1.2rem;
            background: var(--surface);
            border-radius: 8px;
            margin-bottom: 1.2rem;
        }

        .modal-meta-label {
            font-size: .65rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--ink-muted);
            margin-bottom: .15rem;
        }

        .modal-meta-val {
            font-size: .85rem;
            font-weight: 500;
            color: var(--ink);
        }

        .empty-state {
            max-width: 1200px;
            margin: 0 auto;
            padding: 5rem 2rem;
            text-align: center;
            background: var(--white);
            grid-column: 1 / -1;
        }

        .empty-state p {
            color: var(--ink-muted);
            font-size: .9rem;
        }

        footer {
            border-top: 1px solid var(--rule);
            padding: 1.5rem 2rem;
            text-align: center;
            font-size: .72rem;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--ink-muted);
        }

        @media (max-width: 640px) {
            .topbar {
                padding-inline: 1.2rem;
                gap: 1rem;
            }

            .hero {
                padding: 3rem 1.2rem 2.5rem;
            }

            .hero-title {
                font-size: 3rem;
            }

            .modal-box {
                grid-template-columns: 1fr;
            }

            .modal-img {
                min-height: 220px;
                border-radius: 14px 14px 0 0;
            }

            .modal-img-side .modal-close {
                top: .7rem;
                right: .7rem;
            }

            .toolbar,
            .catalog-head,
            .products-grid,
            .section-wrap,
            .pagination-wrap {
                padding-left: 1.2rem;
                padding-right: 1.2rem;
            }
        }

        @keyframes fade-up {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .d-none {
            display: none !important;
        }
    </style>
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
