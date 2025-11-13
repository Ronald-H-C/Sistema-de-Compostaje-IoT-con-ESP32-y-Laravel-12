<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Calibri', sans-serif;
            font-size: 12px;
            color: #333;
        }

        h1 {
            text-align: center;
            background-color: #2E7D32;
            color: white;
            padding: 8px;
            border-radius: 5px;
        }

        p {
            text-align: center;
            font-size: 11px;
            color: #555;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #66BB6A;
            color: white;
        }

        .total {
            background-color: #E8F5E9;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            color: #777;
            margin-top: 15px;
        }
    </style>
</head>

<body>

    <h1>📊 Reporte de Ventas</h1>
    <p>Generado el {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <tr>
            <th>FECHA</th>
            <th>CLIENTE</th>
            <th>TOTAL (Bs)</th>
            <th>DETALLES DE PRODUCTOS</th>
        </tr>

        @php $totalGeneral = 0; @endphp
        @foreach($sales as $row)
        @php $totalGeneral += $row->total; @endphp
        <tr>
            <td>{{ \Carbon\Carbon::parse($row->created_at ?? now())->format('d/m/Y') }}</td>
            <td>{{ $row->client_name }}</td>
            <td>{{ number_format($row->total, 2) }}</td>
            <td>{{ $row->details }}</td>
        </tr>
        @endforeach

        <tr class="total">
            <td colspan="2">TOTAL GENERAL DE VENTAS FILTRADAS:</td>
            <td>Bs {{ number_format($totalGeneral, 2) }}</td>
            <td></td>
        </tr>
    </table>

    <div class="footer">
        Generado automáticamente por el sistema - {{ now()->format('d/m/Y H:i') }}
    </div>
</body>

</html>