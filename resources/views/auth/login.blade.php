<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>Maleri</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- CSS del Login -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styleslogin.css') }}">
</head>

<body>

    <div class="container" id="container">

        <!-- LOGIN REAL DE LARAVEL -->
        <div class="form-container sign-in">
            <form action="{{ route('login.login') }}" method="POST">
                @csrf

                <h1>Iniciar Sesión</h1>

                <div class="social-icons">
                    <i class="fa-solid fa-user-shield fa-2x"></i>
                </div>

                @if ($errors->any())
                @foreach ($errors->all() as $item)
                <div style="color:red; font-size:12px;">{{ $item }}</div>
                @endforeach
                @endif

                <input autofocus autocomplete="off"
                    value=""
                    name="email"
                    type="email"
                    placeholder="Correo electrónico">

                <input name="password"
                    value=""
                    type="password"
                    placeholder="Contraseña">

                <button type="submit">Ingresar</button>
            </form>
        </div>

        <!-- PANEL Lateral -->
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-right">
                    <!-- Logo Maleri -->
                    <div class="logo-container">
                        <img src="{{ asset('img/maleri.png') }}" alt="Maleri">
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>