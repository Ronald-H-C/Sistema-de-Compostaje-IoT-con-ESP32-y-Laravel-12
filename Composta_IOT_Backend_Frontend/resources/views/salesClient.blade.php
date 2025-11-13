@extends('layouts.app')

@section('title', 'Contáctanos - Compostero IoT')

@section('content')


<div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen">
    
    <h1 class="text-3xl font-extrabold text-center mb-8 text-gray-800 tracking-tight">
        Historial de <span class="text-green-600">Ventas y Recibos</span>
    </h1>

    <div class="overflow-x-auto rounded-lg shadow-lg bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            
            {{-- Encabezado de la Tabla --}}
            <thead class="bg-green-600">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                        #
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider">
                        Monto Total
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider">
                        Método de Pago
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
                    Asumimos que la variable $sales (en plural) es la que se envía 
                    desde el controlador, y la iteramos como $sale (singular).
                --}}

                @forelse($sales as $sale)
                    <tr class="bg-white hover:bg-gray-50 transition-colors">
                        
                        {{-- 1. Numeración --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">
                            {{ $loop->iteration }}
                        </td>

                        {{-- 3. Monto Total --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right font-semibold">
                            Bs. {{ number_format($sale->total ?? 0, 2) }}
                        </td>

                        {{-- 4. Método de Pago --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-center">
                            {{ $sale->pay ?? 'N/A' }}
                        </td>

                        {{-- 5. Fecha de Envío (de la solicitud de pago) --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            {{ \Carbon\Carbon::parse($sale->created_at)->format('d/m/Y H:i') }}
                        </td>

                        {{-- 6. Recibo / Acción (Lógica de estados) --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                            
                            @if($sale->state == 1)
                                {{-- APROBADO: Botón de descarga --}}
                                <a href="{{ route('user.sale.receipt', $sale->id) }}" {{-- Ajusta esta ruta --}}
                                   target="_blank" 
                                   class="inline-flex items-center bg-orange-500 text-white text-xs font-semibold py-1 px-3 rounded-full shadow-md hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-2 transition-all">
                                    
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                         <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                     Descargar
                                </a>
                            
                            @elseif ($sale->state == 0)
                                {{-- PENDIENTE: Texto de espera --}}
                                <span class="text-xs text-yellow-600 font-semibold italic">En espera de aprobación</span>
                            
                            @else
                                {{-- OTRO ESTADO: (Rechazado, Expirado, etc.) --}}
                                <span class="text-xs text-red-500 italic">Pago Rechazado</span>
                            @endif

                        </td>
                    </tr>
                
                @empty
                    {{-- Esto se muestra si $sales está vacío --}}
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            No tienes un historial de ventas registrado.
                        </td>
                    </tr>
                @endforelse
                
            </tbody>
        </table>
    </div>

</div>
@endsection
