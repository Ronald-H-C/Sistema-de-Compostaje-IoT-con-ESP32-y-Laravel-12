<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Compostaje</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 20px;
            background-color: #f4f6f8;
            color: #333;
        }

        header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 3px solid #4CAF50;
            margin-bottom: 30px;
        }

        header h1 {
            color: #4CAF50;
            margin: 0;
            font-size: 28px;
        }

        header p {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }

        section {
            background: #fff;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 10px;
            border: 1px solid #ddd;
            box-shadow: 0 3px 6px rgba(0,0,0,0.05);
        }

        h2, h3 {
            color: #4CAF50;
            margin-top: 0;
        }

        h2 {
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        table th {
            background-color: #f1fdf3;
            color: #4CAF50;
        }

        .analysis-box {
            background-color: #f1fdf3;
            border-left: 5px solid #4CAF50;
            padding: 12px 15px;
            margin-bottom: 12px;
            font-style: italic;
            border-radius: 5px;
        }

        .critical-box {
            background-color: #ffe5e5;
            border-left: 5px solid #e53935;
            padding: 12px 15px;
            margin-bottom: 12px;
            font-weight: bold;
            border-radius: 5px;
        }

        img.chart {
            max-width: 100%;
            display: block;
            margin: 15px auto;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        footer {
            text-align: center;
            font-size: 12px;
            color: #999;
            margin-top: 40px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
    </style>
</head>
<body>

<header>
    <h1>Reporte de Compostaje - {{ ucfirst($rango) }}</h1>
    <p>Resumen del estado del compostaje según las mediciones del rango seleccionado</p>
</header>

<section>
    <h2>Gráfico de Lecturas</h2>
    <img src="{{ $lineChartUrl }}" alt="Gráfico de lecturas" class="chart">
</section>

<section>
    <h2>Distribución de Gases Detectados</h2>
    <img src="{{ $doughnutChartUrl }}" alt="Gráfico de gases" class="chart" style="max-width:500px;">
</section>

<section>
    <h2>Promedios Generales</h2>
    <table>
        <thead>
            <tr>
                <th>Parámetro</th>
                <th>Valor Promedio</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Temperatura aire</td><td>{{ $promedioTempAire }} °C</td></tr>
            <tr><td>Humedad aire</td><td>{{ $promedioHumedad }} %</td></tr>
            <tr><td>Nivel MQ-135</td><td>{{ $promedioGases }}</td></tr>
            <tr><td>Temperatura suelo</td><td>{{ $promedioTempSuelo }} °C</td></tr>
            <tr><td>Humedad suelo</td><td>{{ $promedioHumSuelo }} %</td></tr>
        </tbody>
    </table>
</section>

<section>
    <h2>Análisis</h2>
    @foreach($analisis as $linea)
        <div class="analysis-box">{{ $linea }}</div>
    @endforeach
</section>

<section>
    <h2>Momentos Críticos</h2>

    {{-- Temperaturas --}}
    @if(count($momentosCriticos['temperatura']) > 0)
        <div class="critical-box">
            <strong>Temperaturas fuera de rango:</strong>
            <ul>
    @foreach($momentosCriticos['temperatura'] as $rango)
        <li>
          
            {{ \Carbon\Carbon::parse($rango['inicio'])->format('Y-m-d H:i:s') }}
            -
           
            {{ \Carbon\Carbon::parse($rango['fin'])->format('Y-m-d H:i:s') }}
        </li>
    @endforeach
</ul>
        </div>
    @endif

    {{-- Gases --}}
    @if(count($momentosCriticos['gases']) > 0)
        <div class="critical-box">
            <strong>Niveles de gases fuera de rango:</strong>
            <ul>
                @foreach($momentosCriticos['gases'] as $rango)
                    <li>
                      
                        {{ \Carbon\Carbon::parse($rango['inicio'])->format('Y-m-d H:i:s') }}
                        -
                     
                        {{ \Carbon\Carbon::parse($rango['fin'])->format('Y-m-d H:i:s') }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</section>


<footer>
    Generado automáticamente por el sistema de monitoreo de compostaje - {{ now()->format('d/m/Y H:i') }}
</footer>

</body>
</html>
