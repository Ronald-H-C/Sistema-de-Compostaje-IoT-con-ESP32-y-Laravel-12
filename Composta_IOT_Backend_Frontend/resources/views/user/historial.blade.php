@extends('user.dashboard')
@section('content')
<div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen">

    <!-- Botón para volver atrás -->
    <div class="mb-4">
        <a href="{{route('gUs')}}" class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold transition duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
            </svg>
            Volver
        </a>
    </div>

    <h1 class="text-3xl font-bold text-center mb-6 text-gray-800">Historial de Lecturas - IoT</h1>

    @if($datos->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md text-sm">
                <thead class="bg-gray-700 text-white">
                    <tr>
                        <th class="p-2">Temp. DHT (°C)</th>
                        <th class="p-2">Humedad (%)</th>
                        <th class="p-2">MQ-135</th>
                        <th class="p-2">Calidad del Aire</th>
                        <th class="p-2">Temp. DS18B20 (°C)</th>
                        <th class="p-2">Humedad Suelo</th>
                        <th class="p-2">Fecha</th>
                        <th class="p-2">Hora</th>
                    </tr>
                </thead>
                <tbody id="tablaLecturas" class="divide-y divide-gray-200 text-center">
                    @foreach ($datos as $row)
                        <tr>
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
        <div class="flex justify-center mt-6">
            {{ $datos->links() }}
        </div>
    @else
        <p class="text-center text-gray-600">No se encontraron registros.</p>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    setInterval(async () => {
        try {
            const res = await fetch("{{ route('dashboard.datosJson') }}");
            const data = await res.json();

            const tbody = document.getElementById("tablaLecturas");
            if (!tbody) {
                console.error("No se encontró el elemento con id 'tablaLecturas'");
                return;
            }

            tbody.innerHTML = ""; // Limpiar tabla

            data.reverse().forEach(row => {
                tbody.innerHTML += `
                    <tr>
                        <td class="p-2">${row.temperature}</td>
                        <td class="p-2">${row.humidity}</td>
                        <td class="p-2">${row.mq135}</td>
                        <td class="p-2">${row.air_quality_status}</td>
                        <td class="p-2">${row.ds18b20_temp}</td>
                        <td class="p-2">${row.soil_moisture}</td>
                        <td class="p-2">${row.date}</td>
                        <td class="p-2">${row.time}</td>
                    </tr>
                `;
            });

        } catch (e) {
            console.error("Error al actualizar datos:", e);
        }
    }, 10000);
});
</script>
@endsection
