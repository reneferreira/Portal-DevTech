{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - @yield('title', 'Dashboard') | TechNews</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <!-- Summernote (Editor de Texto) -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.css" rel="stylesheet">
    
    <style>
        body {
            font-size: .875rem;
            background-color: #f8f9fc;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            background-color: #1a1c23;
        }
        
        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: .5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }
        
        .sidebar .nav-link {
            font-weight: 500;
            color: rgba(255, 255, 255, 0.7);
            padding: 0.75rem 1rem;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link.active {
            color: white;
            background: #0d6efd;
        }
        
        .sidebar .nav-link i {
            margin-right: 0.5rem;
            width: 1.25rem;
            text-align: center;
        }
        
        /* Submenu */
        .sidebar .nav-item.dropdown .nav-link {
            cursor: pointer;
        }
        
        .sidebar .nav-item.dropdown .dropdown-menu {
            background-color: #2a2c3a;
            border: none;
            padding: 0;
            margin: 0;
            border-radius: 0;
        }
        
        .sidebar .nav-item.dropdown .dropdown-item {
            color: rgba(255, 255, 255, 0.7);
            padding: 0.5rem 1rem 0.5rem 2.5rem;
        }
        
        .sidebar .nav-item.dropdown .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .sidebar .nav-item.dropdown .dropdown-item i {
            margin-right: 0.5rem;
        }
        
        .navbar-brand {
            padding-top: .75rem;
            padding-bottom: .75rem;
            font-size: 1rem;
            background-color: rgba(0, 0, 0, .25);
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
        }
        
        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }
        
        @media (max-width: 767.98px) {
            .sidebar {
                position: static;
                height: auto;
            }
            .main-content {
                margin-left: 0;
            }
        }
        
        .card-stats {
            transition: transform 0.3s;
        }
        
        .card-stats:hover {
            transform: translateY(-5px);
        }
        
        .btn-group-sm > .btn, .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
        
        /* Badge de notificação */
        .nav-link .badge {
            position: relative;
            top: -2px;
            margin-left: 5px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                               href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2"></i>
                                Dashboard
                            </a>
                        </li>
                        
                        <!-- Conteúdo (Dropdown) -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.posts.*') || request()->routeIs('admin.categorias.*') || request()->routeIs('admin.tags.*') || request()->routeIs('admin.videos.*') ? 'active' : '' }}" 
                               href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-newspaper"></i>
                                Conteúdo
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}" 
                                       href="{{ route('admin.posts.index') }}">
                                        <i class="bi bi-file-text"></i> Posts
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('admin.categorias.*') ? 'active' : '' }}" 
                                       href="{{ route('admin.categorias.index') }}">
                                        <i class="bi bi-grid"></i> Categorias
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}" 
                                       href="{{ route('admin.tags.index') }}">
                                        <i class="bi bi-tags"></i> Tags
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('admin.videos.*') ? 'active' : '' }}" 
                                       href="{{ route('admin.videos.index') }}">
                                        <i class="bi bi-camera-reels"></i> Vídeos
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}" 
                                       href="{{ route('admin.banners.index') }}">
                                        <i class="bi bi-images"></i> Banners
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <!-- Interações (Dropdown) -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.comentarios.*') || request()->routeIs('admin.contatos.*') ? 'active' : '' }}" 
                               href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-chat-dots"></i>
                                Interações
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('admin.comentarios.*') ? 'active' : '' }}" 
                                       href="{{ route('admin.comentarios.index') }}">
                                        <i class="bi bi-chat-square-text"></i> Comentários
                                        @php
                                            $pendingComments = \App\Models\Comment::where('aprovado', false)->count();
                                        @endphp
                                        @if($pendingComments > 0)
                                            <span class="badge bg-danger rounded-pill float-end">{{ $pendingComments }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('admin.contatos.*') ? 'active' : '' }}" 
                                       href="{{ route('admin.contatos.index') }}">
                                        <i class="bi bi-envelope"></i> Contatos
                                        @php
                                            $pendingContacts = \App\Models\Contato::where('status', 'novo')->count();
                                        @endphp
                                        @if($pendingContacts > 0)
                                            <span class="badge bg-danger rounded-pill float-end">{{ $pendingContacts }}</span>
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <!-- Configurações (Opcional) -->
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#configMenu">
                                <i class="bi bi-gear"></i>
                                Configurações
                                <i class="bi bi-chevron-down float-end"></i>
                            </a>
                            <div class="collapse" id="configMenu">
                                <ul class="nav flex-column ms-3">
                                    <li class="nav-item">
                                        <a class="nav-link py-2" href="#">
                                            <i class="bi bi-person"></i> Perfil
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link py-2" href="#">
                                            <i class="bi bi-shield"></i> Segurança
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        
                        <hr class="bg-light">
                        
                        <!-- Links Rápidos -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}" target="_blank">
                                <i class="bi bi-eye"></i>
                                Ver Site
                            </a>
                        </li>
                        
                        <!-- Sair -->
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link text-danger bg-transparent border-0 w-100 text-start">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Sair
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Header -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('header')</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Meu Perfil</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-shield-lock"></i> Alterar Senha</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right"></i> Sair
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Alertas -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.js"></script>
    
    <script>
        // Inicializar DataTables
        $(document).ready(function() {
            if($('.datatable').length) {
                $('.datatable').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
                    },
                    pageLength: 25,
                    responsive: true
                });
            }
            
            // Inicializar Select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
            
            // Inicializar Summernote
            if($('#conteudo').length) {
                $('#conteudo').summernote({
                    height: 400,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ]
                });
            }
            
            // Active class para dropdown items
            $('.dropdown-item').each(function() {
                if ($(this).attr('href') === window.location.pathname) {
                    $(this).addClass('active');
                    $(this).closest('.dropdown').find('.dropdown-toggle').addClass('active');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>