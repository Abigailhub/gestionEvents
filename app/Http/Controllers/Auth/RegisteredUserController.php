<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{    
    $data = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'role' => ['required', 'string', 'in:participant,createur'],
    ]);

        $data['role'] = $request->input('role', 'participant'); // Utilise le rôle envoyé par le formulaire

        $data['role'] = $request->input('role','participant'); // ou 'createur' selon ton besoin
        $data['role'] = $request->input('role','createur');


    $data['password'] = bcrypt($data['password']); // Hachage du mot de passe

    $user = User::create($data);

    

    Auth::login($user);

    // Redirection selon le rôle
    return redirect()->route($user->role . '.dashboard');
}

}
