<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email'
        ]);

        // TODO: Implémenter la logique d'inscription à la newsletter
        // Pour l'instant, on redirige simplement avec un message de succès
        return back()->with('success', 'Merci de votre inscription à notre newsletter !');
    }
}
