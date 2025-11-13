<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use App\Models\Sale;
use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Exports\SalesExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ReadingsExport;
use Maatwebsite\Excel\Facades\Excel;



use ReflectionFunctionAbstract;

class ReportController extends Controller
{
    public function historialV()
    {
        $user = Auth::user();

        $reports = Report::where('type', 'ventas')
            ->where('generate_for', $user->id)
            ->orderByDesc('registrationDate')
            ->get();

        return view('user.report.historialV', compact('reports'));
    }

    public function historialL()
    {
        $user = Auth::user();

        $reports = Report::where('type', 'lecturas')
            ->where('generate_for', $user->id)
            ->orderByDesc('registrationDate')
            ->get();

        return view('user.report.historialL', compact('reports'));
    }

    public function lecturas()
    {
        $user = Auth::user();

        // Obtener todos los registros del usuario autenticado, ordenados por fecha y hora descendente
        $lecturas = Dashboard::where('idUser', $user->id)
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->paginate(50); // Puedes ajustar la paginación si deseas más o menos resultados por página

        return view('user.report.readings', [
            'lecturas' => $lecturas,
            'user' => $user
        ]);
    }

    public function filter(Request $request)
{
    $user = Auth::user();
    $rango = $request->query('rango', 'ultimos'); // Si no hay rango, usar "ultimos" por defecto

    $query = Dashboard::where('idUser', $user->id);

    // Aplicar filtro de fecha según rango
    if ($rango === 'ultimos') {
        $query->orderBy('date', 'desc')
              ->orderBy('time', 'desc')
              ->limit(20);
    } elseif ($rango === 'dia') {
        $query->whereDate('date', Carbon::today());
    } elseif ($rango === 'semana') {
        $query->whereDate('date', '>=', Carbon::now()->subDays(7));
    } elseif ($rango === 'mes') {
        $query->whereDate('date', '>=', Carbon::now()->subDays(30));
    }

    // Obtener resultados ordenados por fecha y hora ascendente
    $todos = $query->orderBy('date')->orderBy('time')->get();

    // Definir intervalo de muestreo según rango
    $intervalo = match ($rango) {
        'dia' => 60,
        'semana' => 420,
        'mes' => 1680,
        default => 1, // ultimos 20 ya vienen filtrados
    };

    // Aplicar muestreo: cada N-ésimo registro
    $lecturas = $todos->filter(function ($item, $index) use ($intervalo) {
        return $index % $intervalo === 0;
    })->values();

    return view('user.report.readings', compact('lecturas', 'rango'));
}


    public function sales(Request $request)
    {
        $user = Auth::user();
        $tipo = $request->query('tipo'); 
        $rango = $request->query('rango'); 

        $query = Sale::where('idUser', $user->id)->where('state', 1);

        
        $query->with([
            'products.fertilizer', // Carga los productos y sus fertilizantes asociados
            'client',              // Carga el cliente de la venta
            // 'user', // Ya estamos filtrando por user, usualmente no necesitas cargarlo de nuevo
            // 'updatedBy' // Carga si necesitas mostrar quién actualizó la venta
        ]);

        // --- Filtro por Rango de Fechas ---
        // Usamos la columna 'date' si la tienes, o 'created_at' si no
        $dateColumn = 'created_at'; // O 'date' si es la fecha de la venta real
        if ($rango) {
            $now = Carbon::now();
            if ($rango === 'dia') {
                $query->whereDate($dateColumn, $now->today());
            } elseif ($rango === 'semana') {
                $query->where($dateColumn, '>=', $now->subDays(7)->startOfDay());
            } elseif ($rango === 'mes') {
                $query->where($dateColumn, '>=', $now->subDays(30)->startOfDay());
            } elseif ($rango === 'ano') {
                $query->whereYear($dateColumn, $now->year);
            }
        }

        // --- Filtro por Tipo de Producto ---
        if ($tipo) {
            // CAMBIO: whereHas ahora usa 'products.fertilizer'
            $query->whereHas('products.fertilizer', function ($q) use ($tipo) {
                $q->where('type', $tipo);
            });
        }

        // --- Ordenar, Paginar y Ejecutar ---
        // Ordenamos por la fecha de la venta (o creación) descendente
        $ventas = $query->orderByDesc($dateColumn) // Ordena por fecha más reciente primero
                        ->paginate(20) // Pagina los resultados
                        ->withQueryString(); // Mantiene los filtros en los enlaces de paginación

        // Pasamos los resultados a la vista
        return view('user.report.sales', compact('ventas'));
    }



