@extends('admin.dashboard')

@section('content')
{{-- Usamos un padding general y un fondo suave para toda la página --}}
<div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen">

    {{-- Encabezado de la página (Título y Botón de Nuevo Compostador) --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-3">
        <h1 class="text-3xl font-semibold text-gray-900 flex items-center">
            <i class="fas fa-recycle mr-3 text-green-600"></i>
            Gestión de Compostadores
        </h1>
        <a href="{{ route('createPrototype') }}" 
           class="inline-flex items-center px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium shadow-md transition-all duration-200 ease-in-out">
            <i class="fas fa-plus-circle mr-2"></i>
            Nuevo Compostador
        </a>
    </div>

    {{-- Alerta de Éxito --}}
    @if (session('success'))
        <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-300 text-green-800 font-medium shadow-sm flex items-center">
            <i class="fas fa-check-circle mr-2 text-green-600"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Alerta de Error --}}
    @if ($errors->any())
        <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-300 text-red-800 font-medium shadow-sm">
            <div class="flex items-center font-bold">
                <i class="fas fa-exclamation-triangle mr-2 text-red-600"></i>
                ¡Error!
            </div>
            <ul class="list-disc list-inside mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Contenedor principal de la tabla (Card) --}}
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white flex justify-between items-center p-5">
            <h6 class="m-0 font-semibold flex items-center text-lg">
                <i class="fas fa-list mr-2"></i>
                Listado de Compostadores
            </h6>
            <span class="bg-white/25 text-white px-3 py-1 rounded-full text-sm font-semibold">
                Total: {{ count($prototypes) }}
            </span>
        </div>

        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="w-full align-middle text-sm">
                    
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-6 py-3 text-center">#</th>
                            <th class="px-6 py-3 text-left">Nombre Compostador</th>
                            
                            <th class="px-6 py-3 text-left">Código</th>
                            <th class="px-6 py-3 text-left">Usuario Asignado</th>
                            <th class="px-6 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    
                    <tbody class="divide-y divide-gray-200">
                        @forelse($prototypes as $prototype)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                
                                <td class="px-6 py-4 text-center font-medium text-gray-600">
                                    {{ $loop->iteration }}
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-left">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $prototype->name }}
                                    </div>
                                </td>
                                
                                {{-- Se usa font-mono para que los códigos se vean bien --}}
                                <td class="px-6 py-4 whitespace-nowrap text-left">
                                    <span class="font-mono text-sm font-semibold text-gray-700">
                                        {{ $prototype->code ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('prototype.assignUser') }}" method="POST" class="m-0">
                                        @csrf
                                        <input type="hidden" name="prototype_id" value="{{ $prototype->id }}">
                                        <select name="user_id" 
                                                class="block w-full rounded-md border-gray-300 shadow-sm text-sm 
                                                       focus:border-green-500 focus:ring-green-500"
                                                onchange="this.form.submit()">
                                            
                                            <option value="">No asignado</option>
                                            @foreach ($usuarios as $usuario)
                                                <option value="{{ $usuario->id }}" 
                                                    {{ $prototype->idUser == $usuario->id ? 'selected' : '' }}>
                                                    {{ $usuario->name }} {{ $usuario->firstLastName ?? '' }}
                                                </section>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                
                                <td class="px-6 py-4">
                                     <div class="flex justify-center items-center gap-2">

                                        <form action="{{ route('prototypes.destroy', $prototype) }}" method="POST" class="m-0"
                                              onsubmit="return confirm('¿Seguro que quieres eliminar este compostador?');">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md bg-red-600 hover:bg-red-700 text-white text-xs font-medium shadow-sm transition" 
                                                    title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                
                                <td colspan="5" class="px-6 py-10 text-gray-500 text-center">
                                {{-- ========================================= --}}
                                    <i class="fas fa-info-circle mr-1"></i> No hay compostadores registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</div>
@endsection