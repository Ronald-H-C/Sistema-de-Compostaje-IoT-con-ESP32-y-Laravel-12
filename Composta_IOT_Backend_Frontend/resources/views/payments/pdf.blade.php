<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Recibo de Pago - {{ $receiptCode }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            font-size: 11px; /* Reducido un poco el tamaño base */
            line-height: 1.4;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        /* --- 1. Encabezado --- */
        .header {
            background-color: #2d3748; /* gris oscuro */
            color: #ffffff;
            padding: 20px 24px; /* Un poco más compacto */
        }
        .header-flex {
            display: table;
            width: 100%;
        }
        .header-left {
            display: table-cell;
            vertical-align: middle;
            width: 60%;
        }
        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 40%;
        }
        .header .logo img {
            max-height: 45px; /* Logo más pequeño */
            width: auto;
            vertical-align: middle;
        }
        .header .recibo-title {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 4px 0;
        }
        .header p {
            margin: 2px 0 0;
            font-size: 11px;
            color: #cbd5e0; /* gris claro */
        }

        /* --- 2. Contenido Principal --- */
        .main {
            padding: 24px 30px;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-col {
            display: table-cell;
            width: 48%;
            vertical-align: top;
            padding-right: 4%;
        }
        .info-col:last-child {
            padding-right: 0;
        }
        .info-col h3 {
            font-size: 11px;
            font-weight: bold;
            color: #718096; /* gris medio */
            text-transform: uppercase;
            margin: 0 0 6px 0;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
        }
        .info-col p {
            font-size: 12px;
            margin: 0 0 5px 0;
        }
        .info-col .label {
            font-weight: bold;
            color: #4a5568;
        }

        /* --- 3. Tabla de Items --- */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            table-layout: fixed; /* Ayuda a controlar anchos */
        }
        .items-table thead {
            background-color: #f7fafc; /* gris muy claro */
            border-bottom: 1px solid #e2e8f0; /* gris borde */
        }
        .items-table th {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #4a5568; /* gris oscuro */
            padding: 10px;
            text-align: left;
        }
        .items-table th.text-center { text-align: center; }
        .items-table th.text-right { text-align: right; }
        
        .items-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
        }
        .items-table td {
            padding: 10px;
            vertical-align: top;
            font-size: 11px;
        }
        .items-table td .item-name {
            font-weight: bold;
            font-size: 12px;
            margin: 0;
        }
        .items-table td.text-center { text-align: center; }
        .items-table td.text-right { text-align: right; }
        
        /* Anchos de columna del recibo original */
        .items-table th:first-child, .items-table td:first-child { width: 40%; } /* Producto */
        .items-table th:nth-child(2), .items-table td:nth-child(2) { width: 15%; } /* Cantidad */
        .items-table th:nth-child(3), .items-table td:nth-child(3) { width: 20%; } /* Precio Unit. */
        .items-table th:last-child, .items-table td:last-child { width: 25%; } /* Subtotal */


        /* --- 4. Totales --- */
        .totals-wrapper {
            width: 100%;
            text-align: right;
            margin-top: 10px;
        }
        .totals-box {
            display: inline-block;
            width: 50%;
            min-width: 280px;
            margin-left: auto;
        }
        .total-line {
            background-color: #2d3748;
            color: #ffffff;
            padding: 12px 16px;
            border-radius: 8px 8px 0 0;
            display: table;
            width: 100%;
            box-sizing: border-box;
        }
        .total-line-label {
            display: table-cell;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: left;
        }
        .total-line-amount {
            display: table-cell;
            font-size: 18px;
            font-weight: bold;
            text-align: right;
        }
        .literal-line {
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-top: 0;
            padding: 12px 16px;
            border-radius: 0 0 8px 8px;
        }
        .literal-line p {
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            margin: 0;
            text-align: left;
            color: #4a5568;
        }
        
        /* --- 5. Estado y Comprobante --- */
        .status-section {
            margin-top: 20px;
        }
        .status-section h3 {
             font-size: 11px;
            font-weight: bold;
            color: #718096; /* gris medio */
            text-transform: uppercase;
            margin: 0 0 6px 0;
        }
        .comprobante-img {
            max-width: 150px;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        /* Clases de Estado (del recibo original) */
        .estado { 
            display: inline-block; 
            padding: 4px 10px; 
            border-radius: 4px; 
            font-weight: bold; 
            font-size: 10px; 
            text-transform: uppercase;
        }
        .pendiente { background-color: #fef9c3; color: #a16207; } /* Amarillo */
        .aprobado { background-color: #dcfce7; color: #166534; } /* Verde */
        .rechazado { background-color: #fee2e2; color: #991b1b; } /* Rojo */


        /* --- 6. Pie de Página --- */
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 10px;
            color: #a0aec0; /* gris claro */
            border-top: 1px solid #e2e8f0;
            margin-top: 20px;
        }
        .footer p {
            margin: 0 0 4px 0;
        }
        .footer .gracias {
            font-weight: bold;
            color: #718096;
            font-size: 11px;
        }

    </style>
</head>

<body>
    <div class="container">

        <header class="header">
            <div class="header-flex">
                <div class="header-left">
                    <div class="logo">
                        <img src="{{ public_path('logo/compostLogo.png') }}" alt="Logo">
                    </div>
                </div>
                <div class="header-right">
                    <h2 class="recibo-title">RECIBO DE PAGO</h2>
                    <p>N°: {{ $receiptCode }}</p>
                    <p>Fecha: {{ $sale->updated_at->format('d/m/Y') }} | Hora: {{ $sale->updated_at->format('H:i:s') }}</p>
                </div>
            </div>
        </header>

        <main class="main">

            <div class="info-grid">
                <div class="info-col">
                    <h3>DATOS DEL CLIENTE</h3>
                    <p><span class="label">Nombre:</span> {{ $sale->client->name ?? 'N/A' }} {{ $sale->client->firstLastName ?? '' }} {{ $sale->client->secondLastName ?? '' }}</p>
                    <p><span class="label">Correo:</span> {{ $sale->client->email ?? 'N/A' }}</p>
                </div>
                <div class="info-col">
                    <h3>DATOS DEL VENDEDOR</h3>
                    <p><span class="label">Nombre:</span> {{ $sale->user->name ?? 'N/A' }} {{ $sale->user->firstLastName ?? '' }} {{ $sale->user->secondLastName ?? '' }}</p>
                    <p><span class="label">Correo:</span> {{ $sale->user->email ?? 'N/A' }}</p>
                    @if ($sale->user->reference && $sale->user->reference->phone)
                        <p><span class="label">Teléfono:</span> {{ $sale->user->reference->phone }}</p>
                    @endif
                </div>
            </div>

            <h3 style="font-size: 11px; font-weight: bold; color: #718096; text-transform: uppercase; margin: 20px 0 6px 0;">DETALLE DEL PEDIDO</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-right">Precio Unit.</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sale->products as $item)
                        <tr>
                            <td>
                                <p class="item-name">{{ $item->fertilizer->title ?? 'N/A' }}</p>
                            </td>
                            <td class="text-center">{{ $item->amount }}</td>
                            <td class="text-right">Bs {{ number_format($item->price, 2) }}</td>
                            <td class="text-right">Bs {{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No hay productos en esta venta.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            
            
            <div class="info-grid status-section">
                <div class="info-col">
                    <div class="totals-box">
                        <div class="total-line">
                            <span class="total-line-label">Total Pagado</span>
                            <span class="total-line-amount">Bs {{ number_format($sale->total, 2) }}</span>
                        </div>
                        <div class="literal-line">
                            <p>SON: {{ $montoLiteral ?? '...' }}</p>
                        </div>
                    </div>
                </div>
                
            </div>

            

        </main>

        <footer class="footer">
            <p class="gracias">¡GRACIAS POR SU COMPRA!</p>
            <p>compos.alwaysdata.net</p>
            <p style="margin-top: 10px;">Documento generado automáticamente el {{ now()->format('d/m/Y H:i') }}</p>
        </footer>
    </div>
</body>
</html>