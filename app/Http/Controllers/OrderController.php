<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = auth()->user()->orders()->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Vérifier que l'utilisateur est bien le propriétaire de la commande
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()->with('error', 'Cette commande ne peut plus être annulée');
        }

        $order->update(['status' => 'cancelled']);

        // Remettre les produits en stock
        foreach ($order->items as $item) {
            $item->product->increment('quantity', $item->quantity);
        }

        return back()->with('success', 'Commande annulée avec succès');
    }

    public function track(Order $order)
    {
        if ($order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        return view('orders.track', compact('order'));
    }

    public function invoice(Order $order)
    {
        if ($order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        return view('orders.invoice', compact('order'));
    }
}
