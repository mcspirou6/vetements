<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        
        // Si le panier est vide, rediriger vers la page panier vide
        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $total = collect($cart)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('checkout.index', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.empty');
        }

        // Validation des données
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'country' => 'required|string|size:2',
        ]);

        try {
            // Calculer le total
            $total = collect($cart)->sum(function($item) {
                return $item['price'] * $item['quantity'];
            });

            // Créer la commande
            $order = Order::create([
                'user_id' => auth()->id(),
                'total_amount' => $total,
                'status' => 'pending',
                'shipping_address' => json_encode([
                    'firstname' => $request->firstname,
                    'lastname' => $request->lastname,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city,
                    'postal_code' => $request->postal_code,
                    'country' => $request->country,
                ]),
            ]);

            // Vider le panier
            session()->forget('cart');

            return redirect()->route('order.confirmation', $order)
                           ->with('success', 'Votre commande a été enregistrée avec succès !');

        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la création de votre commande.');
        }
    }

    public function payment(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->payment_status !== 'pending') {
            return redirect()->route('orders.show', $order);
        }

        return view('checkout.payment', compact('order'));
    }

    public function processPayment(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->payment_status !== 'pending') {
            return redirect()->route('orders.show', $order);
        }

        // Ici, nous traiterons le paiement avec Stripe ou un autre service de paiement
        try {
            // Traitement du paiement...

            $order->update([
                'payment_status' => 'completed',
                'status' => 'processing'
            ]);

            return redirect()->route('orders.show', $order)
                ->with('success', 'Paiement effectué avec succès');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du paiement : ' . $e->getMessage());
        }
    }

    public function confirmation(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('checkout.confirmation', compact('order'));
    }
}
