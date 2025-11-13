@extends('admin.dashboard')

@section('content')
<div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen">

    <!-- ✅ Alertas Refinadas -->
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

    <!-- ✅ Encabezado -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-3">
        <h1 class="text-3xl font-semibold text-gray-900 flex items-center">
            <i class="fas fa-file-invoice-dollar text-green-600 mr-3"></i>
            Gestión de Comprobantes
        </h1>
        <a href="{{ route('change_plans.delete') }}" 
           class="inline-flex items-center px-4 py-2 rounded-lg bg-orange-700 hover:bg-red-800 text-white text-sm font-medium shadow-md transition">
            <i class="fas fa-trash-restore-alt mr-2"></i> Ver Comprobantes Rechazados
        </a>
    </div>

    <!-- ✅ Tabla de Comprobantes (Puro Tailwind) -->
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white flex justify-between items-center p-5">
            <h6 class="m-0 font-semibold text-lg flex items-center">
                <i class="fas fa-list mr-2"></i> Listado de Comprobantes Recibidos
            </h6>
           
           
        </div>
        <div class="p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full align-middle text-sm">
                    <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3 text-center">Nro</th>
                            <th class="px-6 py-3 text-left">Usuario</th>
                            <th class="px-6 py-3 text-left">Plan Solicitado</th>
                            <th class="px-6 py-3 text-left">Imagen</th>
                            <th class="px-6 py-3 text-left">Observaciones</th>
                            <th class="px-6 py-3 text-left">Estado</th>
                            <th class="px-6 py-3 text-left">Fecha de Envío</th>
                            <th class="px-6 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($change_plans as $change_plan)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-center">
                                    {{-- Contador de paginación --}}
                                    <span class="font-medium text-gray-700">
                                        {{ ($change_plans->currentPage() - 1) * $change_plans->perPage() + $loop->iteration }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $change_plan->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $change_plan->plan->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    @if ($change_plan->image)
                                        <a href="{{ asset($change_plan->image) }}" target="_blank"
                                           class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800 text-xs font-medium hover:bg-blue-200">
                                            <i class="fas fa-image mr-1.5"></i> Ver imagen
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">Sin imagen</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $change_plan->observations ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    @switch($change_plan->state)
                                        @case(0)
                                            <span class="px-2.5 py-0.5 text-xs rounded-full bg-red-100 text-red-800 font-medium">Rechazado</span>
                                        @break
                                        @case(1)
                                            <span class="px-2.5 py-0.5 text-xs rounded-full bg-yellow-100 text-yellow-800 font-medium">Pendiente</span>
                                        @break
                                        @case(2)
                                            <span class="px-2.5 py-0.5 text-xs rounded-full bg-green-100 text-green-800 font-medium">Aprobado</span>
                                        @break
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $change_plan->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('change_plans.edit', $change_plan->id) }}" 
                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg shadow-sm transition">
                                        <i class="fas fa-edit mr-1"></i> Actualizar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-gray-500 py-10">
                                    <i class="fas fa-inbox mr-2"></i>No hay comprobantes disponibles.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- ✅ Paginación de Tailwind -->
            <div class="p-5 border-t border-gray-200">
                {{ $change_plans->links() }}
            </div>
        </div>
    </div>
</div>
@endsection