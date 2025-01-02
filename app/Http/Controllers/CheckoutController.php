<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        
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
            $total = collect($cart)->sum(function($item) {
                return $item['price'] * $item['quantity'];
            });

            // Générer un numéro de commande unique
            $orderNumber = 'CMD-' . strtoupper(uniqid());

            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => $orderNumber,
                'total_amount' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'shipping_address' => $request->address,
                'shipping_city' => $request->city,
                'shipping_country' => $request->country,
                'shipping_postal_code' => $request->postal_code,
                'shipping_phone' => $request->phone,
                'notes' => $request->notes ?? null,
            ]);

            foreach($cart as $id => $details) {
                $order->items()->create([
                    'product_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price']
                ]);
            }

            session()->forget('cart');

            return redirect()->route('checkout.payment', $order);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de la commande: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la création de votre commande.')
                        ->withInput();
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

        $paymentMethod = $request->input('payment_method');

        try {
            switch ($paymentMethod) {
                case 'stripe':
                    return $this->processStripePayment($request, $order);
                
                case 'paypal':
                    return $this->processPayPalPayment($order);
                
                case 'western_union':
                    return $this->processWesternUnionPayment($order);
                
                default:
                    throw new \Exception('Méthode de paiement non valide');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du paiement : ' . $e->getMessage());
        }
    }

    private function processStripePayment(Request $request, Order $order)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $token = $request->input('stripeToken');

            $charge = \Stripe\Charge::create([
                'amount' => $order->total_amount * 100,
                'currency' => 'eur',
                'source' => $token,
                'description' => "Commande #{$order->id}",
                'metadata' => [
                    'order_id' => $order->id
                ]
            ]);

            $order->update([
                'payment_status' => 'completed',
                'payment_method' => 'stripe',
                'status' => 'processing'
            ]);

            return redirect()->route('checkout.confirmation', $order)
                           ->with('success', 'Paiement effectué avec succès');

        } catch (\Stripe\Exception\CardException $e) {
            throw new \Exception('Erreur de carte : ' . $e->getMessage());
        }
    }

    private function processPayPalPayment(Order $order)
    {
        // Redirection vers PayPal avec les détails de la commande
        $paypalUrl = "https://www.sandbox.paypal.com/cgi-bin/webscr";
        $returnUrl = route('checkout.paypal.success', $order);
        $cancelUrl = route('checkout.paypal.cancel', $order);
        $notifyUrl = route('checkout.paypal.notify', $order);
        
        $queryParams = http_build_query([
            'cmd' => '_xclick',
            'business' => config('services.paypal.business_email'),
            'item_name' => "Commande #{$order->id}",
            'amount' => $order->total_amount,
            'currency_code' => 'EUR',
            'return' => $returnUrl,
            'cancel_return' => $cancelUrl,
            'notify_url' => $notifyUrl,
            'custom' => $order->id,
        ]);

        return redirect()->away($paypalUrl . '?' . $queryParams);
    }

    private function processWesternUnionPayment(Order $order)
    {
        $order->update([
            'payment_method' => 'western_union',
            'status' => 'pending_transfer'
        ]);

        return view('checkout.western-union', [
            'order' => $order,
            'transfer_info' => [
                'name' => 'VOTRE NOM COMPLET',
                'country' => 'FRANCE',
                'city' => 'PARIS'
            ]
        ]);
    }

    public function confirmation(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('checkout.confirmation', compact('order'));
    }

    public function paypalSuccess(Request $request, Order $order)
    {
        // Vérification du paiement PayPal
        if ($request->has('PayerID') && $request->has('token')) {
            $order->update([
                'payment_status' => 'completed',
                'payment_method' => 'paypal',
                'status' => 'processing'
            ]);

            return redirect()->route('checkout.confirmation', $order)
                           ->with('success', 'Paiement PayPal effectué avec succès');
        }

        return redirect()->route('checkout.payment', $order)
                        ->with('error', 'Le paiement PayPal n\'a pas pu être vérifié');
    }

    public function paypalCancel(Order $order)
    {
        return redirect()->route('checkout.payment', $order)
                        ->with('error', 'Paiement PayPal annulé');
    }

    public function paypalNotify(Request $request, Order $order)
    {
        // Vérification IPN PayPal
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
        }

        $req = 'cmd=_notify-validate';
        foreach ($myPost as $key => $value) {
            $value = urlencode($value);
            $req .= "&$key=$value";
        }

        $ch = curl_init('https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

        $res = curl_exec($ch);

        if (!$res) {
            \Log::error('PayPal IPN Error: ' . curl_error($ch));
        }

        curl_close($ch);

        if (strcmp($res, "VERIFIED") == 0) {
            if ($request->input('payment_status') == 'Completed') {
                $order->update([
                    'payment_status' => 'completed',
                    'payment_method' => 'paypal',
                    'status' => 'processing'
                ]);
            }
        }

        return response('OK', 200);
    }
}
