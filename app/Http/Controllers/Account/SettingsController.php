<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        return view('account.settings');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'birthdate' => ['nullable', 'date', 'before:today'],
            'avatar' => ['nullable', 'image', 'max:1024'], // 1MB max
        ]);

        if ($request->hasFile('avatar')) {
            // Supprime l'ancien avatar s'il existe
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);

        return back()->with('success', 'Vos informations ont été mises à jour avec succès.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Votre mot de passe a été modifié avec succès.');
    }

    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'newsletter' => ['boolean'],
            'order_updates' => ['boolean'],
            'promotional_emails' => ['boolean'],
        ]);

        auth()->user()->update([
            'newsletter' => $request->boolean('newsletter'),
            'order_updates' => $request->boolean('order_updates'),
            'promotional_emails' => $request->boolean('promotional_emails'),
        ]);

        return back()->with('success', 'Vos préférences ont été mises à jour avec succès.');
    }

    public function removeAvatar()
    {
        $user = auth()->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }

        return back()->with('success', 'Votre photo de profil a été supprimée.');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = auth()->user();

        // Supprime l'avatar s'il existe
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        auth()->logout();
        
        $user->delete();

        return redirect()->route('home')
            ->with('success', 'Votre compte a été supprimé avec succès.');
    }
}