    public function downloadPdf(Request $request)
    {
        $user = Auth::user();
        $tipo = $request->query('tipo');
        $rango = $request->query('rango');

        // Empezamos la consulta base
        $query = Sale::where('idUser', $user->id)->where('state', 1);

        // --- Carga Eficiente (Eager Loading) ---
        $query->with([
            'products.fertilizer',
            'client',
        ]);

        // --- Filtro por rango de fechas ---
        $dateColumn = 'created_at';
        if ($rango) {
            $now = Carbon::now('America/La_Paz'); // <-- Especificar zona horaria es buena práctica
            if ($rango === 'dia') {
                $query->whereDate($dateColumn, $now->today());
            } elseif ($rango === 'semana') {
                $query->where($dateColumn, '>=', $now->copy()->subDays(7)->startOfDay()); // Usar copy() para no modificar $now
            } elseif ($rango === 'mes') {
                $query->where($dateColumn, '>=', $now->copy()->subDays(30)->startOfDay());
            } elseif ($rango === 'ano') {
                $query->whereYear($dateColumn, $now->year);
            }
        }

        // --- Filtro por tipo de producto ---
        if ($tipo) {
            $query->whereHas('products.fertilizer', function ($q) use ($tipo) {
                $q->where('type', $tipo);
            });
        }

        // --- Obtener Resultados ---
        $ventas = $query->orderByDesc($dateColumn)->get();

        // --- Generar PDF ---
        $pdf = Pdf::loadView('user.report.sales-pdf', [
            'ventas' => $ventas,
            'rango'  => $rango,
            'tipo'   => $tipo,
        ])->setPaper('A4', 'landscape');

        // --- Guardar PDF en el servidor ---
        $destination = public_path('uploads/reports');
        
        $fileName = 'ventas_' . $user->id . '_' . time() . '.pdf';
        $filePath = $destination . '/' . $fileName;

        try {
            file_put_contents($filePath, $pdf->output());
        } catch (\Exception $e) {
             Log::error("Error al guardar PDF de reporte: " . $e->getMessage());
             return redirect()->back()->with('error', 'No se pudo guardar el archivo PDF en el servidor.');
        }

        // --- Guardar registro en la BD ---
        $relativePath = 'uploads/reports/' . $fileName;
        
        // --- INICIO DE LA MODIFICACIÓN ---
        try {
            Report::create([
                'type'             => 'ventas', // <-- Tipo 'ventas'
                'registrationDate' => Carbon::now('America/La_Paz'), // <-- Usar Carbon::now() y especificar zona horaria
                'file_route'       => $relativePath,
                'generate_for'     => $user->id,
                // 'idReport'      => null, // Comenta o elimina si tu tabla no tiene esta columna o es auto-incremental
            ]);
        } catch (\Exception $e) {
            // Manejar error si falla la creación del reporte en BD
            Log::error("Error al crear registro de reporte en BD: " . $e->getMessage());
            // Opcional: Podrías querer borrar el archivo PDF si falla el registro en BD
            // if (File::exists($filePath)) {
            //     File::delete($filePath);
            // }
            return redirect()->back()->with('error', 'Se generó el PDF, pero hubo un error al registrar el reporte.');
        }
        // --- FIN DE LA MODIFICACIÓN ---


        // --- Retornar descarga ---
        return response()->download($filePath)->deleteFileAfterSend(false);
    }

