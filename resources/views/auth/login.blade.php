<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>Maleri — Iniciar sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            --danger: #b91c1c;
            --serif: 'DM Serif Display', Georgia, serif;
            --sans: 'DM Sans', system-ui, sans-serif;
            --ease: cubic-bezier(.25, .46, .45, .94);
        }

        html,
        body {
            min-height: 100%;
            font-family: var(--sans);
            background: var(--white);
            color: var(--ink);
        }

        body {
            margin: 0;
        }

        .page {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        .panel-left {
            position: relative;
            background: var(--ink);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem;
            overflow: hidden;
        }

        .panel-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, .04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .04) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
        }

        .panel-left::after {
            content: 'M';
            position: absolute;
            bottom: -2rem;
            right: -1rem;
            font-family: var(--serif);
            font-style: italic;
            font-size: 28vw;
            line-height: 1;
            color: rgba(255, 255, 255, .04);
            pointer-events: none;
            user-select: none;
        }

        .panel-brand,
        .panel-copy,
        .panel-footer {
            position: relative;
            z-index: 1;
            animation: fade-up .6s var(--ease) both;
        }

        .panel-brand {
            display: inline-flex;
            align-items: center;
            gap: .7rem;
            text-decoration: none;
            width: fit-content;
        }

        .panel-brand-icon {
            width: 36px;
            height: 36px;
            background: var(--white);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .panel-brand-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .panel-brand-name {
            font-family: var(--serif);
            font-size: 1.3rem;
            color: var(--white);
            letter-spacing: -.01em;
        }

        .panel-copy {
            animation-delay: .15s;
        }

        .panel-footer {
            animation-delay: .25s;
        }

        .panel-eyebrow,
        .form-eyebrow {
            font-size: .65rem;
            letter-spacing: .22em;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: .55rem;
        }

        .panel-eyebrow {
            color: rgba(255, 255, 255, .4);
            margin-bottom: .9rem;
        }

        .panel-eyebrow::before,
        .form-eyebrow::before {
            content: '';
            display: inline-block;
            width: 1.5rem;
            height: 1px;
        }

        .panel-eyebrow::before {
            background: rgba(255, 255, 255, .25);
        }

        .form-eyebrow::before {
            background: var(--rule);
        }

        .panel-headline {
            font-family: var(--serif);
            font-size: clamp(2.4rem, 4vw, 3.4rem);
            line-height: 1.05;
            letter-spacing: -.03em;
            color: var(--white);
            margin-bottom: 1.2rem;
        }

        .panel-headline em,
        .form-title em {
            font-style: italic;
        }

        .panel-headline em {
            color: rgba(255, 255, 255, .55);
        }

        .panel-sub {
            font-size: .85rem;
            color: rgba(255, 255, 255, .45);
            line-height: 1.7;
            max-width: 320px;
        }

        .panel-footer {
            font-size: .7rem;
            letter-spacing: .08em;
            color: rgba(255, 255, 255, .2);
        }

        .panel-right {
            background: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
        }

        .form-wrap {
            width: 100%;
            max-width: 380px;
            animation: fade-up .55s .1s var(--ease) both;
        }

        .form-header {
            margin-bottom: 2.5rem;
        }

        .form-eyebrow {
            color: var(--ink-muted);
            margin-bottom: .7rem;
        }

        .form-title {
            font-family: var(--serif);
            font-size: 2rem;
            letter-spacing: -.03em;
            color: var(--ink);
            line-height: 1.1;
        }

        .form-title em {
            color: var(--ink-soft);
        }

        .error-box {
            background: rgba(185, 28, 28, .06);
            border: 1px solid rgba(185, 28, 28, .2);
            border-radius: 8px;
            padding: .8rem 1rem;
            margin-bottom: 1.5rem;
        }

        .error-item {
            font-size: .8rem;
            color: var(--danger);
            display: flex;
            align-items: flex-start;
            gap: .4rem;
            line-height: 1.5;
        }

        .error-item+.error-item {
            margin-top: .3rem;
        }

        .error-item::before {
            content: '—';
            flex-shrink: 0;
            opacity: .5;
        }

        .field {
            margin-bottom: 1.1rem;
        }

        .field-label {
            display: block;
            font-size: .7rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--ink-muted);
            margin-bottom: .5rem;
        }

        .field-input-wrap {
            position: relative;
        }

        .field-icon,
        .pw-toggle {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
        }

        .field-icon {
            left: 1rem;
            color: var(--ink-muted);
            pointer-events: none;
            transition: color .2s;
        }

        .field-input {
            width: 100%;
            padding: .85rem 1rem .85rem 2.8rem;
            border: 1px solid var(--rule);
            border-radius: 8px;
            font-family: var(--sans);
            font-size: .9rem;
            color: var(--ink);
            background: var(--surface);
            outline: none;
            transition: border-color .2s var(--ease), background .2s var(--ease), box-shadow .2s var(--ease);
        }

        .field-input::placeholder {
            color: #aaaaaa;
        }

        .field-input:focus {
            border-color: var(--ink);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(10, 10, 10, .06);
        }

        .field-input:focus+.field-icon,
        .field-input-wrap.is-focused .field-icon {
            color: var(--ink);
        }

        .password-input {
            padding-right: 2.8rem;
        }

        .pw-toggle {
            right: .9rem;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--ink-muted);
            padding: .2rem;
            display: flex;
            align-items: center;
            transition: color .18s;
        }

        .pw-toggle:hover {
            color: var(--ink);
        }

        .btn-submit {
            width: 100%;
            margin-top: 1.8rem;
            padding: .9rem;
            background: var(--ink);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-family: var(--sans);
            font-size: .8rem;
            font-weight: 500;
            letter-spacing: .12em;
            text-transform: uppercase;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: transform .18s var(--ease), box-shadow .18s var(--ease);
        }

        .btn-submit::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0);
            transition: background .2s;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(10, 10, 10, .18);
        }

        .btn-submit:hover::after {
            background: rgba(255, 255, 255, .07);
        }

        .btn-submit:active {
            transform: translateY(0);
            box-shadow: none;
        }

        .form-divider {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin: 1.8rem 0 1.2rem;
            color: var(--ink-muted);
            font-size: .7rem;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .form-divider::before,
        .form-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--rule);
        }

        .catalog-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            padding: .75rem;
            border: 1px solid var(--rule);
            border-radius: 8px;
            font-size: .8rem;
            font-family: var(--sans);
            color: var(--ink-muted);
            text-decoration: none;
            transition: all .18s var(--ease);
        }

        .catalog-link:hover {
            border-color: var(--ink);
            color: var(--ink);
            background: var(--surface);
        }

        @keyframes fade-up {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 720px) {
            .page {
                grid-template-columns: 1fr;
            }

            .panel-left {
                min-height: 220px;
                padding: 2rem;
            }

            .panel-left::after {
                font-size: 52vw;
            }

            .panel-copy {
                padding-top: 1rem;
            }

            .panel-headline {
                font-size: 2.2rem;
            }

            .panel-footer {
                display: none;
            }

            .panel-right {
                padding: 2.5rem 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="panel-left">
            <a class="panel-brand" href="{{ route('panel') }}">

                <span class="panel-brand-name">Maleri</span>
            </a>

            <div class="panel-copy">
                <p class="panel-eyebrow">Fine &amp; Fashion Jewelry</p>
                <h1 class="panel-headline">Bienvenido<br><em>de vuelta</em></h1>
                <p class="panel-sub">Accede al panel de administración para gestionar tu catálogo, inventario y pedidos.</p>
            </div>

            <p class="panel-footer">&copy; BlueCrow {{ date('Y') }}</p>
        </div>

        <div class="panel-right">
            <div class="form-wrap">
                <div class="form-header">
                    <p class="form-eyebrow">Acceso al panel</p>
                    <h2 class="form-title">Iniciar<br><em>sesión</em></h2>
                </div>

                @if ($errors->any())
                <div class="error-box">
                    @foreach ($errors->all() as $item)
                    <div class="error-item">{{ $item }}</div>
                    @endforeach
                </div>
                @endif

                <form action="{{ route('login.login') }}" method="POST">
                    @csrf

                    <div class="field">
                        <label class="field-label" for="email">Correo electrónico</label>
                        <div class="field-input-wrap">
                            <input
                                id="email"
                                class="field-input"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="hola@ejemplo.com"
                                autocomplete="email"
                                autofocus>
                            <svg class="field-icon" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                                <rect x=".7" y="2.7" width="12.6" height="8.6" rx="1.4" stroke="currentColor" stroke-width="1.2" />
                                <path d="M1 3.5l6 4.5 6-4.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" />
                            </svg>
                        </div>
                    </div>

                    <div class="field">
                        <label class="field-label" for="password">Contraseña</label>
                        <div class="field-input-wrap">
                            <input
                                id="password"
                                class="field-input password-input"
                                type="password"
                                name="password"
                                placeholder="••••••••"
                                autocomplete="current-password">
                            <svg class="field-icon" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                                <rect x=".7" y="5.7" width="12.6" height="7.6" rx="1.4" stroke="currentColor" stroke-width="1.2" />
                                <path d="M4.5 5.7V4a2.5 2.5 0 015 0v1.7" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" />
                            </svg>
                            <button type="button" class="pw-toggle" id="pwToggle" aria-label="Mostrar contraseña">
                                <svg id="eyeIcon" width="15" height="15" viewBox="0 0 15 15" fill="none" aria-hidden="true">
                                    <path d="M1 7.5C2.5 4 5 2 7.5 2s5 2 6.5 5.5C12.5 11 10 13 7.5 13S2.5 11 1 7.5z" stroke="currentColor" stroke-width="1.2" />
                                    <circle cx="7.5" cy="7.5" r="2" stroke="currentColor" stroke-width="1.2" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button class="btn-submit" type="submit">Ingresar</button>
                </form>

                <div class="form-divider">o</div>

                <a class="catalog-link" href="{{ route('panel') }}">
                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true">
                        <path d="M6.5 1L1 6.5l5.5 5.5M1 6.5h11" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Ver catálogo público
                </a>
            </div>
        </div>
    </div>

    <script>
        const pwToggle = document.getElementById('pwToggle');
        const pwInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (pwToggle && pwInput && eyeIcon) {
            pwToggle.addEventListener('click', () => {
                const show = pwInput.type === 'password';
                pwInput.type = show ? 'text' : 'password';
                pwToggle.setAttribute('aria-label', show ? 'Ocultar contraseña' : 'Mostrar contraseña');
                eyeIcon.innerHTML = show ?
                    '<path d="M2 2l11 11M5.5 5.7a2 2 0 002.8 2.8M1 7.5C2.5 4 5 2 7.5 2c1.2 0 2.3.4 3.3 1M13 7.5c-.8 1.8-2.2 3.2-3.8 4" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" />' :
                    '<path d="M1 7.5C2.5 4 5 2 7.5 2s5 2 6.5 5.5C12.5 11 10 13 7.5 13S2.5 11 1 7.5z" stroke="currentColor" stroke-width="1.2" /><circle cx="7.5" cy="7.5" r="2" stroke="currentColor" stroke-width="1.2" />';
            });
        }

        document.querySelectorAll('.field-input').forEach((input) => {
            const wrap = input.closest('.field-input-wrap');
            if (!wrap) {
                return;
            }

            input.addEventListener('focus', () => wrap.classList.add('is-focused'));
            input.addEventListener('blur', () => wrap.classList.remove('is-focused'));
        });
    </script>
</body>

</html>