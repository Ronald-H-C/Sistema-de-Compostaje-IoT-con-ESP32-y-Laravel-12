@extends('user.dashboard')

@section('content')
{{-- Fondo en tono café/tierra muy claro --}}
<div id="layoutSidenav_content" class="p-6 min-h-screen" style="background-color: #f5f5f4;">

    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <h1 class="text-4xl font-extrabold text-stone-800">🛒 Reporte de Ventas</h1>
        
        <div class="flex flex-wrap gap-3">
            {{-- Botón Descargar PDF (Script al final) --}}
            <button type="button" id="btnDescargarPdf"
                    class="flex items-center gap-2 px-5 py-3 bg-green-700 text-white rounded-lg font-medium shadow-md hover:bg-green-800 transition-colors duration-300">
                <i class="fas fa-file-pdf fa-fw"></i>
                <span>Generar PDF</span>
            </button>
            
            {{-- Botón Historial (Asegúrate que la ruta 'historialV' exista) --}}
            @if(Route::has('historialV'))
            <a href="{{ route('historialV') }}"
               class="flex items-center gap-2 px-5 py-3 bg-stone-600 text-white rounded-lg font-medium shadow-md hover:bg-stone-700 transition-colors duration-300">
                <i class="fas fa-history fa-fw"></i>
                <span>Ver Historial Completo</span>
            </a>
            @endif

            {{-- Botón Volver (Asegúrate que la ruta 'select' exista) --}}
            @if(Route::has('select'))
            <a href="{{ route('select') }}"
               class="flex items-center gap-2 px-5 py-3 rounded-lg bg-white hover:bg-stone-100 text-stone-700 font-medium shadow-sm border border-stone-300 transition-colors duration-300">
                <i class="fas fa-chevron-left fa-fw"></i>
                <span>Volver</span>
            </a>
            @endif
        </div>
    </div>

    <form method="GET" action="{{ route('reportes.ventas') }}" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div>
            <label for="rango" class="block text-sm font-medium text-stone-700 mb-1">Filtrar por rango</label>
            <select name="rango" id="rango"
                    class="w-full rounded-lg border border-stone-300 px-3 py-2 shadow-sm focus:ring-2 focus:ring-green-600 focus:border-green-600">
                <option value="">Todos los tiempos</option>
                <option value="dia" @if(request('rango')=='dia') selected @endif>Hoy</option>
                <option value="semana" @if(request('rango')=='semana') selected @endif>Últimos 7 días</option>
                <option value="mes" @if(request('rango')=='mes') selected @endif>Últimos 30 días</option>
                <option value="ano" @if(request('rango')=='ano') selected @endif>Este Año</option>
            </select>
        </div>
        <div>
            <label for="tipo" class="block text-sm font-medium text-stone-700 mb-1">Tipo de producto</label>
            <select name="tipo" id="tipo"
                    class="w-full rounded-lg border border-stone-300 px-3 py-2 shadow-sm focus:ring-2 focus:ring-green-600 focus:border-green-600">
                <option value="">Todos los tipos</option>
                <option value="composta" @if(request('tipo')=='composta') selected @endif>Composta</option>
                <option value="humus" @if(request('tipo')=='humus') selected @endif>Humus</option>
                <option value="abono_organico" @if(request('tipo')=='abono_organico') selected @endif>Abono Orgánico</option>
                {{-- Agrega más tipos si los tienes --}}
            </select>
        </div>
        {{-- Botón para aplicar filtros --}}
        <div class="md:col-span-1">
            <button type="submit" class="w-full px-5 py-3 bg-green-700 hover:bg-green-800 text-white rounded-lg font-medium shadow-md transition-colors duration-300">
                <i class="fas fa-filter mr-2"></i> Aplicar Filtros
            </button>
        </div>
        {{-- Enlace para limpiar filtros --}}
        <div class="md:col-span-1 flex items-center justify-center md:justify-start">
            <a href="{{ route('reportes.ventas') }}" class="text-sm text-stone-600 hover:underline">
                <i class="fas fa-broom mr-1"></i> Limpiar filtros
            </a>
        </div>
    </form>

    @if ($ventas->count())
    <div class="overflow-x-auto shadow-xl rounded-lg bg-white mb-6">
        <table id="salesTable" class="min-w-full divide-y divide-gray-200 table-auto text-sm">
            <thead class="bg-green-700 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider cursor-pointer sortable" data-sort="sale-date" data-type="date">
                        📅 Fecha <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider cursor-pointer sortable" data-sort="client-name" data-type="text">
                        👤 Cliente <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider cursor-pointer sortable" data-sort="total-sale" data-type="number">
                        Total Venta <i class="fas fa-sort ml-1"></i>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Detalles de Productos</th>
                </tr>
            </thead>
            <tbody id="salesTableBody" class="divide-y divide-stone-100">
                {{-- No usamos $saleNumber en el bucle PHP, el JS lo manejará --}}
                @foreach ($ventas as $venta)
                    <tr class="hover:bg-stone-50 transition-colors duration-150 sale-row">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-500 font-medium sale-number-cell"></td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-700" data-sale-date="{{ $venta->created_at ? \Carbon\Carbon::parse($venta->created_at)->timestamp : '' }}">
                            {{ $venta->created_at ? $venta->created_at->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-700" data-client-name="{{ Str::lower($venta->client->name ?? '') }}">
                            {{ $venta->client->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-semibold text-stone-800" data-total-sale="{{ $venta->total }}">Bs {{ number_format($venta->total, 2) }}</td>
                        <td class="px-6 py-4">
                            <ul class="list-disc list-inside text-stone-700 space-y-1">
                                @foreach ($venta->products as $item)
                                    @php
                                        $productType = strtolower($item->fertilizer->type ?? '');
                                    @endphp
                                    {{-- Lógica de filtro por tipo (si aplica) --}}
                                    @if(empty(request('tipo')) || request('tipo') == $productType)
                                        <li>
                                            {{ $item->fertilizer->title ?? 'Producto eliminado' }}
                                            ({{ $item->amount }} unidades) -
                                            <span class="font-medium">Bs {{ number_format($item->subtotal, 2) }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $ventas->links() }}
    </div>

    @else
        <div class="col-span-full bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 p-6 rounded-md shadow-sm" role="alert">
            <p class="font-bold text-lg"><i class="fas fa-info-circle mr-2"></i> No se encontraron ventas</p>
            <p>No se encontraron ventas registradas que coincidan con los filtros aplicados.</p>
        </div>
    @endif

</div>

<script>
document.getElementById('btnDescargarPdf').addEventListener('click', function () {
    const rango = document.querySelector('select[name="rango"]').value;
    const tipo = document.querySelector('select[name="tipo"]').value;

    let url = "{{ route('reports.download') }}"; // Asegúrate que esta ruta apunte al controlador de descarga
    let params = [];

    if (rango) params.push(`rango=${encodeURIComponent(rango)}`);
    if (tipo) params.push(`tipo=${encodeURIComponent(tipo)}`);

    if (params.length > 0) {
        url += '?' + params.join('&');
    }

    window.location.href = url;
});

document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.getElementById('salesTableBody');
    const headers = document.querySelectorAll('#salesTable .sortable');
    let currentSortColumn = null;
    let currentSortDirection = 'asc';

    // Función para actualizar los números correlativos
    function updateRowNumbers() {
        const rows = tableBody.querySelectorAll('.sale-row');
        rows.forEach((row, index) => {
            const numberCell = row.querySelector('.sale-number-cell');
            if (numberCell) {
                numberCell.textContent = index + 1;
            }
        });
    }


});
</script>
@endsection