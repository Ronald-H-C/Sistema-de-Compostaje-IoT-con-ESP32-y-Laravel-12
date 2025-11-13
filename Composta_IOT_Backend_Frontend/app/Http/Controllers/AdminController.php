<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
        // 1. VALIDACIÓN (SIN PASSWORD)
        $request->validate([
            'name'          => 'required|string',
            'firstLastName' => 'required|string',
            'username'      => 'required|string', // Nota: considera añadir 'unique:users' aquí también
            'email'         => 'required|email|unique:users',
            'role'          => 'required|in:admin,user,client',
        ], [
            // 🔹 Mensajes personalizados (sin los de password)
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
        ]);

        // 2. GENERACIÓN DE CONTRASEÑA
        // Genera un número aleatorio de 8 dígitos (de 10000000 a 99999999)
        // Lo convertimos a string, ya que las contraseñas deben tratarse como texto
        $generatedPassword = (string) rand(10000000, 99999999);

        try {
            DB::beginTransaction();

            // 3. REFACTOR (PREPARAMOS LOS DATOS DEL USUARIO)
            // Preparamos los datos base que son comunes para todos los roles
            $userData = [
                'name'           => $request->name,
                'firstLastName'  => $request->firstLastName,
                'secondLastName' => $request->secondLastName ?? '',
                'username'       => $request->username,
                'email'          => $request->email,
                'role'           => $request->role,
                'password'       => bcrypt($generatedPassword), // Usamos la contraseña generada
            ];

            // Añadimos campos específicos solo si el rol es 'user'
            if ($request->role == 'user') {
                $userData['readings_token'] = Str::random(16);
            }

            // Creamos el usuario una sola vez
            $user = User::create($userData);

            // 👉 Asignar plan si es "user" (tu lógica original)
            if ($user->role === 'user') {
                UserPlan::create([
                    'idUser'     => $user->id,
                    'idPlan'     => 1,
                    'started_at' => now(),
                    'expires_at' => now()->addDays(30),
                    'active'     => 1
                ]);
            }

            DB::commit();

            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp-compos.alwaysdata.net';
                $mail->SMTPAuth = true;
                $mail->Username = 'compos@alwaysdata.net';
                $mail->Password = 'compostajeiot&2025';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;
                $mail->setFrom('compos@alwaysdata.net', 'CompostajeIoT');
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';


                // 1. Asunto corregido para coincidir con el contenido
                $mail->addAddress($request->email);
                $mail->Subject = "¡Bienvenido/a a CompostajeIoT! Tus datos de acceso";

                // 2. Cuerpo del email con plantilla HTML profesional
                $mail->Body = "
                <!DOCTYPE html>
                <html lang='es'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Bienvenido a CompostajeIoT</title>
                </head>
                <body style='margin: 0; padding: 0; font-family: Arial, sans-serif; line-height: 1.6; background-color: #f4f4f4;'>
                    
                    <table width='100%' border='0' cellpadding='0' cellspacing='0' style='background-color: #f4f4f4;'>
                        <tr>
                            <td align='center' style='padding: 20px 0;'>
                                
                                <table width='90%' border='0' cellpadding='0' cellspacing='0' style='max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05);'>
                                    
                                    <tr>
                                        <td align='center' style='padding: 30px 20px; background-color: #2E7D32; color: #ffffff;'>
                                            <h1 style='margin: 0; font-size: 28px; font-weight: bold;'>CompostajeIoT</h1>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style='padding: 40px 30px;'>
                                            <h2 style='font-size: 22px; color: #333333; margin-top: 0;'>¡Bienvenido/a, {$request->name}!</h2>
                                            <p style='font-size: 16px; color: #555555; margin-bottom: 25px;'>
                                                Tu cuenta ha sido creada exitosamente. Ya puedes acceder a la plataforma con los siguientes datos:
                                            </p>
                                            
                                            <div style='background-color: #f9f9f9; border: 1px dashed #cccccc; padding: 20px; border-radius: 5px; margin-bottom: 25px; font-size: 16px;'>
                                                <strong>Usuario:</strong> {$request->email}<br>
                                                <strong>Contraseña Temporal:</strong> <span style='font-size: 18px; font-weight: bold; color: #D32F2F;'>{$generatedPassword}</span>
                                            </div>

                                            <p style='font-size: 16px; color: #555555; margin-bottom: 30px;'>
                                                Por tu seguridad, te recomendamos cambiar esta contraseña inmediatamente después de iniciar sesión por primera vez.
                                            </p>

                                            <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                                <tr>
                                                    <td align='center'>
                                                        <a href='http://tu-sitio-web.com/login' target='_blank' style='background-color: #2E7D32; color: #ffffff; padding: 14px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px; display: inline-block;'>
                                                            Iniciar Sesión Ahora
                                                        </a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td align='center' style='padding: 20px 30px; background-color: #333333; color: #aaaaaa; font-size: 12px;'>
                                            <p style='margin: 0;'>&copy; " . date('Y') . " CompostajeIoT. Todos los derechos reservados.</p>
                                            <p style='margin: 5px 0 0 0;'>Este es un correo automático, por favor no respondas.</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </body>
                </html>
                ";
                
                $mail->send();

            } catch (Exception $e) {
                // Si el email falla, no detenemos el proceso, pero lo registramos.
                // El usuario ya fue creado y el admin vio la clave en pantalla.
                Log::error("Error al enviar email de bienvenida: {$mail->ErrorInfo}");
            }


            return redirect()->route('gU')->with('success', 
                "✅ Usuario creado correctamente"
            );

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear usuario: '.$e->getMessage());
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
