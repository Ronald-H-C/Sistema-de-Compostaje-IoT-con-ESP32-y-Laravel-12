@extends('user.dashboard')

@section('title', 'Planes de Suscripción')

@section('content')
<div id="layoutSidenav_content" class="p-6 md:p-10 bg-gradient-to-b from-sky-50 to-white min-h-screen">
    <h1 class="text-4xl font-extrabold text-center mb-14 text-gray-800 tracking-tight">
        🪙 Elige tu Plan
    </h1>

    <div class="w-full flex justify-end -mt-4 mb-6 px-4">
        <a href="{{route('showPlansAdq')}}"
        class="inline-flex items-center bg-green-600 text-white font-semibold py-2 px-5 rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-150 ease-in-out">
            
            {{-- Icono Opcional (Heroicons) --}}
            <svg xmlns="http://www.w-w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 00-2-2H5z" />
            </svg>
            
            Planes Adquiridos
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-10 max-w-7xl mx-auto">
        @foreach ($plans as $plan)
            @php
            if($expire){
            if ($plan->id == 1) continue;}
                $planName = strtoupper($plan->name);
                $colors = [
                    'FREE' => [
                        'badge' => 'bg-gray-400 text-white',
                        'bg' => 'bg-white',
                        'border' => 'border-gray-300',
                        'button' => 'bg-gray-400 text-white hover:bg-gray-500',
                    ],
                    'BASIC' => [
                        'badge' => 'bg-blue-500 text-white',
                        'bg' => 'bg-gradient-to-br from-blue-50 to-blue-100',
                        'border' => 'border-blue-300',
                        'button' => 'bg-blue-500 text-white hover:bg-blue-600',
                    ],
                    'PREMIUM' => [
                        'badge' => 'bg-yellow-400 text-yellow-900',
                        'bg' => 'bg-gradient-to-br from-yellow-50 to-yellow-100',
                        'border' => 'border-yellow-400 border-2',
                        'button' => 'bg-yellow-400 text-yellow-900 font-semibold hover:bg-yellow-500',
                    ],
                ];
                $style = $colors[$planName] ?? $colors['FREE'];

                $isActive = isset($activePlan) && $plan->id == $activePlan->idPlan;
            @endphp

            <div class="rounded-3xl p-6 shadow-xl plan-card border {{ $style['bg'] }} {{ $style['border'] }} 
                        transition-transform duration-300 hover:scale-105 hover:shadow-2xl
                        @if($isActive) ring-4 ring-green-400 @endif">
                
                <div class="mb-4 flex justify-between items-center">
                    <span class="inline-block px-4 py-1 rounded-full text-sm font-semibold tracking-wide {{ $style['badge'] }}">
                        {{ $planName }}
                    </span>

                    @if($isActive)
                        <span class="px-3 py-1 text-xs font-bold bg-green-500 text-white rounded-full shadow">
                            ✅ Activo
                        </span>
                    @endif
                </div>

                <h2 class="text-3xl font-bold text-gray-800 mb-2">
                    {{ $plan->cost > 0 ? 'Bs ' . number_format($plan->cost, 2) : 'Gratis' }}
                </h2>
                <p class="text-gray-600 mb-4 min-h-[60px]">{{ $plan->description ?? 'Descripción no disponible' }}</p>

                <ul class="text-gray-700 text-sm mb-6 space-y-2">
                    <li>📆 <strong>{{ $plan->duration }}</strong> días de duración</li>
                    <li>📌 <strong>{{ $plan->post_limit }}</strong> publicaciones permitidas</li>

                    @if(strtolower($plan->name) === 'basic')
                        <li>✅ Acceso básico</li>
                    @elseif(strtolower($plan->name) === 'premium')
                        <li>🚀 Prioridad en listados</li>
                        <li>📞 Soporte prioritario</li>
                    @endif
                </ul>

                @if($isActive)
                    <button class="px-4 py-2 rounded-full inline-block text-center shadow-md bg-green-500 text-white font-bold cursor-not-allowed opacity-80">
                        Plan Actual
                    </button>
                @else
                    <a href="{{ route('uplans.comprar', $plan->id) }}"
                       class="{{ $style['button'] }} px-4 py-2 rounded-full inline-block text-center shadow-md hover:shadow-lg transition-all">
                        Seleccionar
                    </a>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
