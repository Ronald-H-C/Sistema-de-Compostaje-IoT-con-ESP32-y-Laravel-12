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
            <i class="fas fa-user-tag text-green-600 mr-3"></i>
            Gestión de Planes de Usuarios
        </h1>
        {{-- No hay botones de acción globales aquí, lo cual es correcto --}}
    </div>

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white flex justify-between items-center p-5">
            <h6 class="m-0 font-semibold text-lg flex items-center">
                <i class="fas fa-list mr-2"></i> Listado de Planes Asignados
            </h6>
           
        </div>
        <div class="p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full align-middle text-sm">
                    <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3 text-center">N°</th>
                            <th class="px-6 py-3 text-left">Usuario</th>
                            <th class="px-6 py-3 text-left">Plan Actual</th>
                            <th class="px-6 py-3 text-left">Estado</th>
                            <th class="px-6 py-3 text-left">Inicio</th>
                            <th class="px-6 py-3 text-left">Vencimiento</th>
                            <th class="px-6 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($userPlans as $plan)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-center">
                                    {{-- Contador de paginación --}}
                                    <span class="font-medium text-gray-700">
                                        {{ ($userPlans->currentPage() - 1) * $userPlans->perPage() + $loop->iteration }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $plan->user->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $plan->plan->name ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    @if ($plan->active)
                                        <span class="px-2.5 py-0.5 text-xs rounded-full bg-green-100 text-green-800 font-medium">Activo</span>
                                    @else
                                        {{-- Usamos gris para inactivo aquí para diferenciarlo de las páginas de "eliminados" --}}
                                        <span class="px-2.5 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600 font-medium">Inactivo</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ $plan->started_at ? \Carbon\Carbon::parse($plan->started_at)->format('d/m/Y') : '—' }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $plan->expires_at ? \Carbon\Carbon::parse($plan->expires_at)->format('d/m/Y') : '—' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('user_plans.edit', $plan->id) }}" 
                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-sm transition">
                                        <i class="fas fa-edit mr-1"></i> Editar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-gray-500 py-10">
                                    <i class="fas fa-inbox mr-2"></i>No hay planes asignados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-gray-200">
                {{ $userPlans->links() }}
            </div>
        </div>
    </div>
</div>
@endsection