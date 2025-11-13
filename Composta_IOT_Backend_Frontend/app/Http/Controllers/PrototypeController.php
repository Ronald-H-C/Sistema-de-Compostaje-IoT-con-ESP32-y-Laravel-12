<?php

namespace App\Http\Controllers;

use App\Models\Prototype;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;

class PrototypeController extends Controller
{
    public function index()
    {
        $prototypes = Prototype::where('state', 1)
            ->paginate(10);
        
        $usuarios = User::where('state', 1)
        ->where('role', 'user')
        ->get(); 
        
        return view('admin.prototype.index', [
            'prototypes' => $prototypes,
            'usuarios'   => $usuarios
        ]);
    }

    public function create()
    {
        $usuarios = User::where('state', 1)
        ->where('role', 'user')
        ->get();
        return view('admin.prototype.create', ['usuarios' => $usuarios]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            
            'idUser' => [
                'nullable', // 'nullable' permite que se envíe vacío
                'numeric',
                Rule::unique('prototypes', 'idUser') 
            ],
            
            'name' => 'required|string|max:100',
            // ...puedes añadir más validaciones si las necesitas

        ], [
            // ¡MENSAJE AÑADIDO!
            'idUser.unique' => 'Este usuario ya está asignado a otro compostador.'
        ]);

        $validatedData['code'] = $this->generateUniqueComposterCode();

        // 2. Creamos el compostador usando solo los datos validados + el código
        Prototype::create($validatedData);

        return redirect()->route('compost')->with('success', '✅ Compostador registrado correctamente.');
    }

    public function desactive(Prototype $prototype)
    {
        /*
        if ($prototype) {
            $prototype->state = 0; // 0 = Inactivo (Soft Delete)
            $prototype->save();
            
            // Mensaje actualizado para ser más claro
            return back()->with('success', 'Compostador enviado a la papelera');
        }*/
        $prototype->delete();
        return back()->with('error', 'Compostador eliminado');
    }

    // Función para tu nueva ruta POST (asignar usuario)
    public function assignUser(Request $request)
    {
        // 2. AÑADIMOS LA VALIDACIÓN
        $validatedData = $request->validate([
            'prototype_id' => 'required|exists:prototypes,id',
            'user_id' => [
                'nullable',
                Rule::unique('prototypes', 'idUser')
                    ->ignore($request->prototype_id)
            ]
        ], [
            // Mensaje de error personalizado si falla la regla 'unique'
            'user_id.unique' => 'Este usuario ya está asignado a otro prototipo.'
        ]);

        $prototype = Prototype::find($request->prototype_id);

        if ($prototype) {
            // 4. Usamos el 'user_id' del array $validatedData
            $prototype->idUser = $validatedData['user_id']; 
            $prototype->save();
            return back()->with('success', 'Usuario asignado al prototipo');
        }

        // Este return es un 'fallback', aunque la validación 'exists'
        // hace difícil llegar aquí.
        return back()->with('error', 'Error al asignar usuario');
    }

    private function generateUniqueComposterCode()
    {
        $prefix = 'COMP-';
        // Caracteres seguros (sin 0, O, 1, I, L)
        $characters = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
        $charactersLength = strlen($characters);
        $codeLength = 6;
        
        do {
            $randomString = '';
            for ($i = 0; $i < $codeLength; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            $newCode = $prefix . $randomString;
            
            // Verifica si el código ya existe en la BD
            $exists = \App\Models\Prototype::where('code', $newCode)->exists();
            
        } while ($exists); // Repite si el código ya existe
        
        return $newCode;
    }
}
