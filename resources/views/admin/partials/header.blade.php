<header class="admin-header">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid px-0">
            <!-- Toggle Sidebar Button -->
            <button class="btn btn-link text-dark" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Search Form -->
            <form class="d-none d-md-flex ms-4" style="min-width: 300px;">
                <div class="input-group">
                    <span class="input-group-text border-end-0 bg-transparent">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="search" class="form-control border-start-0" 
                           placeholder="Rechercher..." aria-label="Search">
                </div>
            </form>

            <!-- Right Navigation -->
            <ul class="navbar-nav ms-auto align-items-center">
                <!-- Notifications -->
                <li class="nav-item dropdown me-3">
                    <a class="nav-link" href="#" id="notificationsDropdown" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            2
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end mt-2" aria-labelledby="notificationsDropdown">
                        <h6 class="dropdown-header">Notifications</h6>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-shopping-cart text-success"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-0">Nouvelle commande #1234</p>
                                    <small class="text-muted">Il y a 3 minutes</small>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-center" href="#">Voir toutes les notifications</a>
                    </div>
                </li>

                <!-- User Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" 
                       id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" 
                             class="rounded-circle me-2" alt="Avatar" width="32" height="32">
                        <span>{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end mt-2" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user-circle me-2"></i> Mon Profil
                            </a>
                        </li>
                        <li>
                             
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
