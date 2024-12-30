<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $addresses = auth()->user()->addresses;
        return view('account.addresses.index', compact('addresses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'address_complement' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'size:2'],
            'is_default' => ['boolean'],
        ]);

        $address = auth()->user()->addresses()->create($validated);

        if ($request->boolean('is_default')) {
            $address->setAsDefault();
        }

        return redirect()->route('account.addresses.index')
            ->with('success', 'L\'adresse a été ajoutée avec succès.');
    }

    public function edit(Address $address)
    {
        $this->authorize('update', $address);
        return response()->json($address);
    }

    public function update(Request $request, Address $address)
    {
        $this->authorize('update', $address);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'address_complement' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'size:2'],
            'is_default' => ['boolean'],
        ]);

        $address->update($validated);

        if ($request->boolean('is_default')) {
            $address->setAsDefault();
        }

        return redirect()->route('account.addresses.index')
            ->with('success', 'L\'adresse a été mise à jour avec succès.');
    }

    public function destroy(Address $address)
    {
        $this->authorize('delete', $address);

        if ($address->is_default) {
            return back()->with('error', 'Vous ne pouvez pas supprimer l\'adresse par défaut.');
        }

        $address->delete();

        return back()->with('success', 'L\'adresse a été supprimée avec succès.');
    }

    public function setDefault(Address $address)
    {
        $this->authorize('update', $address);

        $address->setAsDefault();

        return back()->with('success', 'L\'adresse a été définie comme adresse par défaut.');
    }
}
