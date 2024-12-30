<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $orders = Order::with(['user', 'items.product'])
            ->latest()
            ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        // Si la commande est annulée, remettre les produits en stock
        if ($request->status === 'cancelled') {
            foreach ($order->items as $item) {
                $item->product->increment('quantity', $item->quantity);
            }
        }

        return back()->with('success', 'Statut de la commande mis à jour avec succès');
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,completed,failed,refunded'
        ]);

        $order->update([
            'payment_status' => $request->payment_status
        ]);

        return back()->with('success', 'Statut du paiement mis à jour avec succès');
    }

    public function invoice(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.invoice', compact('order'));
    }

    public function filter(Request $request)
    {
        $orders = Order::with(['user', 'items.product']);

        if ($request->status) {
            $orders->where('status', $request->status);
        }

        if ($request->payment_status) {
            $orders->where('payment_status', $request->payment_status);
        }

        if ($request->date_from) {
            $orders->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $orders->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->min_amount) {
            $orders->where('total_amount', '>=', $request->min_amount);
        }

        if ($request->max_amount) {
            $orders->where('total_amount', '<=', $request->max_amount);
        }

        $orders = $orders->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }
}
