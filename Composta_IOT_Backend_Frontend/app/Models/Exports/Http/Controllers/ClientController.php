<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ClientController extends Controller
{
    
    public function registro(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'firstLastName' => 'required|string|max:255',
        'secondLastName' => 'nullable|string|max:255',
        'username' => 'required|string|max:255|unique:users',
        'email' => 'required|email|unique:users',
        'password' => [
            'required',
            'string',
            'min:8',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).+$/',
            'confirmed' // <-- compara con password_confirmation
        ],
    ], [
        // Mensajes personalizados
        'name.required' => 'El nombre es obligatorio.',
        'name.string' => 'El nombre debe ser una cadena de texto.',
        'name.max' => 'El nombre no debe exceder los 255 caracteres.',

        'firstLastName.required' => 'El primer apellido es obligatorio.',
        'firstLastName.string' => 'El primer apellido debe ser una cadena de texto.',
        'firstLastName.max' => 'El primer apellido no debe exceder los 255 caracteres.',

        'secondLastName.string' => 'El segundo apellido debe ser una cadena de texto.',
        'secondLastName.max' => 'El segundo apellido no debe exceder los 255 caracteres.',

        'username.required' => 'El nombre de usuario es obligatorio.',
        'username.string' => 'El nombre de usuario debe ser una cadena de texto.',
        'username.max' => 'El nombre de usuario no debe exceder los 255 caracteres.',
        'username.unique' => 'Este nombre de usuario ya está en uso.',

        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'El correo electrónico debe ser válido.',
        'email.unique' => 'Este correo electrónico ya está registrado.',

        'password.required' => 'La contraseña es obligatoria.',
        'password.string' => 'La contraseña debe ser una cadena de texto.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.regex' => 'La contraseña debe incluir al menos una letra mayúscula, una minúscula, un número y un carácter especial.',
        'password.confirmed' => 'La confirmación de la contraseña no coincide.',
    ]);

    // Crear usuario
    $user = User::create([
        'name' => $request->name,
        'firstLastName' => $request->firstLastName,
        'secondLastName' => $request->secondLastName,
        'username' => $request->username,
        'email' => $request->email,
        'role' => 'client',
        'state' => 1,
        'password' => bcrypt($request->password),
    ]);

   

    return redirect()->route('registro')->with('success', 'Usuario creado correctamente.');
}

}
