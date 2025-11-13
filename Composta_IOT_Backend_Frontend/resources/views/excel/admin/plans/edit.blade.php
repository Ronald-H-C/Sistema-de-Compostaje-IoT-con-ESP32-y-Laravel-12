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
                <i class="fas fa-edit mr-2"></i> Editar Plan
            </h6>
            {{-- Botón de volver circular --}}
            <a href="{{ route('plans.index') }}"
               title="Volver al listado"
               class="inline-flex items-center justify-center h-11 w-11 rounded-full text-white/80 hover:bg-white/20 hover:text-white transition-colors">
                <i class="fas fa-long-arrow-alt-left text-2xl"></i>
            </a>
        </div>

        <div class="bg-white p-6">
            <form action="{{ route('plans.update', $plan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="space-y-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Plan *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $plan->name) }}" required
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5">
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción *</label>
                            <textarea id="description" name="description" rows="4" required
                                      class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-2.5">{{ old('description', $plan->description) }}</textarea>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label for="cost" class="block text-sm font-medium text-gray-700 mb-1">Costo (Bs) *</label>
                            <input type="number" id="cost" name="cost" step="0.01" value="{{ old('cost', $plan->cost) }}" required min="0"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5">
                        </div>

                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Duración (días) *</label>
                            <input type="number" id="duration" name="duration" value="{{ old('duration', $plan->duration) }}" required min="1"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5">
                        </div>

                        <div>
                            <label for="post_limit" class="block text-sm font-medium text-gray-700 mb-1">Límite de publicaciones</label>
                            <input type="number" id="post_limit" name="post_limit" value="{{ old('post_limit', $plan->post_limit) }}" min="0"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5">
                            <p class="mt-2 text-xs text-gray-500">Déjalo vacío o en 0 para ilimitado</p>
                        </div>

                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
                            <select id="state" name="state" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5">
                                <option value="">Seleccionar estado...</option>
                                <option value="1" {{ old('state', $plan->state ?? 1) == 1 ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ old('state', $plan->state ?? 1) == 0 ? 'selected' : '' }}>Inactivo</option>
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