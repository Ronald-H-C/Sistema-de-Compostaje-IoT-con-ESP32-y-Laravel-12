<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Dashboard;
use App\Models\UserReference;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Vista principal del dashboard
    public function datos()
    {
        $user = Auth::user();
        $references = UserReference::where('idUser', $user->id)->first();

        $data = DB::table('readings')
            ->select(
                'temperature',
                'humidity',
                'mq135',
                'air_quality_status',
                'ammonia',
                'co2',
                'co',
                'benzene',
                'alcohol',
                'smoke',
                'ds18b20_temp',
                'soil_moisture',
                'status',
                'date',
                'time'
            )
            ->where('idUser', $user->id)
            ->orderByDesc('id')
            ->limit(20)
            ->get()
            ->reverse()
            ->values();

        return view('user.inicio', [
            'data' => $data,
            'user' => $user,
            'references' => $references
        ]);
    }

    // API: Obtener datos en JSON
    public function datosJson()
    {
        $user = Auth::user();

        $data = DB::table('readings')
            ->select(
                'temperature',
                'humidity',
                'mq135',
                'air_quality_status',
                'ammonia',
                'co2',
                'co',
                'benzene',
                'alcohol',
                'smoke',
                'ds18b20_temp',
                'soil_moisture',
                'status',
                'date',
                'time'
            )
            ->where('idUser', $user->id)
            ->orderByDesc('id')
            ->limit(20)
            ->get()
            ->reverse()
            ->values();

        return response()->json($data);
    }

    // Paginación de historial
    public function paginacionDatos(Request $request)
    {
        $user = Auth::user();
        $limit = 20;
        $page = $request->input('page', 1);

        $datos = DB::table('readings')
            ->select(
                'temperature',
                'humidity',
                'mq135',
                'air_quality_status',
                'ds18b20_temp',
                'soil_moisture',
                'date',
                'time'
            )
            ->where('idUser', $user->id)
            ->orderByDesc('id')
            ->paginate($limit, ['*'], 'page', $page);

        return view('user.historial', compact('datos'));
    }

   
    // public function store(Request $request)
    // {
    //     // Fecha y hora local
    //     date_default_timezone_set("America/La_Paz");

    //     $validated = $request->validate([
    //         'idUser' => 'required|integer',
    //         'temperature' => 'required|numeric',
    //         'humidity' => 'required|numeric',
    //         'status' => 'required|string',
    //         'mq135' => 'required|numeric',
    //         'air_quality_status' => 'required|string',
    //         'ammonia' => 'required|numeric',
    //         'co2' => 'required|numeric',
    //         'co' => 'required|numeric',
    //         'benzene' => 'required|numeric',
    //         'alcohol' => 'required|numeric',
    //         'smoke' => 'required|numeric',
    //         'ds18b20_temp' => 'required|numeric',
    //         'soil_moisture' => 'required|numeric',
    //     ]);

    //     $validated['date'] = date("Y-m-d");
    //     $validated['time'] = date("H:i:s");

    //     try {
    //         Dashboard::create($validated);
    //         return response()->json(["success" => true], 201);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             "error" => "Error en la base de datos: " . $e->getMessage()
    //         ], 500);
    //     }}
        
        public function store(Request $request)
    {
        
        $idUser = $request->input('idUser');
        $receivedToken = $request->input('api_token');

 
        if (empty($idUser) || empty($receivedToken)) {
            return response()->json(["error" => "No autorizado. Credenciales incompletas."], 401);
        }


        $user = User::find($idUser);


        if (!$user) {
            return response()->json(["error" => "No autorizado. Usuario no válido."], 401);
        }


        $expectedToken = $user->remember_token;

        if (empty($expectedToken)) {
            return response()->json(["error" => "No autorizado. El usuario no tiene un token de API configurado."], 401);
        }


        if (!hash_equals($expectedToken, $receivedToken)) {
            return response()->json(["error" => "No autorizado. Token no válido."], 401);
        }

    
        date_default_timezone_set("America/La_Paz");


        $validated = $request->validate([
            'idUser' => 'required|integer', 
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
            'status' => 'required|string',
            'mq135' => 'required|numeric',
            'air_quality_status' => 'required|string',
            'ammonia' => 'required|numeric',
            'co2' => 'required|numeric',
            'co' => 'required|numeric',
            'benzene' => 'required|numeric',
            'alcohol' => 'required|numeric',
            'smoke' => 'required|numeric',
            'ds18b20_temp' => 'required|numeric',
            'soil_moisture' => 'required|numeric',
        ]);

       
        $validated['date'] = date("Y-m-d");
        $validated['time'] = date("H:i:s");

       
        try {
            Dashboard::create($validated);
            return response()->json(["success" => true], 201);
        } catch (\Exception $e) {
            return response()->json([
                "error" => "Error en la base de datos: " . $e->getMessage()
            ], 500);
        }
    }

    // Ruta de prueba para verificar conexión
    public function prueba()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Datos recibidos correctamente'
        ]);
    }

    public function getTypeAlert()
    {
        
    }
}
