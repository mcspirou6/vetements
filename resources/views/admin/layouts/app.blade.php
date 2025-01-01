<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Administration</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        #sidebar {
            min-height: 100vh;
            background-color: #212529;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: transform 0.3s ease-in-out;
        }
        
        #content {
            margin-left: 250px;
            padding: 2rem;
            transition: margin-left 0.3s ease-in-out;
        }
        
        .nav-link {
            color: rgba(255,255,255,.75);
            padding: .75rem 1.25rem;
        }
        
        .nav-link:hover, .nav-link.active {
            color: #fff;
            background-color: rgba(255,255,255,.1);
        }
        
        .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: .5rem;
        }

        .navbar-toggler {
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1050;
            display: none;
            border: none;
            background-color: #212529;
            color: white;
            padding: 0.5rem;
            border-radius: 0.25rem;
        }

        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
                width: 100%;
            }

            #content {
                margin-left: 0;
            }

            .navbar-toggler {
                display: block;
            }

            body.sidebar-open #sidebar {
                transform: translateX(0);
            }

            body.sidebar-open .navbar-toggler {
                left: calc(100% - 3rem);
            }
        }
    </style>
</head>
<body>
    <!-- Bouton toggle pour mobile -->
    <button class="navbar-toggler" type="button" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
    </button>

    <div class="d-flex">
        <!-- Sidebar -->
        <aside id="sidebar">
            @include('admin.partials.sidebar')
        </aside>

        <!-- Content -->
        <main id="content" class="flex-grow-1">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Toggle du sidebar
        document.querySelector('.navbar-toggler').addEventListener('click', function(e) {
            e.stopPropagation();
            document.body.classList.toggle('sidebar-open');
        });

        // Fermer le sidebar en cliquant sur le contenu
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768 && document.body.classList.contains('sidebar-open')) {
                if (!document.querySelector('#sidebar').contains(e.target) && 
                    !document.querySelector('.navbar-toggler').contains(e.target)) {
                    document.body.classList.remove('sidebar-open');
                }
            }
        });

        // Fermer le sidebar si la fenêtre est redimensionnée
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                document.body.classList.remove('sidebar-open');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
