<?php

use App\Models\Empresa;

$empresa = Empresa::first();
?>
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-4" href="{{ route('panel') }}">
        <span class="brand-mark"><i class="fa-solid fa-cube"></i></span>
        <span class="brand-name">{{ $empresa->nombre ?? 'BlueCrow POS' }}</span>
    </a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-3 me-lg-3 topbar-action" id="sidebarToggle" href="#!" aria-label="Mostrar u ocultar menú lateral">
        <i class="fas fa-bars"></i>
    </button>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">

    </form>
    <div class="nav-item dropdown me-2 me-md-3">
        <a class="nav-link dropdown-toggle topbar-icon-link" href="#" role="button" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-bell"></i>
            <span class="badge bg-danger rounded-pill">{{ Auth::user()->unreadNotifications->count() }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end notifications-menu" aria-labelledby="notificationsDropdown">
            @forelse (Auth::user()->unreadNotifications->take(5) as $notification)
            <li>
                <a href="#" class="dropdown-item">
                    {{ $notification->data['message'] ?? 'Nueva notificación' }}
                    <br>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </a>
            </li>
            @empty
            <li>
                <span class="dropdown-item text-muted">Sin notificaciones nuevas</span>
            </li>
            @endforelse
            <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item text-center" href="#">Ver todas</a></li>
        </ul>
    </div>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle topbar-profile-link" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="topbar-profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                <span class="topbar-profile-name d-none d-md-inline">{{ auth()->user()->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                @can('ver-perfil')
                <li><a class="dropdown-item" href="{{ route('profile.index') }}">Configuraciones</a></li>
                @endcan
                @can('ver-registro-actividad')
                <li><a class="dropdown-item" href="{{ route('activityLog.index') }}">Registro de actividad</a></li>
                @endcan
                <li>
                    <hr class="dropdown-divider" />
                </li>
                <li><a class="dropdown-item" href="{{ route('logout') }}">Cerrar sesión</a></li>
            </ul>
        </li>
    </ul>
</nav>
