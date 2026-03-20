<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>Maleri — Iniciar sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
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