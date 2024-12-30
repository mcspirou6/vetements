<footer class="pt-5">
    <div class="container">
        <div class="row g-4">
            <!-- À propos -->
            <div class="col-12 col-md-4 mb-4">
                <h3 class="h5 mb-3 section-title">À propos de Speroshop</h3>
                <p class="text-muted">
                    Speroshop est votre boutique en ligne de référence pour la mode. Nous proposons une large gamme de vêtements de qualité pour hommes et femmes, avec un service client exceptionnel.
                </p>
                <div class="mt-4">
                    <a href="#" class="text-muted me-3 fs-5"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-muted me-3 fs-5"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-muted me-3 fs-5"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-muted fs-5"><i class="fab fa-pinterest"></i></a>
                </div>
            </div>

            <!-- Liens utiles -->
            <div class="col-6 col-md-2 mb-4">
                <h3 class="h5 mb-3 section-title">Liens utiles</h3>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('home') }}" class="text-muted text-decoration-none">Accueil</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('shop') }}" class="text-muted text-decoration-none">Boutique</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('contact') }}" class="text-muted text-decoration-none">Contact</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-muted text-decoration-none">Blog</a>
                    </li>
                </ul>
            </div>

            <!-- Service client -->
            <div class="col-6 col-md-3 mb-4">
                <h3 class="h5 mb-3 section-title">Service client</h3>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="#" class="text-muted text-decoration-none">FAQ</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-muted text-decoration-none">Livraison</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-muted text-decoration-none">Retours</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-muted text-decoration-none">Conditions générales</a>
                    </li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="col-12 col-md-3 mb-4">
                <h3 class="h5 mb-3 section-title">Newsletter</h3>
                <p class="text-muted mb-3">Inscrivez-vous pour recevoir nos dernières offres et nouveautés</p>
                <form action="#" method="POST" class="mb-3">
                    @csrf
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Votre email" required>
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
                <p class="small text-muted">
                    <i class="fas fa-lock me-1"></i>
                    Vos données sont protégées et ne seront jamais partagées
                </p>
            </div>
        </div>

        <!-- Paiement et copyright -->
        <div class="border-top border-light pt-4 mt-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="mb-0 text-muted">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <img src="https://via.placeholder.com/50x30" alt="Visa" class="me-2">
                    <img src="https://via.placeholder.com/50x30" alt="Mastercard" class="me-2">
                    <img src="https://via.placeholder.com/50x30" alt="PayPal" class="me-2">
                    <img src="https://via.placeholder.com/50x30" alt="Apple Pay">
                </div>
            </div>
        </div>
    </div>
</footer>
