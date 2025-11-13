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
            <i class="fas fa-box mr-3 text-green-600"></i> Gestión de Productos de Abono
        </h1>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('products.create') }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium shadow-md transition">
                <i class="fas fa-plus-circle mr-2"></i> Nuevo Producto
            </a>
            <a href="{{ route('products.delete') }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg bg-orange-600 hover:bg-red-700 text-white font-medium shadow-md transition">
                <i class="fas fa-trash-restore-alt mr-2"></i> Ver Eliminados
            </a>
        </div>
    </div>

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white flex justify-between items-center p-5">
            <h6 class="m-0 font-semibold text-lg flex items-center">
                <i class="fas fa-list mr-2"></i> Listado de Productos Disponibles
            </h6>
            {{-- Badge semitransparente --}}
            <span class="px-3 py-1 rounded-full bg-white/25 text-white text-sm font-semibold">
                Total: {{ $productos->total() }}
            </span>
        </div>

        <div class="p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full align-middle text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-6 py-3 text-center">#</th>
                            <th class="px-6 py-3 text-left">Título</th>
                            <th class="px-6 py-3 text-left">Tipo</th>
                            <th class="px-6 py-3 text-right">Precio</th>
                            <th class="px-6 py-3 text-right">Cantidad (kg)</th>
                            <th class="px-6 py-3 text-left">Usuario</th>
                            <th class="px-6 py-3 text-left">Fecha</th>
                            <th class="px-6 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($productos as $producto)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-center">
                                    <span class="font-medium text-gray-700">
                                        {{ ($productos->currentPage() - 1) * $productos->perPage() + $loop->iteration }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $producto->title }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800 text-xs font-semibold">
                                        {{ ucfirst(str_replace('_', ' ', $producto->type)) }}
                                    </span>
                                </td>
                                
                                {{-- CAMBIO: de $ a Bs. --}}
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">
                                    Bs. {{ number_format($producto->price, 2) }}
                                </td>
                                <td class="px-6 py-4 text-right text-gray-700">{{ $producto->amount }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $producto->user->name }} {{ $producto->user->firstLastName }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ \Carbon\Carbon::parse($producto->created_at)->format('d/m/Y H:i') }}</td>
                                
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('products.edit', $producto->id) }}" 
                                           class="inline-flex items-center px-2.5 py-1.5 bg-yellow-400 hover:bg-yellow-500 text-white rounded-lg shadow-sm" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $producto->id) }}" method="POST" 
                                              class="inline-block"
                                              onsubmit="return confirm('¿Desactivar este producto?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-2.5 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg shadow-sm" 
                                                    title="Desactivar">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-gray-500 py-10">
                                    <i class="fas fa-box-open mr-1"></i> No hay productos disponibles.
                                </td>
                            </tr>
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