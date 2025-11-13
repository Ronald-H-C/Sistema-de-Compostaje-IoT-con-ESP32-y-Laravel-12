<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Lecturas de Compostaje</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            padding: 6px;
            border: 1px solid #000;
        }
    </style>
</head>

<body>
    <h2>Lecturas de Compostaje</h2>
    <table>
        <thead>
            <tr>
                <th>Temp. DHT (°C)</th>
                <th>Humedad (%)</th>
                <th>MQ-135</th>
                <th>Calidad del Aire</th>
                <th>Temp. DS18B20 (°C)</th>
                <th>Humedad Suelo</th>
                <th>Fecha</th>
                <th>Hora</th>
            </tr>
        </thead>
        <tbody>
            @foreach($readings as $r)
            <tr>
                <td>{{ $r->temperature }}</td>
                <td>{{ $r->humidity }}</td>
                <td>{{ $r->mq135 }}</td>
                <td>{{ $r->air_quality_status }}</td>
                <td>{{ $r->ds18b20_temp }}</td>
                <td>{{ $r->soil_moisture }}</td>
                <td>{{ $r->date }}</td>
                <td>{{ $r->time }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>