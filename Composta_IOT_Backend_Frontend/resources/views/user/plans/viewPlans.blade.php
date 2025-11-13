@extends('user.dashboard') {{-- O el layout que estés usando --}}

@section('content')
<div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen">
    
    <h1 class="text-3xl font-extrabold text-center mb-8 text-gray-800 tracking-tight">
        Mis <span class="text-green-600">Planes Adquiridos</span>
    </h1>

    <div class="overflow-x-auto rounded-lg shadow-lg bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            
            {{-- Encabezado de la Tabla --}}
            <thead class="bg-green-600">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                        Nombre Plan
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider">
                        Publicaciones
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider">
                        Duración
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider">
                        Precio
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider">
                        Fecha de Envío
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider">
                        Recibo
                    </th>
                </tr>
            </thead>
            
            {{-- Cuerpo de la Tabla --}}
            <tbody class="divide-y divide-gray-200">
                
                {{-- 
                    Usamos la variable $change_plans que viene del controlador.
                    El bucle @forelse maneja el caso de que no haya planes.
                --}}

                @if($espera)
                    <tr class="bg-yellow-50 hover:bg-yellow-100 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $espera->plan->name ?? 'Plan no disponible' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-center">
                            {{ $espera->plan->post_limit ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-center">
                            {{ $espera->plan->duration ?? 'N/A' }} Días
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right font-semibold">
                            Bs. {{ number_format($espera->plan->cost ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            {{ \Carbon\Carbon::parse($espera->created_at)->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                            <span class="text-xs text-yellow-600 font-semibold italic">En espera de aprobación</span>
                        </td>
                    </tr>
                @endif
                @forelse($change_plans as $request)
                    <tr class="bg-white hover:bg-gray-50 transition-colors">
                        
                        {{-- 1. Nombre Plan --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{-- Accedemos al plan a través de la relación --}}
                            {{ $request->plan->name ?? 'Plan no disponible' }}
                        </td>

                        {{-- 2. Publicaciones Permitidas --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-center">
                            {{ $request->plan->post_limit ?? 'N/A' }}
                        </td>

                        {{-- 3. Duración --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-center">
                            {{ $request->plan->duration ?? 'N/A' }} Días
                        </td>

                        {{-- 4. Precio --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right font-semibold">
                            Bs. {{ number_format($request->plan->cost ?? 0, 2) }}
                        </td>

                        {{-- 5. Fecha de Envío (de la solicitud) --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            {{ \Carbon\Carbon::parse($request->created_at)->format('d/m/Y H:i') }}
                        </td>

                        {{-- 6. Descarga de Comprobante --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                            {{-- 
                                Como el controlador filtra por 'state' == 1 (Aprobado),
                                este botón siempre se mostrará si hay una imagen.
                            --}}
                            @if($request->active == 1)
                                <a href="{{ route('payment.download', $request->id)}}" 
                                   target="_blank" 
                                   class="inline-flex items-center bg-orange-500 text-white text-xs font-semibold py-1 px-3 rounded-full shadow-md hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition-all">
                                   
                                   <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                     <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                   </svg>
                                    Descargar
                                </a>
                            @elseif ($request->active == 0)
                                <span class="text-xs text-gray-400 italic">Plan expirado</span>
                            @else
                                <span class="text-xs text-gray-400 italic">Esperando aprobación</span>
                            @endif
                        </td>
                    </tr>
                
                @empty
                    {{-- Esto se muestra si $change_plans está vacío --}}
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            No tienes planes aprobados actualmente.
                        </td>
                    </tr>
                @endforelse
                
            </tbody>
        </table>
    </div>
    <div class="text-center mt-4">
    <a href="{{ route('uplans.index') }}" 
       class="inline-flex items-center ">
        
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        
    </a>
</div>

</div>
@endsection