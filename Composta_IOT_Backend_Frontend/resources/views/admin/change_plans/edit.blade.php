@extends('admin.dashboard')

@section('content')
<div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen"> {{-- Padding aumentado --}}

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
                <i class="fas fa-file-invoice-dollar mr-2"></i> Actualizar Estado de Pago
            </h6>
            {{-- Botón de volver circular --}}
            <a href="{{ route('change_plans.index') }}"
               title="Volver al listado"
               class="inline-flex items-center justify-center h-11 w-11 rounded-full text-white/80 hover:bg-white/20 hover:text-white transition-colors">
                <i class="fas fa-long-arrow-alt-left text-2xl"></i>
            </a>
        </div>

        <div class="bg-white p-6">
            <form action="{{ route('change_plans.update', $change_plan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Contenedor con espaciado vertical --}}
                <div class="space-y-5">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                        <div class="flex items-center w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-gray-50 text-gray-700 font-medium">
                            <i class="fas fa-user mr-2 text-green-600"></i>
                            {{ $change_plan->user->name }} {{ $change_plan->user->firstLastName }}
                        </div>
                    </div>

                    <div>
                        <label for="observations" class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                        <textarea name="observations" id="observations" rows="4"
                                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-2.5">{{ old('observations', $change_plan->observations) }}</textarea> {{-- Corregido 'notes' a 'observations' --}}
                    </div>

                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
                        <select id="state" name="state" required
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5">
                            <option value="">Seleccionar estado...</option>
                            <option value="0" {{ old('state', $change_plan->state) == 0 ? 'selected' : '' }}>Rechazado</option>
                            <option value="1" {{ old('state', $change_plan->state) == 1 ? 'selected' : '' }}>Pendiente</option>
                            <option value="2" {{ old('state', $change_plan->state) == 2 ? 'selected' : '' }}>Aprobado</option>
                        </select>
                    </div>

                    @if ($change_plan->image)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Comprobante Actual</label>
                        <a href="{{ asset($change_plan->image) }}" target="_blank"
                           class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800 text-xs font-medium hover:bg-blue-200 mt-1">
                            <i class="fas fa-image mr-1.5"></i> Ver imagen
                        </a>
                        {{-- Opcional: Mostrar miniatura
                        <img src="{{ asset($change_plan->image) }}" alt="Comprobante" class="mt-2 h-32 w-auto rounded border">
                        --}}
                    </div>
                    @endif
                    


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