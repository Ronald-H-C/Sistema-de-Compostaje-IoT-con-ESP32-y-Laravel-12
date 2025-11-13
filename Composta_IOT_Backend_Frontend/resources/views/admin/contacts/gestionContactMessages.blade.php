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

    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-3">
        <h1 class="text-3xl font-semibold text-gray-900 flex items-center">
            <i class="fas fa-envelope-open-text text-green-600 mr-3"></i>
            Gestión de Mensajes de Contacto
        </h1>
        <a href="{{ route('contacts.delete') }}"
           class="inline-flex items-center px-4 py-2 rounded-lg bg-orange-700 hover:bg-red-800 text-white text-sm font-medium shadow-md transition">
            <i class="fas fa-archive mr-2"></i> Ver Mensajes Archivados
        </a>
    </div>

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white p-5">
            <h6 class="m-0 font-semibold text-lg flex items-center">
                <i class="fas fa-list mr-2"></i> Listado de Mensajes Recibidos
            </h6>
            {{-- Podrías añadir un badge de total aquí si lo necesitas --}}
            {{-- <span class="px-3 py-1 rounded-full bg-white/25 text-white text-sm font-semibold">
                Total: {{ $messages->total() }}
            </span> --}}
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
                                    @switch($message->state)
                                        @case(0)
                                            <span class="px-2.5 py-0.5 text-xs rounded-full bg-gray-100 text-gray-700 font-medium">Recepcionado</span>
                                        @break
                                        @case(1)
                                            <span class="px-2.5 py-0.5 text-xs rounded-full bg-yellow-100 text-yellow-800 font-medium">Pendiente</span>
                                        @break
                                        @default
                                            <span class="px-2.5 py-0.5 text-xs rounded-full bg-red-100 text-red-800 font-medium">Desconocido</span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $message->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('contacts.edit', $message->id) }}"
                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg shadow-sm transition">
                                        <i class="fas fa-edit mr-1"></i> Actualizar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-gray-500 py-10">
                                    <i class="fas fa-inbox mr-2"></i>No hay mensajes disponibles.
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