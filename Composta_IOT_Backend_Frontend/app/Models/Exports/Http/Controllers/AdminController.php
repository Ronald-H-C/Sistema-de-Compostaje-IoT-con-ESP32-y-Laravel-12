<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     */
    public function index()
    {
        $user = User::all();
        
        return view('admin.gestionUser', compact('user'));
    }

    public function dashboardAdmin()
    {
        // Obtener el usuario autenticado
        $user = Auth::User();
        
        return view('admin.inicio', [
            'userV' => $user
        ]);
    }

    public function dashboardUser()
    {
        // Obtener el usuario autenticado
        $user = Auth::User();
        
        return view('user.inicio', [
            'userV' => $user
        ]);
    }
    public function dashboardAdminG()
    {
    $user = Auth::User();           // Usuario autenticado
    $user1 = User::where('state', 1)->get();          // Todos los usuarios

    return view('admin.gestionUser', [
        'userV' => $user,
        'user1' => $user1
    ]);


    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::User();
        return view('admin.create', ['userV' => $user]);
    }

     public function vistaEditar()
    {
        $user = User::all();
        return view('admin.edit', ['userV' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     */
      public function store(Request $request)
{
    $request->validate([
        'name'          => 'required|string',
        'firstLastName' => 'required|string',
        'username'      => 'required|string',
        'email'         => 'required|email|unique:users',
        'role'          => 'required|in:admin,user,client',
        'password'      => 'required|min:6',
    ], [
        // 🔹 Mensajes personalizados
        'name.required'          => 'El campo Nombre es obligatorio.',
        'name.string'            => 'El nombre debe ser un texto válido.',

        'firstLastName.required' => 'El campo Primer Apellido es obligatorio.',
        'firstLastName.string'   => 'El primer apellido debe ser un texto válido.',

        'username.required'      => 'El campo Nombre de Usuario es obligatorio.',
        'username.string'        => 'El nombre de usuario debe ser un texto válido.',

        'email.required'         => 'El campo Correo Electrónico es obligatorio.',
        'email.email'            => 'Debe ingresar un correo electrónico válido.',
        'email.unique'           => 'Este correo ya está registrado. Intente con otro.',

        'role.required'          => 'Debe seleccionar un rol para el usuario.',
        'role.in'                => 'El rol seleccionado no es válido.',

        'password.required'      => 'Debe ingresar una contraseña.',
        'password.min'           => 'La contraseña debe tener al menos 6 caracteres.',
    ]);

    try {
        DB::beginTransaction();

        if ($request->role == 'user')
        {
            $user = User::create([
            'name'          => $request->name,
            'firstLastName' => $request->firstLastName,
            'secondLastName'=> $request->secondLastName ?? '',
            'username'      => $request->username,
            'email'         => $request->email,
            'role'          => $request->role,
            'password'      => bcrypt($request->password),
            'remember_token' => Str::random(16),
        ]);
        }
        else{
             $user = User::create([
            'name'          => $request->name,
            'firstLastName' => $request->firstLastName,
            'secondLastName'=> $request->secondLastName ?? '',
            'username'      => $request->username,
            'email'         => $request->email,
            'role'          => $request->role,
            'password'      => bcrypt($request->password),
        ]);
        }
       

        // 👉 Asignar plan si es "user"
        if ($user->role === 'user') {
            UserPlan::create([
                'idUser'     => $user->id,
                'idPlan'     => 1,
                'started_at' => now(),
                'expires_at' => now()->addDays(90),
                'active'     => 1
            ]);
        }

        DB::commit();
        return redirect()->route('gU')->with('success', 'Usuario creado correctamente.');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error al crear usuario: '.$e->getMessage());
        return redirect()->route('gU')->with('error', 'Error al crear el usuario: ' . $e->getMessage());
    }
}




    public function store1(Request $request)
    {
         $request->validate([
        'name' => 'required|string|max:255',
        'firstLastName' => 'required|string|max:255',
        'secondLastName' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:6',
    ]);

    User::create([
        'name' => $request->name,
        'firstLastName' => $request->firstLastName,
        'secondLastName' => $request->secondLastName,
        'username' => $request->username,
        'email' => $request->email,
        'role' => 'client',
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('login')->with('success', 'Usuario creado.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $userV = Auth::User();
        return view('admin.edit', compact('user', 'userV'));
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, $id)
{
    // Buscar usuario o lanzar 404 si no existe
    $user = User::findOrFail($id);

    // Validación de los campos con reglas y mensajes personalizados
    $request->validate([
        'name'          => 'required|string|max:255',
        'firstLastName' => 'nullable|string|max:255',
        'secondLastName'=> 'nullable|string|max:255',
        'username'      => "nullable|string|max:255|unique:users,username,{$id}",
        'email'         => "required|email|max:255|unique:users,email,{$id}",
        'role'          => 'required|in:admin,user,client',
    ], [
        // 🔹 Mensajes personalizados
        'name.required'          => 'El campo Nombre es obligatorio.',
        'name.string'            => 'El nombre debe ser un texto válido.',
        'name.max'               => 'El nombre no puede exceder 255 caracteres.',

        'firstLastName.string'   => 'El primer apellido debe ser un texto válido.',
        'firstLastName.max'      => 'El primer apellido no puede exceder 255 caracteres.',

        'secondLastName.string'  => 'El segundo apellido debe ser un texto válido.',
        'secondLastName.max'     => 'El segundo apellido no puede exceder 255 caracteres.',

        'username.string'        => 'El nombre de usuario debe ser un texto válido.',
        'username.max'           => 'El nombre de usuario no puede exceder 255 caracteres.',
        'username.unique'        => 'Este nombre de usuario ya está en uso.',

        'email.required'         => 'El correo electrónico es obligatorio.',
        'email.email'            => 'Debe ingresar un correo electrónico válido.',
        'email.max'              => 'El correo electrónico no puede exceder 255 caracteres.',
        'email.unique'           => 'Este correo electrónico ya está registrado.',

        'role.required'          => 'Debe seleccionar un rol.',
        'role.in'                => 'El rol seleccionado no es válido.',
    ]);

    // Actualización de valores
    $user->update([
        'name'          => $request->name,
        'firstLastName' => $request->firstLastName ?? '',
        'secondLastName'=> $request->secondLastName ?? '',
        'username'      => $request->username ?? $user->username,
        'email'         => $request->email,
        'role'          => $request->role,
        'updated_at'    => now(),
        'updated_by'    => auth()->id(),
    ]);

    // Redireccionar con mensaje de éxito
    return redirect()->route('gU')->with('success', 'Usuario actualizado correctamente.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    // Buscar usuario o lanzar 404 si no existe
    $user = User::findOrFail($id);

    // Marcar como eliminado (state = 0)
    $user->update(['state' => 0]);

    // Redireccionar con mensaje de éxito
    return redirect()->route('gU')->with('success', 'Usuario eliminado.');
}

    public function cambiarContrasenia(Request $request)
    {
        $userV = Auth::User();
        return view('admin.cambiarContrasenia', compact('userV'));
    }
    public function actualizarContrasenia(Request $request)
    {
        function validarContrasenia($contrasenia) {
        return (
            strlen($contrasenia) >= 8 &&
            preg_match('/[a-z]/', $contrasenia) &&       // minúscula
            preg_match('/[A-Z]/', $contrasenia) &&       // mayúscula
            preg_match('/\d/', $contrasenia) &&          // número
            preg_match('/[\W_]/', $contrasenia)          // carácter especial
        );
        }
        $usuario = Auth::user();
        $newC = $request->input('Ncontra');
        $confirm = $request->input('Ccontra');
        $oldC = $request->input('Acontra');
        $oldCH = bcrypt($oldC);
        $newCH = bcrypt($newC);

        if (Hash::check($oldC, $usuario->password) && $newC == $confirm){
            if(validarContrasenia($newC))
            {
                // Actualizar la contraseña en la base de datos
            DB::table('users')
                ->where('id', $usuario->id)
                ->update(['password' => $newCH]);

            return redirect()->back()->with('success', 'Contraseña actualizada con éxito');
            }
            else
            {
                return redirect()->back()->with('error', 'La nueva contraseña debe tener al menos 8 caracteres, una minuscula, mayuscula, numero y caracter especial');
            }
            
        } else {
            return redirect()->back()->with('error', 'La contraseña actual es incorrecta');
        }
    }

}
