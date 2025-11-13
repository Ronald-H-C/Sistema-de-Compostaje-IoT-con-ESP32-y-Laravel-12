@extends('admin.dashboard')

@section('content')
<div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen">

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-300 text-green-800 font-medium shadow-sm flex items-center">
            <i class="fas fa-check-circle mr-2 text-green-600"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-300 text-red-800 font-medium shadow-sm flex items-center">
            <i class="fas fa-exclamation-triangle mr-2 text-red-600"></i> {{ session('error') }}
        </div>
    @endif

    <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-2xl shadow-xl p-8 mb-8 flex flex-col sm:flex-row items-center justify-between gap-6">
    <div class="flex items-center gap-5">
        {{-- Contenedor circular --}}
        <div class="w-20 h-20 flex items-center justify-center bg-white/20 rounded-full shadow-lg border-2 border-white/30 overflow-hidden"> {{-- Añadido overflow-hidden por si acaso --}}
            {{-- Imagen redondeada y ajustada --}}
            <img src="{{ asset('img/imgRonald.png') }}" 
                 alt="Avatar de {{ auth()->user()->username }}" 
                 class="w-full h-full rounded-full object-cover"> {{-- Clases clave añadidas aquí --}}
        </div>
        <div>
            <h1 class="text-3xl font-bold">¡Bienvenido, {{ auth()->user()->username }}!</h1>
            <p class="text-green-100 mt-1 text-base">Panel de Administración.</p>
        </div>
    </div>
    <div class="text-right text-sm text-green-100 hidden sm:block">
        {{-- Ejemplo: Mostrar fecha y hora --}}
        <p>{{ \Carbon\Carbon::now()->translatedFormat('l, d \de F \de Y') }}</p>
        <p>{{ \Carbon\Carbon::now()->format('H:i A') }}</p>
    </div>
</div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <a href="{{ route('plans.index') }}" 
           class="block bg-white rounded-2xl shadow-xl p-6 border border-transparent hover:shadow-2xl hover:border-green-300 transition-all duration-300 ease-in-out group">
            <div class="flex items-center justify-between mb-3">
                 <h3 class="text-xl font-semibold text-gray-900">Gestión de Planes</h3>
                 <i class="fas fa-layer-group text-green-500 text-2xl group-hover:scale-110 transition-transform"></i>
            </div>
            <p class="text-gray-600 text-sm">Administra los planes de suscripción disponibles.</p>
        </a>

         <a href="{{ route('change_plans.index') }}" 
            class="block bg-white rounded-2xl shadow-xl p-6 border border-transparent hover:shadow-2xl hover:border-green-300 transition-all duration-300 ease-in-out group">
             <div class="flex items-center justify-between mb-3">
                 <h3 class="text-xl font-semibold text-gray-900">Comprobantes</h3>
                 <i class="fas fa-file-invoice-dollar text-green-500 text-2xl group-hover:scale-110 transition-transform"></i>
             </div>
             <p class="text-gray-600 text-sm">Revisa y gestiona los comprobantes de pago enviados por los usuarios.</p>
         </a>
        
         <a href="{{ route('contacts.index') }}" 
            class="block bg-white rounded-2xl shadow-xl p-6 border border-transparent hover:shadow-2xl hover:border-green-300 transition-all duration-300 ease-in-out group">
             <div class="flex items-center justify-between mb-3">
                 <h3 class="text-xl font-semibold text-gray-900">Mensajes</h3>
                 <i class="fas fa-envelope-open-text text-green-500 text-2xl group-hover:scale-110 transition-transform"></i>
             </div>
             <p class="text-gray-600 text-sm">Gestiona los mensajes recibidos a través del formulario de contacto.</p>
         </a>

        {{-- Puedes añadir más tarjetas aquí si necesitas accesos directos a otras secciones --}}

    </div>

</div>
@endsection