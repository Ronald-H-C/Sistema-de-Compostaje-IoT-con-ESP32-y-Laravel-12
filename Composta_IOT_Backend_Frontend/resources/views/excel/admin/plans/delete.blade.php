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


    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        {{-- Cabecera con gradiente rojo/naranja y botón circular --}}
        <div class="bg-gradient-to-r from-orange-500 to-red-600 text-white flex justify-between items-center p-5">
            <h5 class="m-0 font-semibold text-lg flex items-center">
                <i class="fas fa-list mr-2"></i> Lista de Planes Inactivos
            </h5>
            <a href="{{ route('plans.index') }}" 
               title="Volver al listado"
               class="inline-flex items-center justify-center h-11 w-11 rounded-full text-white/80 hover:bg-white/20 hover:text-white transition-colors">
                <i class="fas fa-long-arrow-alt-left text-2xl"></i>
            </a>
        </div>

        <div class="p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full align-middle text-sm">
                    <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3 text-center">Nro</th>
                            <th class="px-6 py-3 text-left">Nombre</th>
                            <th class="px-6 py-3 text-left">Descripción</th>
                            <th class="px-6 py-3 text-center">Duración</th>
                            <th class="px-6 py-3 text-right">Precio</th>
                            <th class="px-6 py-3 text-center">Publicaciones</th>
                            <th class="px-6 py-3 text-left">Estado</th>
                            <th class="px-6 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($plans as $plan)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-center">
                                    {{-- Contador de paginación (Corregido) --}}
                                    <span class="font-medium text-gray-700">
                                        {{ ($plans->currentPage() - 1) * $plans->perPage() + $loop->iteration }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $plan->name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ Str::limit($plan->description, 50) }}</td>
                                <td class="px-6 py-4 text-center text-gray-700">{{ $plan->duration }} días</td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">Bs {{ number_format($plan->cost, 2) }}</td>
                                <td class="px-6 py-4 text-center font-semibold text-gray-700">{{ $plan->post_limit ?? '∞' }}</td>
                                <td class="px-6 py-4">
                                    {{-- Badge "Inactivo" (Estilo del Modelo) --}}
                                    <span class="px-2.5 py-0.5 text-xs rounded-full bg-red-100 text-red-800 font-medium">Inactivo</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('plans.activate', $plan->id) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('¿Reactivar este plan?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-md transition">
                                            <i class="fas fa-trash-restore-alt mr-1.5"></i> Reactivar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-gray-500 py-10">
                                    <i class="fas fa-inbox mr-2"></i>No hay planes inactivos.
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