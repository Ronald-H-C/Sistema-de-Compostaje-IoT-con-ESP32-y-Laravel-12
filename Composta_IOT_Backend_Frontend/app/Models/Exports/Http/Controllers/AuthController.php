<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string'
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        $user = Auth::user(); // Obtenemos el usuario autenticado
        
        // Redirección basada en roles usando match() (PHP 8.0+)
        $redirectRoute = match($user->role) {
            'admin' => '/admin/dashboard',
            'user' => '/user/dashboard',
            'client' => '/client/dashboard',
            default => '/dashboard' // Ruta por defecto para roles no especificados
        };

        return redirect()->intended($redirectRoute);
    }

    return back()->withErrors([
        'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
    ])->onlyInput('email');
}

    public function registro(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            //return redirect()->intended('dashboard');
            //redireccionamos segun el rol
            // Redireccionar según el rol
            if (Auth::user()->role === 'admin')
            {
                return redirect()->intended('/admin/dashboard');
            }
            else if (Auth::user()->role === 'user')
            {
                return redirect()->intended('/user/dashboard');
            }
            else
            {
                return redirect()->intended('/guest/dashboard');
            }
        }

        return back()->withErrors(['email' => 'Credenciales inválidas'])->withInput();
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
