<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Speroshop') }} - Admin - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #e74c3c;
            --accent-color: #3498db;
            --background-color: #f8f9fa;
            --text-color: #2c3e50;
            --light-text: #95a5a6;
            --white: #ffffff;
            --sidebar-width: 250px;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
        }

        /* Sidebar Styles */
        #sidebar {
            width: var(--sidebar-width);
            background-color: var(--primary-color);
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 100;
            transition: all 0.3s;
        }

        #sidebar .nav-link {
            color: var(--white);
            padding: 0.8rem 1.5rem;
            opacity: 0.85;
            transition: all 0.3s;
        }

        #sidebar .nav-link:hover {
            opacity: 1;
            background-color: rgba(255, 255, 255, 0.1);
        }

        #sidebar .nav-link.active {
            opacity: 1;
            background-color: var(--accent-color);
        }

        /* Main Content Styles */
        #content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s;
        }

        /* Header Styles */
        .admin-header {
            background-color: var(--white);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding: 1rem 2rem;
        }

        /* Card Styles */
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: var(--white);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding: 1rem 1.5rem;
        }

        /* Button Styles */
        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .btn-primary:hover {
            background-color: darken(var(--accent-color), 10%);
            border-color: darken(var(--accent-color), 10%);
        }

        /* Responsive */
        @media (max-width: 768px) {
            #sidebar {
                margin-left: calc(var(--sidebar-width) * -1);
            }

            #sidebar.active {
                margin-left: 0;
            }

            #content {
                margin-left: 0;
            }

            #content.active {
                margin-left: var(--sidebar-width);
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar">
            @include('admin.partials.sidebar')
        </div>

        <!-- Page Content -->
        <div id="content">
            <!-- Header -->
            @include('admin.partials.header')

            <!-- Main Content -->
            <main class="py-4 px-4">
                @yield('content')
            </main>

            <!-- Footer -->
            @include('admin.partials.footer')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Custom Scripts -->
    <script>
        $(document).ready(function() {
            // Toggle sidebar
            $("#sidebarToggle").click(function(e) {
                e.preventDefault();
                $("#wrapper").toggleClass("toggled");
            });

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Flash messages
            @if(session('success'))
                toastr.success('{{ session('success') }}');
            @endif

            @if(session('error'))
                toastr.error('{{ session('error') }}');
            @endif
        });
    </script>

    @stack('scripts')
</body>
</html>