    public function descargarReporte(Request $request)
{
    $user  = Auth::user();
    $rango = $request->query('rango');

    // 📌 1. Construir consulta según rango
    $query = Dashboard::where('idUser', $user->id);

    if ($rango === 'ultimos') {
        $query->orderBy('date', 'desc')
              ->orderBy('time', 'desc')
              ->limit(20);
    } elseif ($rango === 'dia') {
        $query->whereDate('date', Carbon::today());
    } elseif ($rango === 'semana') {
        $query->whereDate('date', '>=', Carbon::now()->subDays(7));
    } elseif ($rango === 'mes') {
        $query->whereDate('date', '>=', Carbon::now()->subDays(30));
    }

    $datos = $query->get();

    if ($datos->isEmpty()) {
        return back()->with('error', 'No hay datos para el rango seleccionado.');
    }

    // 📌 2. Calcular promedios
    $promedioTempAire   = round($datos->avg('temperature'), 1);
    $promedioHumedad    = round($datos->avg('humidity'), 1);
    $promedioGases      = round($datos->avg('mq135'), 1);
    $promedioTempSuelo  = round($datos->avg('ds18b20_temp'), 1);
    $promedioHumSuelo   = round($datos->avg('soil_moisture'), 1);

    // 📌 3. Evaluación automática
    $analisis = [];
    $analisis[] = ($promedioTempAire >= 20 && $promedioTempAire <= 35)
        ? "Temperatura ambiente óptima."
        : (($promedioTempAire < 20) ? "Temperatura baja." : "Temperatura alta, vigilancia requerida.");
    $analisis[] = ($promedioHumedad >= 40 && $promedioHumedad <= 60)
        ? "Humedad ambiental adecuada."
        : (($promedioHumedad < 40) ? "Ambiente seco, aumentar humedad." : "Humedad alta, riesgo de hongos.");
    $analisis[] = ($promedioGases < 100)
        ? "Calidad del aire buena."
        : (($promedioGases < 300) ? "Calidad del aire aceptable." : "Calidad del aire deficiente.");
    $analisis[] = ($promedioTempSuelo >= 25 && $promedioTempSuelo <= 55)
        ? "Temperatura del suelo ideal."
        : "Temperatura del suelo fuera del rango.";
    $analisis[] = ($promedioHumSuelo >= 40 && $promedioHumSuelo <= 60)
        ? "Humedad del suelo correcta."
        : (($promedioHumSuelo < 40) ? "Suelo seco, riego necesario." : "Suelo muy húmedo.");

    // 📌 4. Momentos críticos
 $umbralTemperatura = 35;
$umbralGases = 400;

$rangosTemperatura = [];
$rangosGases = [];

// ---------- TEMPERATURA ----------
$inicio = null;
$fin = null;

foreach ($datos as $d) {
    if ($d->temperature > $umbralTemperatura) {
        if ($inicio === null) {
            $inicio = $d->date . ' ' . $d->time;
        }
        $fin = $d->date . ' ' . $d->time;
    } else {
        if ($inicio !== null) {
            $rangosTemperatura[] = ['inicio' => $inicio, 'fin' => $fin];
            $inicio = null;
        }
    }
}
if ($inicio !== null) {
    $rangosTemperatura[] = ['inicio' => $inicio, 'fin' => $fin];
}

// ---------- GASES ----------
$inicio = null;
$fin = null;

foreach ($datos as $d) {
    if ($d->mq135 > $umbralGases) {
        if ($inicio === null) {
            $inicio = $d->date . ' ' . $d->time;
        }
        $fin = $d->date . ' ' . $d->time;
    } else {
        if ($inicio !== null) {
            $rangosGases[] = ['inicio' => $inicio, 'fin' => $fin];
            $inicio = null;
        }
    }
}
if ($inicio !== null) {
    $rangosGases[] = ['inicio' => $inicio, 'fin' => $fin];
}

// ---------- MOMENTOS CRÍTICOS ----------
$momentosCriticos = [
    'temperatura' => $rangosTemperatura,
    'gases'       => $rangosGases,
];

// 📌 5. Crear datos para gráficos
$total = $datos->count();

// Definir máximo de puntos que queremos en el gráfico
$maxDatos = 20;

// Calcular el intervalo dinámico
$intervalo = ($total > $maxDatos) ? ceil($total / $maxDatos) : 1;

// Filtrar los datos según el intervalo
$datosFiltrados = $datos->filter(function ($item, $index) use ($intervalo) {
    return $index % $intervalo === 0;
})->values();

// Crear labels y datasets con los datos filtrados
$labels     = $datosFiltrados->pluck('time')->toArray();
$tempsAire  = $datosFiltrados->pluck('temperature')->map(fn($v) => $v ?? 0)->toArray();
$humAire    = $datosFiltrados->pluck('humidity')->map(fn($v) => $v ?? 0)->toArray();
$tempsSuelo = $datosFiltrados->pluck('ds18b20_temp')->map(fn($v) => $v ?? 0)->toArray();
$humSuelo   = $datosFiltrados->pluck('soil_moisture')->map(fn($v) => $v ?? 0)->toArray();


// Última fila para gases individuales
$last = $datos->last();
$gases = [
  'NH₃'     => (float) $last->ammonia,
  'CO₂'     => (float) $last->co2,
  'CO'      => (float) $last->co,
  'Benceno' => (float) $last->benzene,
  'Alcohol' => (float) $last->alcohol,
  'Humo'    => (float) $last->smoke,
];

// Configuración del gráfico de líneas
$lineConfig = [
  'type' => 'line',
  'data' => [
    'labels' => $labels,
    'datasets' => [
      ['label'=>'Temp. Aire (°C)','data'=>$tempsAire,'borderColor'=>'#ef4444','fill'=>false],
      ['label'=>'Humedad Aire (%)','data'=>$humAire,'borderColor'=>'#3b82f6','fill'=>false],
      ['label'=>'Temp. Suelo (°C)','data'=>$tempsSuelo,'borderColor'=>'#f59e0b','fill'=>false],
      ['label'=>'Humedad Suelo (%)','data'=>$humSuelo,'borderColor'=>'#8b5cf6','fill'=>false],
      
    ]
  ],
  'options' => [
    'responsive'=>true,
    'legend'=>['position'=>'bottom'],  // usar directamente legend
    'scales'=>[
        'y'=>['min'=>0,'max'=>100],
        'yRight'=>['min'=>0,'max'=>1000], // eje derecho simplificado
        'x'=>['display'=>true,'scaleLabel'=>['display'=>true,'labelString'=>'Hora']]
    ]
  ]
];

$lineChartUrl = 'https://quickchart.io/chart?width=900&height=400&c=' . urlencode(json_encode($lineConfig));


// Configuración del gráfico de dona
$doughnutConfig = [
  'type'=>'doughnut',
  'data'=>[
    'labels'=>array_keys($gases),
    'datasets'=>[[
      'data'=>array_values($gases),
      'backgroundColor'=>['#4CAF50','#FF9800','#F44336','#2196F3','#9C27B0','#607D8B']
    ]]
  ],
  'options'=>[
    'responsive'=>true,
    'plugins'=>['legend'=>['position'=>'bottom']]
  ]
];
$doughnutChartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode($doughnutConfig));



