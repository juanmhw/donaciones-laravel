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
    
    <style>
        .brand-link {
            background-color: #0d6efd !important;
        }
        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active {
            background-color: #0d6efd !important;
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
                <!-- Notifications Dropdown Menu -->
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

                        <!-- Donaciones (con submenu) -->
                        <li class="nav-item {{ request()->routeIs('donaciones.*') || request()->routeIs('estados.*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('donaciones.*') || request()->routeIs('estados.*') ? 'active' : '' }}">
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
                                <li class="nav-item">
                                    <a href="{{ route('detallesasignacion.index') }}" class="nav-link {{ request()->routeIs('detallesasignacion.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Detalles</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('donacionesasignaciones.index') }}" class="nav-link {{ request()->routeIs('donacionesasignaciones.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Donación → Asignación</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Mensajes (con submenu) -->
                        <li class="nav-item {{ request()->routeIs('mensajes.*') || request()->routeIs('respuestasmensajes.*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->routeIs('mensajes.*') || request()->routeIs('respuestasmensajes.*') ? 'active' : '' }}">
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
                            </ul>
                        </li>

                        <!-- Saldos -->
                        <li class="nav-item">
                            <a href="{{ route('saldosdonaciones.index') }}" class="nav-link {{ request()->routeIs('saldosdonaciones.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-wallet"></i>
                                <p>Saldos</p>
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