<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Supprimer l'utilisateur s'il existe déjà
        User::where('email', 'mcspirou7@gmail.com')->delete();

        // Créer l'administrateur
        User::create([
            'firstname' => 'Admin',
            'lastname' => 'Speroshop',
            'email' => 'mcspirou7@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }
}
