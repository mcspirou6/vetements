<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit()
    {
        return view('admin.settings.edit');
    }

    public function update(Request $request)
    {
        // Vous pouvez ajouter ici la logique pour mettre à jour les paramètres
        // comme les préférences de notification, le thème, etc.

        return back()->with('success', 'Paramètres mis à jour avec succès.');
    }
}
