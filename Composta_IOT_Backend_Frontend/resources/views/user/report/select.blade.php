@extends('user.dashboard')

@section('content')
<div id="layoutSidenav_content" class="flex flex-col items-center justify-center min-h-[70vh] p-6 bg-gray-50">

    <h1 class="text-4xl font-bold mb-12 text-green-700">Seleccione un Reporte</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 max-w-4xl w-full">

        <!-- Tarjeta Reporte Lecturas Compostaje -->
        <a href="{{ route('reportes.lecturas') }}" 
           class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition p-8 flex flex-col items-center cursor-pointer
                  border-4 border-green-500 hover:border-green-700">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-24 w-24 mb-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M3 10h1l1 2h2l1-2h2l1 2h2l1-2h2l1 2h1" />
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M16 21v-6a4 4 0 00-8 0v6" />
            </svg>
            <h2 class="text-2xl font-semibold text-green-700 mb-2">Lecturas de Compostaje</h2>
            <p class="text-gray-600 text-center">Visualice los datos de sensores y condiciones ambientales del compostaje.</p>
        </a>

        <!-- Tarjeta Reporte Ventas de Composta -->
        <a href="{{ route('reportes.ventas') }}" 
           class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition p-8 flex flex-col items-center cursor-pointer
                  border-4 border-yellow-500 hover:border-yellow-700">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-24 w-24 mb-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M3 10h18M3 6h18M3 14h18M3 18h18" />
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M12 14v7m-6-7v7m12-7v7" />
            </svg>
            <h2 class="text-2xl font-semibold text-yellow-700 mb-2">Ventas de Composta</h2>
            <p class="text-gray-600 text-center">Revise las estadísticas y reportes sobre las ventas de composta.</p>
        </a>

    </div>
</div>
@endsection
