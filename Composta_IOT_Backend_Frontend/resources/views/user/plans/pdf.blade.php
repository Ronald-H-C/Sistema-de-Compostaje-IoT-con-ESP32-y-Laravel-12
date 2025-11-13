<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Pago - {{ $pago->plan->name }}</title>
    <!-- 
      Estilos CSS en línea.
      Esto es mucho más confiable para la generación de PDFs 
      que no pueden cargar scripts externos como Tailwind.
    -->
    <style>
        body {
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
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
            padding: 24px;
        }
        .header-flex {
            display: table;
            width: 100%;
        }
        .header-left {
            display: table-cell;
            vertical-align: middle;
        }
        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0 0;
            font-size: 11px;
            color: #cbd5e0; /* gris claro */
        }
        .header .recibo-title {
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 5px 0;
        }
        .header .recibo-num {
            font-size: 14px;
        }

        /* --- 2. Contenido Principal --- */
        .main {
            padding: 24px 30px;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 24px;
            border-collapse: collapse;
        }
        .info-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .info-col.text-right {
            text-align: right;
        }
        .info-col h3 {
            font-size: 11px;
            font-weight: bold;
            color: #718096; /* gris medio */
            text-transform: uppercase;
            margin: 0 0 4px 0;
        }
        .info-col p,
        .info-col strong {
            font-size: 13px;
            margin: 0 0 4px 0;
        }
        .info-col strong {
            font-weight: bold;
            color: #2d3748;
        }
        .info-col .mt-4 {
            margin-top: 16px;
        }

        /* --- 3. Tabla de Items --- */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .items-table thead {
            background-color: #f7fafc; /* gris muy claro */
            border-bottom: 1px solid #e2e8f0; /* gris borde */
        }
        .items-table th {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #4a5568; /* gris oscuro */
            padding: 12px;
            text-align: left;
        }
        .items-table th.text-center {
            text-align: center;
        }
        .items-table th.text-right {
            text-align: right;
        }
        .items-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
        }
        .items-table td {
            padding: 12px;
            vertical-align: top;
        }
        .items-table td .item-name {
            font-weight: bold;
            font-size: 13px;
            margin: 0 0 2px 0;
        }
        .items-table td .item-desc {
            font-size: 11px;
            color: #718096;
            margin: 0;
        }
        .items-table td.text-center {
            text-align: center;
        }
        .items-table td.text-right {
            text-align: right;
            font-weight: bold;
        }

        /* --- 4. Totales --- */
        .totals-wrapper {
            width: 100%;
            text-align: right;
        }
        .totals-box {
            display: inline-block;
            width: 45%;
            min-width: 250px;
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
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: left;
        }
        .total-line-amount {
            display: table-cell;
            font-size: 20px;
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
            font-size: 11px;
            text-transform: uppercase;
            margin: 0;
            text-align: left;
        }

        /* --- 5. Observaciones --- */
        .observations {
             margin-top: 24px;
        }
        .observations h3 {
            font-size: 11px;
            font-weight: bold;
            color: #718096; /* gris medio */
            text-transform: uppercase;
            margin: 0 0 4px 0;
        }
        .observations-box {
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            font-size: 12px;
        }

        /* --- 6. Pie de Página --- */
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 10px;
            color: #a0aec0; /* gris claro */
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 0 0 4px 0;
        }

    </style>
</head>

<body>
    <div class="container">

        <!-- 1. Encabezado del Recibo -->
        <header class="header">
            <div class="header-flex">
                <div class="header-left">
                    <h1>CompostajeIoT</h1>
                    <p>Villa Israel, Cochabamba</p>
                </div>
                <div class="header-right">
                    <h2 class="recibo-title">RECIBO</h2>
                    <p class="recibo-num">N°: {{ str_pad($pago->id ?? '001', 5, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </header>

        <main class="main">

            <!-- 2. Información del Cliente y Fecha -->
            <div class="info-grid">
                <div class="info-col">
                    <h3>Recibo a:</h3>
                    <strong>{{ $pago->user->name}} {{$pago->user->firstLastName}} {{$pago->user->secondLastName}}</strong>
                    <p>{{ $pago->user->email }}</p>
                    @if ($pago->user->username)
                    <p>Usuario: {{ $pago->user->username }}</p>
                    @endif
                </div>
                <div class="info-col text-right">
                    <h3>Fecha de Emisión:</h3>
                    <p>{{ $pago->updated_at->format('d/m/Y') }}</p>
                    
                    <h3 class="mt-4">Método de Pago:</h3>
                    <p>{{ $pago->pay ?? 'QR' }}</p>
                </div>
            </div>

            <!-- 3. Detalle de Items (Tabla) -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th class="text-center">Duración</th>
                        <th class="text-right">Precio (Bs)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <p class="item-name">{{ $pago->plan->name }}</p>
                            <p class="item-desc">{{ $pago->plan->description }}</p>
                        </td>
                        <td class="text-center">{{ $pago->plan->duration }} días</td>
                        <td class="text-right">
                            {{ number_format($pago->plan->cost, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- 4. Totales y Monto Literal -->
            <div class="totals-wrapper">
                <div class="totals-box">
                    <div class="total-line">
                        <span class="total-line-label">Total</span>
                        <span class="total-line-amount">Bs {{ number_format($pago->plan->cost, 2) }}</span>
                    </div>
                    <div class="literal-line">
                        <!-- 
                          IMPORTANTE: 
                          Debes calcular el monto literal en tu servidor (PHP/Laravel)
                          y pasarlo en una variable aquí.
                        -->
                        <p id="monto-literal">
                            SON: {{ $pago_literal ?? '...' }}
                        </p>
                    </div>
                </div>
            </div>

        </main>

        <footer class="footer">
            <p>Gracias por su preferencia.</p>
            <p>Este documento es un recibo electrónico y no requiere firma.</p>
        </footer>
    </div>
</body>
</html>