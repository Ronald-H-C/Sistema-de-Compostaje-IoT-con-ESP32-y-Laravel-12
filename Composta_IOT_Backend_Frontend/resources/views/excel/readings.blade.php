<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h1 {
            text-align: center;
            color: #2E7D32;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        .section-title {
            background-color: #E8F5E9;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <h1>📊 Reporte de Compostaje - {{ strtoupper($range) }}</h1>

    <h3>Promedios Generales</h3>
    <table>
        <tr>
            <th>Parámetro</th>
            <th>Valor Promedio</th>
        </tr>
        <tr>
            <td>Temperatura Aire (°C)</td>
            <td>{{ $promedioTempAire }}</td>
        </tr>
        <tr>
            <td>Humedad Aire (%)</td>
            <td>{{ $promedioHumedad }}</td>
        </tr>
        <tr>
            <td>Nivel MQ-135</td>
            <td>{{ $promedioGases }}</td>
        </tr>
        <tr>
            <td>Temperatura Suelo (°C)</td>
            <td>{{ $promedioTempSuelo }}</td>
        </tr>
        <tr>
            <td>Humedad Suelo (%)</td>
            <td>{{ $promedioHumSuelo }}</td>
        </tr>
    </table>

    <h3>Análisis</h3>
    <table>
        <tr>
            <th>Resultado</th>
        </tr>
        @foreach($analisis as $linea)
        <tr>
            <td>{{ $linea }}</td>
        </tr>
        @endforeach
    </table>

    <h3>Momentos Críticos</h3>
    <table>
        <tr>
            <th>Tipo</th>
            <th>Inicio</th>
            <th>Fin</th>
        </tr>
        @foreach($momentosCriticos['temperatura'] as $temp)
        <tr>
            <td>Temperatura Alta</td>
            <td>{{ $temp['inicio'] }}</td>
            <td>{{ $temp['fin'] }}</td>
        </tr>
        @endforeach
        @foreach($momentosCriticos['gases'] as $gas)
        <tr>
            <td>Gases Altos</td>
            <td>{{ $gas['inicio'] }}</td>
            <td>{{ $gas['fin'] }}</td>
        </tr>
        @endforeach
        @if(empty($momentosCriticos['temperatura']) && empty($momentosCriticos['gases']))
        <tr>
            <td colspan="3">Sin momentos críticos detectados</td>
        </tr>
        @endif
    </table>

    <h3>Lecturas Recientes</h3>
    <table>
        <tr>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Temp Aire</th>
            <th>Humedad Aire</th>
            <th>MQ-135</th>
            <th>Temp Suelo</th>
            <th>Humedad Suelo</th>
        </tr>
        @foreach($datos as $d)
        <tr>
            <td>{{ $d->date }}</td>
            <td>{{ $d->time }}</td>
            <td>{{ $d->temperature }}</td>
            <td>{{ $d->humidity }}</td>
            <td>{{ $d->mq135 }}</td>
            <td>{{ $d->ds18b20_temp }}</td>
            <td>{{ $d->soil_moisture }}</td>
        </tr>
        @endforeach
    </table>

    <p style="text-align:center; color:#777;">Generado automáticamente - {{ now()->format('d/m/Y H:i') }}</p>
</body>

</html>