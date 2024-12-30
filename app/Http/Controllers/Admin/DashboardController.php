<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        // Statistiques générales
        $stats = [
            'total_sales' => Order::where('status', 'completed')->sum('total_amount'),
            'total_orders' => Order::count(),
            'total_customers' => User::where('role', 'user')->count(),
            'total_products' => Product::count(),
            'low_stock_products' => Product::where('quantity', '<', 10)->count(),
            'pending_reviews' => Review::where('is_approved', false)->count()
        ];

        // Récupérer les produits avec un stock faible
        $lowStockProducts = Product::where('quantity', '<', 10)
            ->orderBy('quantity', 'asc')
            ->take(5)
            ->get();

        // Commandes récentes
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Avis en attente
        $pendingReviews = Review::with(['user', 'product'])
            ->where('is_approved', false)
            ->latest()
            ->take(5)
            ->get();

        // Statistiques des ventes sur les 7 derniers jours
        $salesData = Order::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'lowStockProducts',
            'recentOrders',
            'pendingReviews',
            'salesData'
        ));
    }
}
