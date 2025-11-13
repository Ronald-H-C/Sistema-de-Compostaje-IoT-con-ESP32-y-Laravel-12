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
        {{-- Cabecera con gradiente rojo/naranja --}}
        <div class="bg-gradient-to-r from-orange-500 to-red-600 text-white flex justify-between items-center p-5">
                <h5 class="m-0 font-semibold text-lg flex items-center">
                <i class="fas fa-list mr-2"></i> Lista de Productos Inactivos
            </h6>

            <a href="{{ route('products.index') }}" 
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
                            <th class="px-6 py-3 text-center">#</th>
                            <th class="px-6 py-3 text-left">Título</th>
                            <th class="px-6 py-3 text-left">Tipo</th>
                            <th class="px-6 py-3 text-right">Precio (Bs)</th>
                            <th class="px-6 py-3 text-left">Usuario</th>
                            <th class="px-6 py-3 text-left">Estado</th>
                            <th class="px-6 py-3 text-left">Fecha Publicación</th>
                            <th class="px-6 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($productos as $producto)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-center">
                                    {{-- Contador de paginación --}}
                                    <span class="font-medium text-gray-700">
                                        {{ ($productos->currentPage() - 1) * $productos->perPage() + $loop->iteration }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $producto->title }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ ucfirst(str_replace('_',' ',$producto->type)) }}</td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">
                                    Bs. {{ number_format($producto->price, 2) }}
                                </td>
                               <td class="px-6 py-4 text-gray-700">{{ $producto->user->name }} {{ $producto->user->firstLastName }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 text-xs rounded-full bg-red-100 text-red-800 font-medium">Inactivo</span>
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ \Carbon\Carbon::parse($producto->created_at)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('products.activate', $producto->id) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('¿Reactivar este producto?')">
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
                                    <i class="fas fa-inbox mr-2"></i>No hay productos inactivos.
                                </td>
                            </tr>
                        {{-- CORRECCIÓN AQUÍ --}}
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-gray-200">
                {{ $productos->links() }}
            </div>
        </div>
    </div>
</div>
@endsection