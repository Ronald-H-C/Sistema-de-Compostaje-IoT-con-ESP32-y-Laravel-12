@extends('user.dashboard')

@section('title', 'Mi Plan Actual')

@section('content')
<div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen">

    @if (session('success'))
        <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-300 text-green-800 font-medium shadow-sm flex items-center">
            <i class="fas fa-check-circle mr-2 text-green-600"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-300 text-red-800 font-medium shadow-sm flex items-center">
            <i class="fas fa-exclamation-triangle mr-2 text-red-600"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Card principal --}}
    <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-200">

        <h1 class="text-3xl font-semibold text-gray-900 mb-8 text-center flex items-center justify-center">
             <i class="fas fa-user-tag text-green-600 mr-3"></i> Mi Plan de Servicio Actual
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Caja con fondo verde muy claro --}}
            <div class="bg-green-50 p-6 rounded-xl border border-green-200 shadow-sm">
                <h3 class="text-lg font-semibold text-green-800 border-b border-green-200 pb-3 mb-4 flex items-center">
                    <i class="fas fa-user mr-2"></i> DATOS DEL USUARIO
                </h3>
                <div class="space-y-3 text-sm text-gray-700">
                    <p><span class="font-semibold text-gray-800">Nombre:</span> {{ $pago->user->name }} {{ $pago->user->firstLastName }}</p>
                    <p><span class="font-semibold text-gray-800">Correo:</span> {{ $pago->user->email }}</p>
                    @if ($pago->user->username)
                        <p><span class="font-semibold text-gray-800">Usuario:</span> {{ $pago->user->username }}</p>
                    @endif
                </div>
            </div>

            {{-- Caja con fondo verde muy claro --}}
            <div class="bg-green-50 p-6 rounded-xl border border-green-200 shadow-sm">
                <h3 class="text-lg font-semibold text-green-800 border-b border-green-200 pb-3 mb-4 flex items-center">
                   <i class="fas fa-layer-group mr-2"></i> PLAN CONTRATADO
                </h3>
                <div class="space-y-3 text-sm text-gray-700">
                    <p><span class="font-semibold text-gray-800">Nombre del Plan:</span> {{ $pago->plan->name }}</p>
                    <p><span class="font-semibold text-gray-800">Descripción:</span> {{ $pago->plan->description }}</p>
                    <p><span class="font-semibold text-gray-800">Duración:</span> {{ $pago->plan->duration }} días</p>
                    <p><span class="font-semibold text-gray-800">Precio:</span> Bs {{ number_format($pago->plan->cost, 2) }}</p>
                </div>
            </div>
        </div>

        {{-- =================================================== --}}
        {{-- SECCIÓN DE ACCIONES MEJORADA --}}
        {{-- =================================================== --}}
        <div class="mt-10 border-t border-gray-200 pt-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                
                <!-- Col 1: Mensaje de Recibo (en lugar del botón de descarga) -->
                <div class="bg-gray-100 p-4 rounded-lg text-center shadow-sm border border-gray-200">
                    <h4 class="font-semibold text-gray-800 mb-2">Recibo de Pago</h4>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1 text-blue-500"></i>
                        Pronto podrás descargar el recibo posterior a la aprobación.
                    </p>
                </div>
                
                <!-- Col 2: Nuevo Botón de "Adquirir" -->
                <div class="text-center">
                    <a href="{{ route('showPlansAdq') }}"
                       class="inline-flex items-center px-6 py-3 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium shadow-md transition-all duration-200 ease-in-out">
                        <i class="fas fa-store mr-2"></i> Ir
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection