<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $orders = auth()->user()->orders()
            ->with(['items.product', 'address'])
            ->latest()
            ->paginate(10);

        return view('account.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['items.product', 'address']);

        return view('account.orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        $this->authorize('cancel', $order);

        if (!$order->can_be_cancelled) {
            return back()->with('error', 'Cette commande ne peut plus être annulée.');
        }

        $order->cancel();

        return back()->with('success', 'Votre commande a été annulée.');
    }

    public function return(Order $order)
    {
        $this->authorize('return', $order);

        if (!$order->can_be_returned) {
            return back()->with('error', 'Cette commande ne peut plus être retournée.');
        }

        return view('account.orders.return', compact('order'));
    }

    public function submitReturn(Order $order, Request $request)
    {
        $this->authorize('return', $order);

        if (!$order->can_be_returned) {
            return back()->with('error', 'Cette commande ne peut plus être retournée.');
        }

        $validated = $request->validate([
            'items' => ['required', 'array'],
            'items.*' => ['required', 'exists:order_items,id'],
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $order->createReturn($validated['items'], $validated['reason']);

        return redirect()->route('account.orders.show', $order)
            ->with('success', 'Votre demande de retour a été enregistrée.');
    }
}
