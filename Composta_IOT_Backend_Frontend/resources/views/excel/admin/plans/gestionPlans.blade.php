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

    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-3">
        <h1 class="text-3xl font-semibold text-gray-900 flex items-center">
            <i class="fas fa-layer-group text-green-600 mr-3"></i>
            Gestión de Planes
        </h1>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('plans.create') }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm font-medium shadow-md transition">
                <i class="fas fa-plus-circle mr-2"></i> Nuevo Plan
            </a>
            <a href="{{ route('plans.delete') }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg bg-orange-700 hover:bg-red-800 text-white text-sm font-medium shadow-md transition">
                <i class="fas fa-trash-restore-alt mr-2"></i> Ver Planes Eliminados
            </a>
        </div>
    </div>

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-5">
            <h6 class="m-0 font-semibold text-lg flex items-center">
                <i class="fas fa-list mr-2"></i> Listado de Planes Disponibles
            </h6>
        </div>
        <div class="p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full align-middle text-sm">
                    <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3 text-center">Nro</th>
                            <th class="px-6 py-3 text-left">Nombre</th>
                            <th class="px-6 py-3 text-left">Descripción</th>
                            <th class="px-6 py-3 text-center">Duración (días)</th>
                            <th class="px-6 py-3 text-right">Precio</th>
                            <th class="px-6 py-3 text-center">Límite Posts</th>
                            <th class="px-6 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($plans as $plan)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-center">
                                    {{-- Contador de paginación --}}
                                    <span class="font-medium text-gray-700">
                                        {{ ($plans->currentPage() - 1) * $plans->perPage() + $loop->iteration }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $plan->name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ Str::limit($plan->description, 50) }}</td>
                                <td class="px-6 py-4 text-center text-gray-700">{{ $plan->duration }}</td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">Bs {{ number_format($plan->cost, 2) }}</td>
                                <td class="px-6 py-4 text-center font-semibold text-gray-700">
                                    {{ $plan->post_limit ?? '∞' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{-- Wrapper flex para espaciado con "gap" --}}
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('plans.edit', $plan->id) }}" 
                                           class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg shadow-sm transition">
                                            <i class="fas fa-edit mr-1"></i> Editar
                                        </a>

                                        <form action="{{ route('plans.destroy', $plan->id) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('¿Desactivar este plan?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-sm transition">
                                                <i class="fas fa-trash-alt mr-1"></i> Desactivar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-gray-500 py-10">
                                    <i class="fas fa-inbox mr-2"></i>No hay planes disponibles.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-gray-200">
                {{ $plans->links() }}
            </div>
        </div>
    </div>
</div>
@endsection