@extends('layouts.app')

@section('title', 'Paiement')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Choisissez votre méthode de paiement</h3>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h4 class="mb-3">Montant total : {{ number_format($order->total_amount, 2) }} €</h4>
                        <p class="text-muted">Commande #{{ $order->order_number }}</p>
                    </div>

                    <form action="{{ route('checkout.process-payment', $order) }}" method="POST" id="payment-form">
                        @csrf
                        <div class="payment-methods">
                            <!-- Carte bancaire -->
                            <div class="payment-method mb-4">
                                <div class="form-check custom-radio">
                                    <input class="form-check-input" type="radio" name="payment_method" id="stripe" value="stripe" checked>
                                    <label class="form-check-label" for="stripe">
                                        <i class="fas fa-credit-card me-2"></i>
                                        Carte bancaire
                                    </label>
                                </div>
                                <div class="payment-details mt-3" id="stripe-details">
                                    <div id="card-element" class="form-control mb-3">
                                        <!-- Stripe Elements s'affichera ici -->
                                    </div>
                                    <div id="card-errors" class="alert alert-danger d-none" role="alert"></div>
                                </div>
                            </div>

                            <!-- PayPal -->
                            <div class="payment-method mb-4">
                                <div class="form-check custom-radio">
                                    <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal">
                                    <label class="form-check-label" for="paypal">
                                        <i class="fab fa-paypal me-2"></i>
                                        PayPal
                                    </label>
                                </div>
                                <div class="payment-details mt-3 d-none" id="paypal-details">
                                    <p class="text-muted">
                                        Vous serez redirigé vers PayPal pour effectuer le paiement en toute sécurité.
                                    </p>
                                </div>
                            </div>

                            <!-- Western Union -->
                            <div class="payment-method mb-4">
                                <div class="form-check custom-radio">
                                    <input class="form-check-input" type="radio" name="payment_method" id="western_union" value="western_union">
                                    <label class="form-check-label" for="western_union">
                                        <i class="fas fa-university me-2"></i>
                                        Western Union
                                    </label>
                                </div>
                                <div class="payment-details mt-3 d-none" id="western-union-details">
                                    <p class="text-muted">
                                        Les instructions pour le transfert Western Union vous seront fournies après validation.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg" id="submit-button">
                                Payer {{ number_format($order->total_amount, 2) }} €
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.payment-method {
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.payment-method:hover {
    border-color: #6c757d;
}

.payment-method.active {
    border-color: #0d6efd;
    background-color: #f8f9fa;
}

.custom-radio .form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.form-check-label {
    font-size: 1.1rem;
    font-weight: 500;
}

#card-element {
    padding: 1rem;
    background-color: #fff;
}
</style>
@endpush

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Stripe setup
    const stripe = Stripe('{{ config('services.stripe.key') }}');
    const elements = stripe.elements();
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#32325d',
            },
        },
    });
    cardElement.mount('#card-element');

    // Gestion des erreurs Stripe
    cardElement.addEventListener('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
            displayError.classList.remove('d-none');
        } else {
            displayError.textContent = '';
            displayError.classList.add('d-none');
        }
    });

    // Gestion de l'affichage des détails de paiement
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const paymentDetails = {
        stripe: document.getElementById('stripe-details'),
        paypal: document.getElementById('paypal-details'),
        western_union: document.getElementById('western-union-details')
    };

    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            // Masquer tous les détails
            Object.values(paymentDetails).forEach(detail => {
                detail.classList.add('d-none');
            });
            
            // Afficher les détails de la méthode sélectionnée
            const selectedDetails = paymentDetails[this.value];
            if (selectedDetails) {
                selectedDetails.classList.remove('d-none');
            }

            // Mettre à jour les classes active
            document.querySelectorAll('.payment-method').forEach(pm => {
                pm.classList.remove('active');
            });
            this.closest('.payment-method').classList.add('active');
        });
    });

    // Activer la première méthode par défaut
    document.querySelector('input[name="payment_method"]:checked')
        .closest('.payment-method').classList.add('active');

    // Gestion du formulaire Stripe
    const form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        
        if (paymentMethod === 'stripe') {
            event.preventDefault();
            
            stripe.createToken(cardElement).then(function(result) {
                if (result.error) {
                    const errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                    errorElement.classList.remove('d-none');
                } else {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'stripeToken');
                    hiddenInput.setAttribute('value', result.token.id);
                    form.appendChild(hiddenInput);
                    form.submit();
                }
            });
        }
    });
});
</script>
@endpush
