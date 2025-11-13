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

   
        $latestReadings = Dashboard::where('idUser', $userId)
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->limit(12)
            ->get();

       
        if ($latestReadings->count() < 12) {
            return response()->json([
                'success' => true, 
                'fase' => $this->getFaseName($typeAlert),
                'type_alert' => $typeAlert,
                'alerts' => [],
                'message' => 'No hay suficientes lecturas recientes (se requieren 12) para generar alertas de tendencia.'
            ]);
        }

        $alerts = [];
        $fase = "Desconocida"; 

    
        switch ($typeAlert) {
            case 1: // Etapa inicial
                $tempMin = 20; $tempMax = 40;
                $humMin = 40; $humMax = 80;
                $soilMin = 35; $soilMax = 70;
                $gasMax = 500;
                $fase = "Inicial";
                break;

            case 2: // Etapa media
                $tempMin = 45; $tempMax = 65;
                $humMin = 40; $humMax = 70;
                $soilMin = 30; $soilMax = 60;
                $gasMax = 800;
                $fase = "Media";
                break;

            case 3: // Etapa final
                $tempMin = 25; $tempMax = 35;
                $humMin = 30; $humMax = 60;
                $soilMin = 25; $soilMax = 50;
                $gasMax = 500;
                $fase = "Final";
                break;

            default: // Default thresholds if type is unknown
                $tempMin = 20; $tempMax = 60;
                $humMin = 30; $humMax = 80;
                $soilMin = 30; $soilMax = 70;
                $gasMax = 700;
                // $fase remains "Desconocida"
        }


        if ($latestReadings->every(fn($r) => $r->ds18b20_temp < $tempMin)) {
            $avgTemp = number_format($latestReadings->avg('ds18b20_temp'), 1);
            $alerts[] = [
                "mensaje" => "🔸 Temperatura consistentemente baja ({$avgTemp}°C promedio en el último minuto).",
                "recomendacion" => "Verificar humedad y aireación. Considerar añadir material rico en nitrógeno si la actividad microbiana es baja.",
            ];
        } elseif ($latestReadings->every(fn($r) => $r->ds18b20_temp > $tempMax)) {
            $avgTemp = number_format($latestReadings->avg('ds18b20_temp'), 1);
            $alerts[] = [
                "mensaje" => "⚠️ Temperatura consistentemente alta ({$avgTemp}°C promedio > {$tempMax}°C en el último minuto).",
                "recomendacion" => "Remueva el material URGENTEMENTE para airear y bajar la temperatura.",
            ];
        }

        if ($latestReadings->every(fn($r) => $r->humidity < $humMin)) {
             $avgHum = number_format($latestReadings->avg('humidity'), 1);
            $alerts[] = [
                "mensaje" => "🔸 Humedad del aire consistentemente baja ({$avgHum}% promedio en el último minuto).",
                "recomendacion" => "Puede indicar ambiente seco. Asegurar que el compostaje tenga tapa o protección.",
            ];
        } elseif ($latestReadings->every(fn($r) => $r->humidity > $humMax)) {
             $avgHum = number_format($latestReadings->avg('humidity'), 1);
            $alerts[] = [
                "mensaje" => "🔸 Humedad del aire consistentemente alta ({$avgHum}% promedio en el último minuto).",
                "recomendacion" => "Asegurar buena ventilación en el área del compostador.",
            ];
        }

      
        if ($latestReadings->every(fn($r) => $r->soil_moisture < $soilMin)) {
             $avgSoil = number_format($latestReadings->avg('soil_moisture'), 1);
            $alerts[] = [
                "mensaje" => "⚠️ Material consistentemente seco ({$avgSoil}% promedio en el último minuto).",
                "recomendacion" => "Agregar agua o residuos húmedos de forma uniforme.",
            ];
        } elseif ($latestReadings->every(fn($r) => $r->soil_moisture > $soilMax)) {
             $avgSoil = number_format($latestReadings->avg('soil_moisture'), 1);
            $alerts[] = [
                "mensaje" => "🔸 Material consistentemente húmedo ({$avgSoil}% promedio en el último minuto).",
                "recomendacion" => "Remover el material y agregar material seco (hojas, cartón) para absorber exceso y mejorar aireación.",
            ];
        }

        
        if ($latestReadings->every(fn($r) => $r->mq135 > $gasMax)) {
             $avgGas = number_format($latestReadings->avg('mq135'), 0); // Gases usually integers
            $alerts[] = [
                "mensaje" => "⚠️ Exceso de gases consistentemente alto ({$avgGas} promedio en el último minuto).",
                "recomendacion" => "Remueva y airee el compost inmediatamente para reducir gases y olores.",
            ];
        }

       
        if ($latestReadings->every(fn($r) => $r->temperature < 15)) {
             $avgAirTemp = number_format($latestReadings->avg('temperature'), 1);
            $alerts[] = [
                "mensaje" => "⚠️ Temperatura ambiental consistentemente baja ({$avgAirTemp}°C promedio en el último minuto).",
                "recomendacion" => "Proteger el compost del frío extremo si es posible.",
            ];
        } elseif ($latestReadings->every(fn($r) => $r->temperature > 45)) {
             $avgAirTemp = number_format($latestReadings->avg('temperature'), 1);
             $alerts[] = [
                "mensaje" => "⚠️ Temperatura ambiental consistentemente alta ({$avgAirTemp}°C promedio en el último minuto).",
                "recomendacion" => "Proteger el compost del sol directo o calor extremo si es posible.",
            ];
        }


        return response()->json([
            'success' => true,
            'fase' => $fase,
            'type_alert' => $typeAlert,
            'alerts' => $alerts 
        ]);
    }

}
