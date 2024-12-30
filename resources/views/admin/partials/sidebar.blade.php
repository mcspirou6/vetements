<div class="h-100 d-flex flex-column">
    <!-- Logo -->
    <div class="px-4 py-4 text-center border-bottom border-light">
        <h4 class="text-white mb-0">{{ config('app.name') }}</h4>
        <small class="text-white-50">Administration</small>
    </div>

    <!-- Navigation -->
    <nav class="nav flex-column py-3">
        <a href="{{ route('admin.dashboard') }}" 
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.products.index') }}"
           class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="fas fa-box"></i>
            <span>Produits</span>
        </a>

        <a href="{{ route('admin.categories.index') }}"
           class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i>
            <span>Catégories</span>
        </a>

        <a href="{{ route('admin.orders.index') }}"
           class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class="fas fa-shopping-cart"></i>
            <span>Commandes</span>
        </a>

        <a href="{{ route('admin.users.index') }}"
           class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span>Utilisateurs</span>
        </a>

        <a href="{{ route('admin.reviews.index') }}"
           class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
            <i class="fas fa-star"></i>
            <span>Avis</span>
        </a>

        <a href="{{ route('admin.coupons.index') }}"
           class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
            <i class="fas fa-ticket-alt"></i>
            <span>Coupons</span>
        </a>
    </nav>

    <!-- Bottom Links -->
    <div class="mt-auto p-3 border-top border-light">
        <a href="{{ route('home') }}" class="nav-link text-white-50" target="_blank">
            <i class="fas fa-external-link-alt"></i>
            <span>Voir le site</span>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link text-white-50">
                <i class="fas fa-sign-out-alt"></i>
                <span>Déconnexion</span>
            </button>
        </form>
    </div>
</div>
