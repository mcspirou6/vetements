<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Coupon;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cart = auth()->user()->cart;

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide');
        }

        return view('checkout.index', compact('cart'));
    }

    public function store(Request $request)
    {
        $cart = auth()->user()->cart;

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide');
        }

        $request->validate([
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_country' => 'required|string',
            'shipping_postal_code' => 'required|string',
            'shipping_phone' => 'required|string',
            'payment_method' => 'required|in:card,paypal',
            'notes' => 'nullable|string'
        ]);

        $total = $cart->total;
        $couponDiscount = 0;

        if (session()->has('coupon_id')) {
            $coupon = Coupon::find(session('coupon_id'));
            if ($coupon && $coupon->isValid()) {
                $couponDiscount = $coupon->calculateDiscount($total);
                $total -= $couponDiscount;
                $coupon->increment('used_times');
            }
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'total_amount' => $total,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'shipping_address' => $request->shipping_address,
            'shipping_city' => $request->shipping_city,
            'shipping_country' => $request->shipping_country,
            'shipping_postal_code' => $request->shipping_postal_code,
            'shipping_phone' => $request->shipping_phone,
            'notes' => $request->notes
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->current_price,
                'size' => $item->size,
                'color' => $item->color
            ]);

            // Décrémenter le stock
            $item->product->decrement('quantity', $item->quantity);
        }

        // Vider le panier
        $cart->items()->delete();
        session()->forget('coupon_id');

        // Si paiement par carte, rediriger vers la page de paiement
        if ($request->payment_method === 'card') {
            return redirect()->route('checkout.payment', $order);
        }

        // Si paiement PayPal, rediriger vers PayPal
        if ($request->payment_method === 'paypal') {
            return redirect()->route('checkout.paypal', $order);
        }

        return redirect()->route('orders.show', $order)
            ->with('success', 'Commande passée avec succès');
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
}
