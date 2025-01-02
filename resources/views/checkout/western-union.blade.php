@extends('layouts.app')

@section('title', 'Instructions Western Union')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold">Instructions de paiement Western Union</h1>
                <p class="text-gray-600 mt-2">
                    Veuillez suivre ces instructions pour effectuer votre paiement via Western Union
                </p>
            </div>

            <!-- Détails de la commande -->
            <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                <h2 class="font-semibold mb-2">Récapitulatif de la commande</h2>
                <div class="flex justify-between items-center mb-2">
                    <span>Numéro de commande</span>
                    <span class="font-medium">#{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span>Montant à payer</span>
                    <span class="font-bold text-xl">{{ number_format($order->total_amount, 2) }} €</span>
                </div>
            </div>

            <!-- Instructions -->
            <div class="space-y-6">
                <div>
                    <h3 class="font-semibold text-lg mb-3">Étapes à suivre :</h3>
                    <ol class="list-decimal list-inside space-y-3 text-gray-700">
                        <li>Rendez-vous dans une agence Western Union près de chez vous</li>
                        <li>Indiquez que vous souhaitez effectuer un transfert d'argent vers :</li>
                        <li class="ml-6 bg-yellow-50 p-3 rounded-lg">
                            <div class="space-y-2">
                                <p><strong>Nom du bénéficiaire :</strong> {{ $transfer_info['name'] }}</p>
                                <p><strong>Pays :</strong> {{ $transfer_info['country'] }}</p>
                                <p><strong>Ville :</strong> {{ $transfer_info['city'] }}</p>
                            </div>
                        </li>
                        <li>Effectuez le paiement de {{ number_format($order->total_amount, 2) }} €</li>
                        <li>Conservez le numéro de transfert MTCN qui vous sera remis</li>
                        <li>Envoyez-nous le numéro MTCN par email à : support@votresite.com</li>
                    </ol>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="font-semibold mb-2">Important :</h4>
                    <ul class="list-disc list-inside space-y-2 text-gray-700">
                        <li>Votre commande sera traitée dès réception du paiement</li>
                        <li>Le délai de traitement est de 24-48h après réception du MTCN</li>
                        <li>En cas de problème, contactez notre support</li>
                    </ul>
                </div>

                <div class="text-center mt-8">
                    <a href="{{ route('orders.show', $order) }}" 
                       class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition">
                        Voir ma commande
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
