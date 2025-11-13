<?php

namespace App\Http\Controllers;

use App\Models\Fertilizer;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function ver(Request $request)
    {
        $lat = $request->query('lat');
        $lng = $request->query('lng');

        if (!$lat || !$lng) {
            return redirect()->route('index')->with('error', 'Coordenadas no válidas.');
        }

        $product = Fertilizer::with(['location', 'user'])
            ->whereHas('location', function ($query) use ($lat, $lng) {
                $query->whereRaw('ABS(latitude - ?) < 0.0001 AND ABS(longitude - ?) < 0.0001', [$lat, $lng]);
            })
            ->first();

        return view('map.map', compact('product', 'lat', 'lng'));
    }
}