    // 📌 6. Generar PDF
    $pdf = Pdf::setOptions(['isRemoteEnabled'=>true])
    ->loadView('user.report.readings-pdf', [
        'rango'             => $rango,
        'promedioTempAire'  => $promedioTempAire,
        'promedioHumedad'   => $promedioHumedad,
        'promedioGases'     => $promedioGases,
        'promedioTempSuelo' => $promedioTempSuelo,
        'promedioHumSuelo'  => $promedioHumSuelo,
        'analisis'          => $analisis,
        'momentosCriticos'  => $momentosCriticos,
        'lineChartUrl'      => $lineChartUrl,
        'doughnutChartUrl'  => $doughnutChartUrl,
    ]);


    // 📌 7. Preparar carpeta para guardar PDF
    $destination = $_SERVER['DOCUMENT_ROOT'] . '/uploads/reports';
    if (!file_exists($destination)) {
        mkdir($destination, 0777, true);
    }

    // 📌 8. Guardar PDF en servidor
    $fileName = 'reporte_lecturas_' . time() . '.pdf';
    $filePath = $destination . '/' . $fileName;
    file_put_contents($filePath, $pdf->output());

    // 📌 9. Guardar registro en la base de datos
    $relativePath = 'uploads/reports/' . $fileName;
    Report::create([
        'type'             => 'lecturas',
        'registrationDate' => Carbon::now(),
        'file_route'       => $relativePath,
        'generate_for'     => $user->id,
    ]);

