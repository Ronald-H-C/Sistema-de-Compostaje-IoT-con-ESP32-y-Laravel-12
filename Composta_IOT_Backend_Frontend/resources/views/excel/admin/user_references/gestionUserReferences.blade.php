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
            <i class="fas fa-users mr-3 text-green-600"></i> Gestión de Referencias
        </h1>
        <a href="{{ route('user_references.create') }}" 
           class="inline-flex items-center px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium shadow-md transition-all duration-200">
            <i class="fas fa-plus-circle mr-2"></i> Nueva Referencia
        </a>
    </div>

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white flex justify-between items-center p-5">
            <h6 class="m-0 font-semibold text-lg flex items-center">
                <i class="fas fa-list mr-2"></i> Listado de Referencias
            </h6>
            {{-- Badge semitransparente --}}
            <span class="px-3 py-1 rounded-full bg-white/25 text-white text-sm font-semibold">
                Total: {{ $references->total() }}
            </span>
        </div>

        {{-- Quitamos el padding del body para que la tabla se ajuste --}}
        <div class="p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full align-middle text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                        <tr>
                            {{-- Padding aumentado para mejor espaciado --}}
                            <th class="px-6 py-3 text-center">#</th>
                            <th class="px-6 py-3 text-left">Usuario</th>
                            <th class="px-6 py-3 text-left">Teléfono</th>
                            <th class="px-6 py-3 text-left">Redes Sociales</th>
                            <th class="px-6 py-3 text-left">QR</th>
                            <th class="px-6 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    {{-- Líneas divisorias finas --}}
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($references as $ref)
                            <tr class="hover:bg-gray-50 transition">
                                {{-- Padding aumentado --}}
                                <td class="px-6 py-4 text-center">
                                    {{-- Contador Blade para paginación --}}
                                    <span class="font-medium text-gray-700">
                                        {{ ($references->currentPage() - 1) * $references->perPage() + $loop->iteration }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $ref->user->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $ref->phone ?? '—' }}</td>
                                
                                {{-- Celda de Redes con Flexbox en lugar de <br> --}}
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1.5 items-start">
                                        @if ($ref->whatsapp_link)
                                            <a href="{{ $ref->whatsapp_link }}" target="_blank" 
                                               class="inline-flex items-center px-2.5 py-0.5 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                                <i class="fab fa-whatsapp mr-1.5"></i> WhatsApp
                                            </a>
                                        @endif
                                        @if ($ref->facebook_link)
                                            <a href="{{ $ref->facebook_link }}" target="_blank" 
                                               class="inline-flex items-center px-2.5 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                                <i class="fab fa-facebook mr-1.5"></i> Facebook
                                            </a>
                                        @endif
                                        @if ($ref->instagram_link)
                                            <a href="{{ $ref->instagram_link }}" target="_blank" 
                                               class="inline-flex items-center px-2.5 py-0.5 bg-pink-100 text-pink-800 rounded-full text-xs font-medium">
                                                <i class="fab fa-instagram mr-1.5"></i> Instagram
                                            </a>
                                        @endif
                                        @if ($ref->tiktok_link)
                                            <a href="{{ $ref->tiktok_link }}" target="_blank" 
                                               class="inline-flex items-center px-2.5 py-0.5 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">
                                                <i class="fab fa-tiktok mr-1.5"></i> TikTok
                                            </a>
                                        @endif
                                        @if (!$ref->whatsapp_link && !$ref->facebook_link && !$ref->instagram_link && !$ref->tiktok_link)
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    @if ($ref->qr_image)
                                        {{-- Badge azul para "ver" --}}
                                        <a href="{{ asset($ref->qr_image) }}" target="_blank" 
                                           class="inline-flex items-center px-2.5 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                            <i class="fas fa-qrcode mr-1.5"></i> Ver QR
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">Sin imagen</span>
                                    @endif
                                </td>
                                
                                <td class="px-6 py-4 text-center">
                                    {{-- Botón de editar más refinado --}}
                                    <a href="{{ route('user_references.edit', $ref->id) }}" 
                                       class="inline-flex items-center px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white rounded-lg shadow-sm text-xs font-medium">
                                        <i class="fas fa-edit mr-1"></i> Editar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-gray-500 py-10">
                                    <i class="fas fa-info-circle mr-1"></i> No hay referencias registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-gray-200">
                {{-- Esto usará las vistas de paginación de Tailwind por defecto --}}
                {{ $references->links() }}
            </div>
        </div>
    </div>

</div>
@endsection