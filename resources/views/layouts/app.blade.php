<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Sistema de Donaciones')</title>
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style>
        .brand-link {
            background-color: #0d6efd !important;
        }
        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active {
            background-color: #0d6efd !important;
        }
            /* ===========================
       CHAT
       =========================== */

    .chat-container {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .chat-sidebar {
        flex: 1 1 260px;
        max-width: 320px;
    }

    .chat-main {
        flex: 2 1 480px;
        min-width: 0;
    }

    .chat-window {
        max-height: 480px;
        overflow-y: auto;
        padding-right: 0.5rem;
    }

    .chat-message-group {
        margin-bottom: 1.5rem;
    }

    .chat-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #9ca3af;
        margin-bottom: 0.25rem;
    }

    .chat-bubble {
        display: inline-block;
        padding: 0.6rem 0.9rem;
        border-radius: 1rem;
        max-width: 80%;
        font-size: 0.9rem;
        position: relative;
        margin-bottom: 0.25rem;
    }

    .chat-bubble-left {
        background-color: #e5e7eb;
        color: #111827;
        border-bottom-left-radius: 0.2rem;
    }

    .chat-bubble-right {
        background-color: #6366f1;
        color: #ffffff;
        border-bottom-right-radius: 0.2rem;
        margin-left: auto;
    }

    .chat-meta {
        font-size: 0.75rem;
        color: #9ca3af;
        margin-top: 0.15rem;
    }

    .chat-meta span + span::before {
        content: "•";
        margin: 0 0.25rem;
    }

    .chat-asunto {
        font-weight: 600;
        font-size: 0.85rem;
        color: #4b5563;
        margin-bottom: 0.15rem;
    }

    .chat-empty {
        text-align: center;
        color: #9ca3af;
        padding: 2rem 0;
        font-size: 0.9rem;
    }
        /* ===========================
       SALDOS DONACIONES
       =========================== */

    .saldo-summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .saldo-summary-card {
        border-radius: 0.75rem;
        padding: 1rem 1.2rem;
        color: #111827;
    }

    .saldo-summary-card h5 {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 0.2rem;
        opacity: 0.8;
    }

    .saldo-summary-card .saldo-value {
        font-size: 1.3rem;
        font-weight: 700;
    }

    .saldo-summary-card.total-original {
        background: linear-gradient(135deg, #e0f2fe, #eef2ff);
    }

    .saldo-summary-card.total-utilizado {
        background: linear-gradient(135deg, #fee2e2, #fef9c3);
    }

    .saldo-summary-card.total-disponible {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    }
        /* ===========================
       DONACIONES
       =========================== */

    .donaciones-summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .donaciones-summary-card {
        border-radius: 0.75rem;
        padding: 1rem 1.2rem;
        color: #111827;
    }

    .donaciones-summary-card h5 {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 0.2rem;
        opacity: 0.8;
    }

    .donaciones-summary-card .summary-value {
        font-size: 1.3rem;
        font-weight: 700;
    }

    .donaciones-summary-card.total-donado {
        background: linear-gradient(135deg, #e0f2fe, #eef2ff);
    }

    .donaciones-summary-card.total-monetaria {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    }

    .donaciones-summary-card.total-especie {
        background: linear-gradient(135deg, #fee2e2, #fef3c7);
    }
        /* ===========================
       DONACIONES-ASIGNACIONES
       =========================== */

    .donasig-summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .donasig-summary-card {
        border-radius: 0.75rem;
        padding: 1rem 1.2rem;
        color: #111827;
    }

    .donasig-summary-card h5 {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 0.2rem;
        opacity: 0.8;
    }

    .donasig-summary-card .summary-value {
        font-size: 1.3rem;
        font-weight: 700;
    }

    .donasig-summary-card.total-asignado {
        background: linear-gradient(135deg, #e0f2fe, #eef2ff);
    }

    .donasig-summary-card.total-registros {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    }
        /* ===========================
       CAMPAÑAS
       =========================== */

    .campanias-summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .campanias-summary-card {
        border-radius: 0.75rem;
        padding: 1rem 1.2rem;
        color: #111827;
    }

    .campanias-summary-card h5 {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 0.2rem;
        opacity: 0.8;
    }

    .campanias-summary-card .summary-value {
        font-size: 1.3rem;
        font-weight: 700;
    }

    .campanias-summary-card.total-campanias {
        background: linear-gradient(135deg, #e0f2fe, #eef2ff);
    }

    .campanias-summary-card.total-activas {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    }

    .campanias-summary-card.total-meta {
        background: linear-gradient(135deg, #fef3c7, #fee2e2);
    }

    .campania-badge-activa {
        padding: 0.25rem 0.6rem;
        border-radius: 999px;
        font-size: 0.75rem;
    }
        /* ===========================
       USUARIOS
       =========================== */

    .role-chip {
        display: inline-block;
        padding: 0.15rem 0.5rem;
        border-radius: 999px;
        font-size: 0.75rem;
        background-color: #e5e7eb;
        color: #374151;
        margin: 0 0.15rem 0.15rem 0;
    }

    .user-avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        background: linear-gradient(135deg, #6366f1, #3b82f6);
        color: white;
    }





    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('home') }}" class="nav-link">Inicio</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Crear nuevo -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fas fa-plus-circle"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">Crear Nuevo</span>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('roles.create') }}" class="dropdown-item">
                            <i class="fas fa-user-tag mr-2"></i> Rol
                        </a>
                        <a href="{{ route('usuarios.create') }}" class="dropdown-item">
                            <i class="fas fa-user mr-2"></i> Usuario
                        </a>
                        <a href="{{ route('campanias.create') }}" class="dropdown-item">
                            <i class="fas fa-bullhorn mr-2"></i> Campaña
                        </a>
                        <a href="{{ route('donaciones.create') }}" class="dropdown-item">
                            <i class="fas fa-hand-holding-heart mr-2"></i> Donación
                        </a>
                        <a href="{{ route('asignaciones.create') }}" class="dropdown-item">
                            <i class="fas fa-tasks mr-2"></i> Asignación
                        </a>
                        <a href="{{ route('mensajes.create') }}" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> Mensaje
                        </a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="brand-link text-center">
                <i class="fas fa-hand-holding-heart fa-lg"></i>
                <span class="brand-text font-weight-light ml-2">Donaciones</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <i class="fas fa-user-circle fa-2x text-white"></i>
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">Usuario Admin</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        
                        <!-- Roles -->
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-tag"></i>
                                <p>Roles</p>
                            </a>
                        </li>

                        <!-- Usuarios -->
                        <li class="nav-item">
                            <a href="{{ route('usuarios.index') }}" class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Usuarios</p>
                            </a>
                        </li>

                        <!-- Usuarios-Roles -->
                        <li class="nav-item">
                            <a href="{{ route('usuariosroles.index') }}" class="nav-link {{ request()->routeIs('usuariosroles.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-shield"></i>
                                <p>Usuarios–Roles</p>
                            </a>
                        </li>

                        <!-- Campañas -->
                        <li class="nav-item">
                            <a href="{{ route('campanias.index') }}" class="nav-link {{ request()->routeIs('campanias.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-bullhorn"></i>
                                <p>Campañas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('campanias.cierreSeleccion') }}"
                                class="nav-link {{ request()->routeIs('campanias.cierre*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-lock"></i>
                                <p>Cierre de campaña</p>
                            </a>
                        </li>

                        <!-- Donaciones (con submenu) -->
                        <li class="nav-item {{ request()->routeIs('donaciones.*') || request()->routeIs('estados.*') || request()->routeIs('usuarios.estadoCuenta*') || request()->routeIs('reporte.cierreCaja') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('donaciones.*') || request()->routeIs('estados.*') || request()->routeIs('usuarios.estadoCuenta*') || request()->routeIs('reporte.cierreCaja') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-hand-holding-heart"></i>
                                <p>
                                    Donaciones
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('donaciones.index') }}" class="nav-link {{ request()->routeIs('donaciones.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Donaciones</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('estados.index') }}" class="nav-link {{ request()->routeIs('estados.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Estados</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('usuarios.estadoCuentaSeleccion') }}"
                                        class="nav-link {{ request()->routeIs('usuarios.estadoCuentaSeleccion') || request()->routeIs('usuarios.estadoCuenta') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Estado de cuenta</p>
                                    </a>
                                </li>
                                {{-- 🔹 NUEVO: Cierre de caja general --}}
                                <li class="nav-item">
                                    <a href="{{ route('reporte.cierreCaja') }}"
                                       class="nav-link {{ request()->routeIs('reporte.cierreCaja') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Cierre de caja</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Asignaciones (con submenu) -->
                        <li class="nav-item {{ request()->routeIs('asignaciones.*') || request()->routeIs('detallesasignacion.*') || request()->routeIs('donacionesasignaciones.*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('asignaciones.*') || request()->routeIs('detallesasignacion.*') || request()->routeIs('donacionesasignaciones.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tasks"></i>
                                <p>
                                    Asignaciones
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('asignaciones.index') }}" class="nav-link {{ request()->routeIs('asignaciones.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Asignaciones</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Mensajes (con submenu) -->
                        <li class="nav-item {{ request()->routeIs('mensajes.*') || request()->routeIs('respuestasmensajes.*') || request()->routeIs('mensajes.centro*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('mensajes.*') || request()->routeIs('respuestasmensajes.*') || request()->routeIs('mensajes.centro*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-envelope"></i>
                                <p>
                                    Mensajes
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('mensajes.index') }}" class="nav-link {{ request()->routeIs('mensajes.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Mensajes</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('respuestasmensajes.index') }}" class="nav-link {{ request()->routeIs('respuestasmensajes.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Respuestas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('mensajes.centroSeleccion') }}"
                                        class="nav-link {{ request()->routeIs('mensajes.centro*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Centro de mensajes</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Saldos -->
                        <li class="nav-item">
                            <a href="{{ route('saldosdonaciones.index') }}" class="nav-link {{ request()->routeIs('saldosdonaciones.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-wallet"></i>
                                <p>Saldos</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('campanias.reporteGeneral') }}" 
                            class="nav-link {{ request()->routeIs('campanias.reporteGeneral') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-pie"></i>
                                <p>Reporte campañas</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    @yield('header')
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <strong>© {{ date('Y') }} Sistema de Donaciones</strong> — Laravel + PostgreSQL
            <div class="float-right d-none d-sm-inline-block">
                <b>Versión</b> 1.0.0
            </div>
        </footer>
    </div>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    
    @stack('scripts')
</body>
</html>
