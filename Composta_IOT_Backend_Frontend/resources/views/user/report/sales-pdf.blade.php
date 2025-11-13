<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>
    {{-- Usar DejaVu Sans es bueno para caracteres especiales en PDF --}}
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #333; }
        h2 { text-align: center; margin-bottom: 15px; border-bottom: 1px solid #ccc; padding-bottom: 5px;}
        p { margin: 0 0 8px 0; font-size: 11px;}
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #aaa; }
        th { background: #f0f0f0; font-weight: bold; padding: 8px; font-size: 10px; text-transform: uppercase;}
        td { padding: 6px; text-align: center; vertical-align: top; font-size: 10px;}
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .detalles ul { margin: 0; padding-left: 15px; list-style: none;}
        .detalles li { margin-bottom: 3px; }
        tfoot th { background: #e0e0e0; font-size: 11px;}
    </style>
</head>
<body>
    <h2>Reporte de Ventas</h2>
    <p><strong>Generado el:</strong> {{ now()->format('d/m/Y H:i') }}</p>
    {{-- Mostrar filtros aplicados --}}
    @if($rango) <p><strong>Rango:</strong> {{ ucfirst($rango) }}</p> @endif
    @if($tipo) <p><strong>Tipo Producto:</strong> {{ ucfirst(str_replace('_', ' ', $tipo)) }}</p> @endif

    <table>
        <thead>
            <tr>
                <th style="width:15%;">Fecha</th>
                <th style="width:20%;">Cliente</th>
                <th style="width:15%;" class="text-right">Total Venta</th>
                <th style="width:50%;">Detalles de Productos</th>
            </tr>
        </thead>
        <tbody>
            @php $totalGeneral = 0; @endphp
            @forelse ($ventas as $venta)
                @php $totalGeneral += $venta->total; @endphp
                <tr>
                    {{-- Usa la columna de fecha que ordenaste en el controlador --}}
                    <td>{{ $venta->created_at ? \Carbon\Carbon::parse($venta->created_at)->format('d/m/Y') : '-' }}</td>
                    <td class="text-left">{{ $venta->client->name ?? 'N/A' }}</td>
                    <td class="text-right">Bs {{ number_format($venta->total, 2) }}</td>
                    <td class="detalles text-left">
                        {{-- CAMBIO: Iterar sobre $venta->products --}}
                        <ul>
                            @foreach ($venta->products as $item) {{-- $item es PaymentProduct --}}
                                <li>
                                    {{ $item->fertilizer->title ?? 'Producto no disponible' }}
                                    ({{ $item->amount }} {{-- Corregido: amount --}} uds) -
                                    Bs {{ number_format($item->subtotal, 2) }}
                                </li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No se encontraron ventas con los filtros aplicados.</td>
                </tr>
            @endforelse
        </tbody>
        @if($ventas->count() > 0)
            <tfoot>
                <tr>
                    <th colspan="2" class="text-right">Total General de Ventas Filtradas:</th>
                    <th class="text-right">Bs {{ number_format($totalGeneral, 2) }}</th>
                    <th></th> {{-- Celda vacía para alinear --}}
                </tr>
            </tfoot>
        @endif
    </table>
</body>
</html>