    // 📌 10. Descargar PDF
    return response()->download($filePath)->deleteFileAfterSend(false);
}


    public function exportPDF(Request $request)
    {
        $idUser = $request->query('idUser');

        $readings = DB::table('readings')
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
            ->where('idUser', $idUser)
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $pdf = PDF::loadView('pdf.readings', ['readings' => $readings]);
        return $pdf->download('lecturas_compostaje.pdf');
    }

    public function exportXLSX(Request $request)
    {
        $idUser = $request->query('idUser');

        $readings = DB::table('readings')
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
            ->where('idUser', $idUser)
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        return Excel::download(new ReadingsExport($readings), 'lecturas_compostaje.xlsx');
    }

    public function exportSalesPDF(Request $request)
    {
        $idUser = $request->query('idUser');
        $from   = $request->query('from'); // yyyy-MM-dd
        $to     = $request->query('to');   // yyyy-MM-dd

        DB::statement('SET SESSION group_concat_max_len = 100000');

        $sql = "
        WITH t AS (
            SELECT idClient, SUM(total) AS total_cliente
            FROM sales
            WHERE idUser = ? AND date >= ? AND date < DATE_ADD(?, INTERVAL 1 DAY)
            GROUP BY idClient
        ),
        q AS (
            SELECT s.idClient, d.idFertilizer, SUM(d.amout) AS qty
            FROM sales s
            JOIN details d ON d.idSale = s.id
            WHERE s.idUser = ? AND s.date >= ? AND s.date < DATE_ADD(?, INTERVAL 1 DAY)
            GROUP BY s.idClient, d.idFertilizer
        )
        SELECT
            c.name AS client_name,
            t.total_cliente AS total,
            GROUP_CONCAT(CONCAT(f.type, ' (', q.qty, ' unidades)')
                         ORDER BY f.type SEPARATOR ' • ') AS details
        FROM q
        JOIN users c       ON c.id = q.idClient
        JOIN fertilizers f ON f.id = q.idFertilizer
        JOIN t             ON t.idClient = q.idClient
        GROUP BY q.idClient, c.name, t.total_cliente
        ORDER BY c.name;
    ";

        $rows = DB::select($sql, [$idUser, $from, $to, $idUser, $from, $to]);

        $pdf = Pdf::loadView('pdf.sales', [
            'sales' => $rows,   // => en la vista usa $sales
            'from'  => $from,
            'to'    => $to,
        ]);

        return $pdf->download("ventas_compostaje_{$from}_a_{$to}.pdf");
    }

    public function exportSalesXLSX(Request $request)
    {
        $idUser = $request->query('idUser');
        $from   = $request->query('from');
        $to     = $request->query('to');

        DB::statement('SET SESSION group_concat_max_len = 100000');

        $sql = "
        WITH t AS (
            SELECT idClient, SUM(total) AS total_cliente
            FROM sales
            WHERE idUser = ? AND date >= ? AND date < DATE_ADD(?, INTERVAL 1 DAY)
            GROUP BY idClient
        ),
        q AS (
            SELECT s.idClient, d.idFertilizer, SUM(d.amout) AS qty
            FROM sales s
            JOIN details d ON d.idSale = s.id
            WHERE s.idUser = ? AND s.date >= ? AND s.date < DATE_ADD(?, INTERVAL 1 DAY)
            GROUP BY s.idClient, d.idFertilizer
        )
        SELECT
            c.name AS client_name,
            t.total_cliente AS total,
            GROUP_CONCAT(CONCAT(f.type, ' (', q.qty, ' unidades)')
                         ORDER BY f.type SEPARATOR ' • ') AS details
        FROM q
        JOIN users c       ON c.id = q.idClient
        JOIN fertilizers f ON f.id = q.idFertilizer
        JOIN t             ON t.idClient = q.idClient
        GROUP BY q.idClient, c.name, t.total_cliente
        ORDER BY c.name;
    ";

        $rows = collect(DB::select($sql, [$idUser, $from, $to, $idUser, $from, $to]));

        // SalesExport ya espera una vista 'excel.sales'
        return Excel::download(new SalesExport($rows), "ventas_compostaje_{$from}_a_{$to}.xlsx");
    }
}
