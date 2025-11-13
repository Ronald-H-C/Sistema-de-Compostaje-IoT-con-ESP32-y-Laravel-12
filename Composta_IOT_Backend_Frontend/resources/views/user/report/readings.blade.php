@extends('user.dashboard')

@section('content')
<div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen">

    <!-- Título y botones -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <h1 class="text-4xl font-extrabold text-gray-800">📊 Reporte de Lecturas IoT</h1>
        <div class="flex flex-wrap gap-2">
            <!-- Botón historial (si aplica) -->
            <a href="{{ route('historialL') }}" 
               class="flex items-center gap-2 px-6 py-3 bg-red-600 text-white rounded-lg font-medium shadow-md hover:bg-red-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Ver Historial
            </a>

            <!-- Botón descargar PDF -->
            <a href="{{ route('reportes.descargar', ['rango' => request('rango', 'ultimos')]) }}" 
               class="flex items-center gap-2 px-6 py-3 bg-red-600 text-white rounded-lg font-medium shadow-md hover:bg-red-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Generar Reporte y Descargar PDF
            </a>

            <!-- Botón volver -->
            <a href="{{ route('select') }}" 
               class="flex items-center gap-2 px-5 py-3 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium shadow-sm transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Volver
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <form method="GET" action="{{ route('reportes.lecturas') }}" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Filtrar por rango</label>
            <select name="rango" onchange="this.form.submit()" 
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <option value="ultimos" @if(request('rango')=='ultimos') selected @endif>Últimos 20 datos</option>
                <option value="dia" @if(request('rango')=='dia') selected @endif>Hoy</option>
                <option value="semana" @if(request('rango')=='semana') selected @endif>Últimos 7 días</option>
                <option value="mes" @if(request('rango')=='mes') selected @endif>Últimos 30 días</option>
            </select>
        </div>
    </form>

    <!-- Tabla de lecturas -->
    @if($lecturas->count())
    <div class="overflow-x-auto shadow-lg rounded-lg bg-white mb-6">
        <table class="min-w-full divide-y divide-gray-200 table-auto">
            <thead class="bg-green-600 text-white">
                <tr>
                    <th class="p-3 text-left">🌡 Temp. DHT (°C)</th>
                    <th class="p-3 text-left">💧 Humedad (%)</th>
                    <th class="p-3 text-left">MQ-135</th>
                    <th class="p-3 text-left">🌬 Calidad del Aire</th>
                    <th class="p-3 text-left">Temp. DS18B20 (°C)</th>
                    <th class="p-3 text-left">Humedad Suelo</th>
                    <th class="p-3 text-left">📅 Fecha</th>
                    <th class="p-3 text-left">⏰ Hora</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($lecturas as $row)
                <tr class="hover:bg-green-50 transition">
                    <td class="p-2">{{ $row->temperature }}</td>
                    <td class="p-2">{{ $row->humidity }}</td>
                    <td class="p-2">{{ $row->mq135 }}</td>
                    <td class="p-2">{{ $row->air_quality_status }}</td>
                    <td class="p-2">{{ $row->ds18b20_temp }}</td>
                    <td class="p-2">{{ $row->soil_moisture }}</td>
                    <td class="p-2">{{ $row->date }}</td>
                    <td class="p-2">{{ $row->time }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Paginación -->


    @else
        <p class="text-center text-gray-500 mt-10 text-lg">No se encontraron lecturas registradas.</p>
    @endif

</div>
@endsection
