<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $user = auth()->user();
        
        return view('account.dashboard', [
            'recentOrders' => $user->orders()->latest()->take(5)->get(),
            'wishlistItems' => $user->wishlistItems()->with('product')->latest()->take(4)->get(),
            'points' => $user->loyalty_points,
            'defaultAddress' => $user->addresses()->where('is_default', true)->first(),
        ]);
    }
}
