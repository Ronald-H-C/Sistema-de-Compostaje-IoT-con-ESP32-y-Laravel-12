@extends('user.dashboard')

@section('content')
<div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen">

    <!-- Encabezado con botón volver -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">📑 Historial de Reportes de Ventas</h1>
        <a href="{{ route('reportes.ventas') }}" 
           class="flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg shadow transition">
            ⬅ Volver
        </a>
    </div>

    @if($reports->isEmpty())
        <div class="p-4 bg-yellow-100 text-yellow-800 rounded-lg shadow">
            No tienes reportes de ventas generados aún.
        </div>
    @else
        <div class="overflow-x-auto bg-white rounded-lg shadow mb-6">
            <table class="min-w-full border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border">#</th>
                        <th class="px-4 py-2 border">Fecha</th>
                        <th class="px-4 py-2 border">Archivo</th>
                        <th class="px-4 py-2 border">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $index => $report)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($report->registrationDate)->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2 border">{{ basename($report->file_route) }}</td>
                            <td class="px-4 py-2 border text-center space-x-2">
                                <a href="{{ url($report->file_route) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm"
                                   target="_blank">
                                   📥 Descargar
                                </a>
                                <button onclick="verReporte('{{ url($report->file_route) }}')" 
                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-sm">
                                    👁 Ver
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Visualizador PDF -->
        <div id="viewer-container" class="hidden bg-white rounded-lg shadow p-4">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-lg font-semibold text-gray-700">📖 Visualizador de Reporte</h2>
                <button onclick="cerrarViewer()" 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-1 rounded-lg text-sm">
                    ✖ Cerrar
                </button>
            </div>
            <iframe id="pdf-viewer" src="" class="w-full h-[600px] border rounded"></iframe>
        </div>
    @endif
</div>

<script>
    function verReporte(ruta) {
        document.getElementById('viewer-container').classList.remove('hidden');
        document.getElementById('pdf-viewer').src = ruta;
        window.scrollTo({ top: document.getElementById('viewer-container').offsetTop - 100, behavior: 'smooth' });
    }

    function cerrarViewer() {
        document.getElementById('viewer-container').classList.add('hidden');
        document.getElementById('pdf-viewer').src = "";
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>
@endsection
