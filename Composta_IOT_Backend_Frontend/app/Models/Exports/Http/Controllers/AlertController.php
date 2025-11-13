<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dashboard;
use App\Models\User;

class AlertController extends Controller
{

    public function updateTypeAlert(Request $request)
{
    try {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'type_alert' => 'required|integer|in:1,2,3',
        ]);

        $user = User::find($request->input('user_id'));

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ]);
        }

        $user->type_alert = $request->input('type_alert');
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'actualizado correctamente',
            'data' => [
                'user_id' => $user->id,
                'type_alert' => $user->type_alert
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error interno: ' . $e->getMessage()
        ], 500);
    }
}



    public function getAlerts(Request $request)
{
    $userId = $request->query('idUser');

    if (!$userId) {
        return response()->json([
            'success' => false,
            'message' => 'user_id es requerido'
        ], 400);
    }

    $typeAlert = User::where('id', $userId)->value('type_alert');

    $reading = Dashboard::where('idUser', $userId)
        ->orderBy('date', 'desc')
        ->orderBy('time', 'desc')
        ->first();

    if (!$reading) {
        return response()->json([
            'success' => false,
            'alerts' => [],
            'message' => 'No hay lecturas para este usuario'
        ]);
    }

    $alerts = [];

    // ----------------------------
    // CONFIGURACIÓN POR ETAPA
    // ----------------------------
    switch ($typeAlert) {
        case 1: // Etapa inicial
            $tempMin = 20; $tempMax = 40;
            $humMin = 40; $humMax = 80;
            $soilMin = 35; $soilMax = 70;
            $gasMax = 500;
            $fase = "Inicial";
            break;

        case 2: // Etapa media (alta actividad microbiana)
            $tempMin = 45; $tempMax = 65;
            $humMin = 40; $humMax = 70;
            $soilMin = 30; $soilMax = 60;
            $gasMax = 800;
            $fase = "Media";
            break;

        case 3: // Etapa final (maduración)
            $tempMin = 25; $tempMax = 35;
            $humMin = 30; $humMax = 60;
            $soilMin = 25; $soilMax = 50;
            $gasMax = 500;
            $fase = "Final";
            break;

        default:
            $tempMin = 20; $tempMax = 60;
            $humMin = 30; $humMax = 80;
            $soilMin = 30; $soilMax = 70;
            $gasMax = 700;
            $fase = "Desconocida";
    }

    // ----------------------------
    // VERIFICAR ALERTAS SEGÚN ETAPA + RECOMENDACIONES
    // ----------------------------

    // 🌡️ Temperatura del compost
    if ($reading->ds18b20_temp < $tempMin) {
        $alerts[] = [
            "mensaje" => "🔸 Temperatura baja ({$reading->ds18b20_temp}°C).",
            "recomendacion" => "Humedad del compost baja.",
        ];
    } elseif ($reading->ds18b20_temp > $tempMax) {
        $alerts[] = [
            "mensaje" => "⚠️ Temperatura alta ({$reading->ds18b20_temp}°C > {$tempMax}°C).",
            "recomendacion" => "Remueva el material para airear y evitar que mueran microorganismos beneficiosos.",
        ];
    }

    // 💧 Humedad del aire
    if ($reading->humidity < $humMin) {
        $alerts[] = [
            "mensaje" => "🔸 Humedad del aire baja ({$reading->humidity}%).",
            "recomendacion" => "Agregue un poco de agua o restos húmedos para equilibrar la humedad.",
        ];
    } elseif ($reading->humidity > $humMax) {
        $alerts[] = [
            "mensaje" => "🔸 Humedad del aire alta ({$reading->humidity}%).",
            "recomendacion" => "Agregue material seco (hojas, aserrín) para absorber el exceso de humedad.",
        ];
    }

    // 🌱 Humedad del material
    if ($reading->soil_moisture < $soilMin) {
        $alerts[] = [
            "mensaje" => "⚠️ Material muy seco ({$reading->soil_moisture}%).",
            "recomendacion" => "Agregue agua o residuos húmedos, el compost debe sentirse como una esponja escurrida.",
        ];
    } elseif ($reading->soil_moisture > $soilMax) {
        $alerts[] = [
            "mensaje" => "🔸 Material muy húmedo ({$reading->soil_moisture}%).",
            "recomendacion" => "Remueva el material y agregue hojas secas para mejorar la aireación.",
        ];
    }

    // 💨 Gases (MQ-135)
    if ($reading->mq135 > $gasMax) {
        $alerts[] = [
            "mensaje" => "⚠️ Exceso de gases ({$reading->mq135}).",
            "recomendacion" => "Remueva y airee el compost para reducir la acumulación de gases y olores.",
        ];
    }

    // 🌤️ Temperatura del aire (DHT22)
    if ($reading->temperature < 15 || $reading->temperature > 45) {
        $alerts[] = [
            "mensaje" => "⚠️ Temperatura ambiental fuera de rango ({$reading->temperature}°C).",
            "recomendacion" => "Procure mantener el compost protegido del frío o del sol directo.",
        ];
    }

    // Si no hay alertas, sugerimos mantenimiento
    // if (empty($alerts)) {
    //     $alerts[] = [
    //         "mensaje" => "✅ Compost en condiciones óptimas.",
    //         "recomendacion" => "Mantenga la aireación y controle la humedad cada pocos días.",
    //     ];
    // }

    return response()->json([
        'success' => true,
        'fase' => $fase,
        'type_alert' => $typeAlert,
        'alerts' => $alerts
    ]);
}

}
