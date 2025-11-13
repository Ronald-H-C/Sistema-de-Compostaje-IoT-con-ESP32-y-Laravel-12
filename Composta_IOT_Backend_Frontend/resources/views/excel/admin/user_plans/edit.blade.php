@extends('admin.dashboard')

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

    @if ($errors->any())
        <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-300 text-red-900 shadow-sm">
            <h6 class="font-semibold mb-2 flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Errores encontrados:
            </h6>
            <ul class="mb-0 list-disc list-inside text-sm text-red-800">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white flex justify-between items-center p-5">
            <h6 class="m-0 font-semibold text-lg flex items-center">
                <i class="fas fa-edit mr-2"></i> Actualizar Plan de Usuario
            </h6>
            {{-- Botón de volver circular --}}
            <a href="{{ route('user_plans.index') }}"
               title="Volver al listado"
               class="inline-flex items-center justify-center h-11 w-11 rounded-full text-white/80 hover:bg-white/20 hover:text-white transition-colors">
                <i class="fas fa-long-arrow-alt-left text-2xl"></i>
            </a>
        </div>

        <div class="bg-white p-6">
            <form action="{{ route('user_plans.update', $userPlan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                    <div class="flex items-center w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-gray-50 text-gray-700 font-medium">
                        <i class="fas fa-user mr-2 text-green-600"></i>
                        {{ $userPlan->user->name }} {{ $userPlan->user->firstLastName }}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-5">
                         <div>
                            <label for="plan_id" class="block text-sm font-medium text-gray-700 mb-1">Plan asignado *</label>
                            <select name="plan_id" id="plan_id" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5">
                                <option value="">Seleccionar plan...</option>
                                @foreach ($planes as $plan)
                                    <option value="{{ $plan->id }}"
                                            {{ old('plan_id', $userPlan->plan_id) == $plan->id ? 'selected' : '' }}>
                                        {{-- CAMBIO: Moneda a Bs. --}}
                                        {{ $plan->name }} (Bs {{ number_format($plan->cost, 2) }}, {{ $plan->duration }} días)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label for="active" class="block text-sm font-medium text-gray-700 mb-1">Estado del plan *</label>
                            <select name="active" id="active" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5">
                                <option value="">Seleccionar estado...</option>
                                <option value="1" {{ old('active', $userPlan->active) == 1 ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ old('active', $userPlan->active) == 0 ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium shadow-md transition-all duration-200 ease-in-out">
                        <i class="fas fa-save mr-2"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection