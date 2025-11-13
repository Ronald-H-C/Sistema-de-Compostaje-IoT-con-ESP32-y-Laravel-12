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

    

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        {{-- Cabecera verde con botón circular --}}
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white flex justify-between items-center p-5">
            <h6 class="m-0 font-semibold text-lg flex items-center">
                <i class="fas fa-list mr-2"></i> Lista de Mensajes Recepcionados
            </h6>
             <a href="{{ route('contacts.index') }}" 
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
                            <th class="px-6 py-3 text-left">Nombre</th>
                            <th class="px-6 py-3 text-left">Email</th>
                            <th class="px-6 py-3 text-left">Asunto</th>
                            <th class="px-6 py-3 text-left">Mensaje</th>
                            <th class="px-6 py-3 text-left">Estado</th>
                            <th class="px-6 py-3 text-left">Fecha de Envío</th>
                            <th class="px-6 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($messages as $message)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-center">
                                    {{-- Contador de paginación --}}
                                    <span class="font-medium text-gray-700">
                                        {{ ($messages->currentPage() - 1) * $messages->perPage() + $loop->iteration }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $message->full_name }}</td>
                                <td class="px-6 py-4 text-blue-600">{{ $message->email }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $message->subject }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ Str::limit($message->message, 60, '...') }}</td>
                                <td class="px-6 py-4">
                                    {{-- Asumiendo que state 0 es Recepcionado/Archivado --}}
                                    <span class="px-2.5 py-0.5 text-xs rounded-full bg-gray-100 text-gray-700 font-medium">Recepcionado</span>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $message->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 text-center">
                                     {{-- Botón Restaurar --}}
                                    <form action="{{ route('contacts.activate', $message->id) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('¿Restaurar este mensaje? Volverá al listado principal.')">
                                        @csrf
                                        @method('PUT') 
                                        {{-- Asegúrate que la ruta 'contacts.activate' cambia el estado a Pendiente (1) o el estado deseado --}}
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-sm transition">
                                            <i class="fas fa-trash-restore-alt mr-1"></i> Restaurar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-gray-500 py-10">
                                    <i class="fas fa-inbox mr-2"></i>No hay mensajes archivados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-gray-200">
                {{ $messages->links() }}
            </div>
        </div>
    </div>
</div>
@endsection