@extends('admin.dashboard')

@section('content')
<div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen"> {{-- Padding aumentado --}}

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



    <!-- ✅ Tabla de Comprobantes (Puro Tailwind) -->
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        {{-- Cabecera roja/naranja con botón circular --}}
        <div class="bg-gradient-to-r from-red-500 to-rose-600 text-white flex justify-between items-center p-5">
            <h6 class="m-0 font-semibold text-lg flex items-center">
                <i class="fas fa-list mr-2"></i> Lista de Comprobantes Rechazados
            </h6>
             <a href="{{ route('change_plans.index') }}" 
                title="Volver al listado principal"
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
                                     {{-- Solo debería haber rechazados (estado 0) aquí --}}
                                     <span class="px-2.5 py-0.5 text-xs rounded-full bg-red-100 text-red-800 font-medium">Rechazado</span>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $change_plan->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 text-center">
                                    {{-- Botón para reactivar (cambiar estado a pendiente) --}}
                                    <form action="{{ route('change_plans.activate', $change_plan->id) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('¿Revisar de nuevo este comprobante? Se marcará como Pendiente.')">
                                        @csrf
                                        @method('PUT') 
                                        {{-- Asegúrate que la ruta 'change_plans.activate' cambie el estado a 1 (Pendiente) --}}
                                        <input type="hidden" name="new_state" value="1"> {{-- Puedes enviar el nuevo estado si tu controlador lo espera --}}
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg shadow-sm transition">
                                            <i class="fas fa-undo mr-1"></i> Revisar de Nuevo
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-gray-500 py-10">
                                    <i class="fas fa-inbox mr-2"></i>No hay comprobantes rechazados.
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