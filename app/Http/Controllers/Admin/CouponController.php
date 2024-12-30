<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'nullable|string|max:50|unique:coupons',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'is_active' => 'boolean'
        ]);

        Coupon::create([
            'code' => $request->code ?? strtoupper(Str::random(8)),
            'type' => $request->type,
            'value' => $request->value,
            'min_amount' => $request->min_amount,
            'max_uses' => $request->max_uses,
            'starts_at' => $request->starts_at,
            'expires_at' => $request->expires_at,
            'is_active' => $request->boolean('is_active', true)
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon créé avec succès');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'nullable|string|max:50|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'is_active' => 'boolean'
        ]);

        $coupon->update([
            'code' => $request->code ?? $coupon->code,
            'type' => $request->type,
            'value' => $request->value,
            'min_amount' => $request->min_amount,
            'max_uses' => $request->max_uses,
            'starts_at' => $request->starts_at,
            'expires_at' => $request->expires_at,
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon mis à jour avec succès');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon supprimé avec succès');
    }

    public function updateActive(Coupon $coupon)
    {
        $coupon->update([
            'is_active' => !$coupon->is_active
        ]);

        return response()->json([
            'success' => true,
            'is_active' => $coupon->is_active
        ]);
    }

    public function generateCode()
    {
        return response()->json([
            'code' => strtoupper(Str::random(8))
        ]);
    }

    public function usageHistory(Coupon $coupon)
    {
        $orders = $coupon->orders()->with('user')->latest()->paginate(10);
        return view('admin.coupons.usage', compact('coupon', 'orders'));
    }
}
