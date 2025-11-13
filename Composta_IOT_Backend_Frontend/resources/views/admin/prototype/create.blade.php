@extends('admin.dashboard')

@section('content')
<div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen">

    {{-- Alertas --}}
    @if (session('success'))
        <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-300 text-green-800 font-medium shadow-sm flex items-center">
            <i class="fas fa-check-circle mr-2 text-green-600"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-300 text-red-800 font-medium shadow-sm flex items-center">
            <i class="fas fa-exclamation-triangle mr-2 text-red-600"></i>
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-300 text-red-900 shadow-sm">
            <!-- Título de error modificado -->
            <h6 class="font-semibold mb-2 flex items-center">
                <i class="fas fa-times-circle mr-2"></i>
                Error en la creación del prototipo
            </h6>
            <ul class="mb-0 list-disc list-inside text-sm text-red-800">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        
        <!-- Encabezado modificado -->
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white flex justify-between items-center p-5">
            <h5 class="m-0 font-semibold text-lg flex items-center">
                <i class="fas fa-microchip mr-2"></i>
                Nuevo Compostador
            </h5>
            
            <!-- Enlace de "volver" (puedes ajustar la ruta) -->
            <a href="{{route('compost')}}" 
               title="Volver"
               class="inline-flex items-center justify-center h-11 w-11 rounded-full text-white/80 hover:bg-white/20 hover:text-white transition-colors">
                <i class="fas fa-long-arrow-alt-left text-2xl"></i>
            </a>
        </div>

        <div class="bg-white p-6">
            <!-- Formulario apuntando a la ruta de storePrototype -->
            <form action="{{ route('storePrototype') }}" method="POST">
                @csrf

                <!-- Rejilla con los dos campos que pediste -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- 1. Campo Nombre del Prototipo -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Compostador *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" 
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5" required>
                    </div>

                    <!-- 2. Campo Asignar Usuario (Selector) -->
                    <div>
                        <label for="idUser" class="block text-sm font-medium text-gray-700 mb-1">Asignar a Usuario</label>
                        <select id="idUser" name="idUser" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5">
                            <option value="">No asignado</option>
                            
                            <!-- Bucle para cargar los usuarios -->
                            @foreach ($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" {{ old('user_id') == $usuario->id ? 'selected' : '' }}>
                                    {{ $usuario->name }} {{ $usuario->firstLastName ?? '' }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-2">Puedes asignarlo más tarde si lo deseas.</p>
                    </div>

                </div>

                <!-- Botón de guardado modificado -->
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium shadow-md transition-all duration-200 ease-in-out">
                        <i class="fas fa-save mr-2"></i>
                        Guardar Prototipo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection