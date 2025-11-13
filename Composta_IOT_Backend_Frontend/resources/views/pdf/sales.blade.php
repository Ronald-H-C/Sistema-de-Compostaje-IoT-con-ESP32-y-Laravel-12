<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 25px;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #2E7D32;
            margin-bottom: 5px;
        }

        p {
            text-align: center;
            font-size: 12px;
            color: #555;
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            font-size: 12px;
        }

        th {
            background-color: #66BB6A;
            color: white;
            text-align: center;
        }

        td {
            vertical-align: top;
        }

        .total-row td {
            font-weight: bold;
            background-color: #E8F5E9;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .footer {
            text-align: center;
            font-size: 11px;
            margin-top: 15px;
            color: #777;
        }
    </style>
</head>

<body>
    <h2>Reporte de Ventas</h2>
    <p><strong>Generado el:</strong> {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>FECHA</th>
                <th>CLIENTE</th>
                <th>TOTAL VENTA</th>
                <th>DETALLES DE PRODUCTOS</th>
            </tr>
        </thead>
        <tbody>
            @php $totalGeneral = 0; @endphp
            @foreach ($sales as $sale)
            <tr>
                <td>{{ \Carbon\Carbon::parse($sale->fecha)->format('d/m/Y') }}</td>
                <td>{{ $sale->cliente }}</td>
                <td>{{ $sale->total_venta }}</td>
                <td class="left" style="white-space: pre-line;">{{ $sale->detalles_productos }}</td>
            </tr>
            @php
            $totalGeneral += (float) str_replace(['Bs', ' '], '', $sale->total_venta);
            @endphp
            @endforeach
            <tr class="total-row">
                <td colspan="2">TOTAL GENERAL DE VENTAS FILTRADAS:</td>
                <td colspan="2" class="right">BS {{ number_format($totalGeneral, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>