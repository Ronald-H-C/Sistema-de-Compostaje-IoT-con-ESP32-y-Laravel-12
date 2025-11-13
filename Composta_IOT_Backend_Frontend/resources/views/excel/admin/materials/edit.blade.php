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

    @if ($errors->any())
        <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-300 text-red-900 shadow-sm">
            <h6 class="font-semibold mb-2 flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Errores encontrados:
            </h6>
            <ul class="mb-0 list-disc list-inside text-sm text-red-800">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white flex justify-between items-center p-5">
            <h6 class="m-0 font-semibold text-lg flex items-center">
                <i class="fas fa-edit mr-2"></i> Editar Material
            </h6>
            {{-- Botón de volver circular --}}
            <a href="{{ route('materials.index') }}"
               title="Volver al listado"
               class="inline-flex items-center justify-center h-11 w-11 rounded-full text-white/80 hover:bg-white/20 hover:text-white transition-colors">
                <i class="fas fa-long-arrow-alt-left text-2xl"></i>
            </a>
        </div>

        <div class="bg-white p-6">
            <form action="{{ route('materials.update', $material->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Contenedor con espaciado vertical --}}
                <div class="space-y-5">

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Material *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $material->name) }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5"
                               required>
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Imagen del Material</label>
                        <input type="file" name="image" id="image"
                               class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg shadow-sm cursor-pointer focus:outline-none bg-white
                                      file:py-2.5 file:px-4 file:mr-4 file:border-0
                                      file:bg-gray-50 file:font-medium file:text-gray-700 hover:file:bg-gray-100"
                               accept="image/*">
                         <p class="mt-1 text-xs text-gray-500">Sube un archivo solo si quieres reemplazar la imagen actual.</p>
                        @if($material->image)
                             <div class="mt-3">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Imagen actual:</label>
                                <img src="{{ asset($material->image) }}" alt="Imagen Material" class="w-32 h-32 object-cover rounded-lg border border-gray-200 shadow-sm">
                            </div>
                        @endif
                    </div>

                    <div>
                        <label for="clasification" class="block text-sm font-medium text-gray-700 mb-1">Clasificación *</label>
                        <select name="clasification" id="clasification"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5" required>
                            <option value="">Seleccione una opción</option>
                            <option value="verde" {{ old('clasification', $material->clasification)=='verde' ? 'selected' : '' }}>Verde</option>
                            <option value="marron" {{ old('clasification', $material->clasification)=='marron' ? 'selected' : '' }}>Marrón</option>
                            <option value="no_compostable" {{ old('clasification', $material->clasification)=='no_compostable' ? 'selected' : '' }}>No compostable</option>
                        </select>
                    </div>

                    <div>
                        <label for="aptitude" class="block text-sm font-medium text-gray-700 mb-1">Aptitud</label>
                        <select name="aptitude" id="aptitude"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5">
                            <option value="">Seleccione una opción</option>
                            <option value="casero" {{ old('aptitude', $material->aptitude)=='casero' ? 'selected' : '' }}>Casero</option>
                            <option value="industrial" {{ old('aptitude', $material->aptitude)=='industrial' ? 'selected' : '' }}>Industrial</option>
                            <option value="no_recomendado" {{ old('aptitude', $material->aptitude)=='no_recomendado' ? 'selected' : '' }}>No recomendado</option>
                        </select>
                    </div>

                    <div>
                        <label for="type_category" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Categoría</label>
                        <select name="type_category" id="type_category"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5">
                            <option value="">Seleccione una opción</option>
                            <option value="alimentos" {{ old('type_category', $material->type_category)=='alimentos' ? 'selected' : '' }}>Alimentos</option>
                            <option value="jardin" {{ old('type_category', $material->type_category)=='jardin' ? 'selected' : '' }}>Jardín</option>
                            <option value="papel_carton" {{ old('type_category', $material->type_category)=='papel_carton' ? 'selected' : '' }}>Papel/Cartón</option>
                            {{-- Puedes añadir más opciones si es necesario --}}
                        </select>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea name="description" id="description" rows="4"
                                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-2.5"
                                  placeholder="Detalles sobre el material">{{ old('description', $material->description) }}</textarea>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <a href="{{ route('materials.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition shadow-sm">
                       <i class="fas fa-times mr-2"></i> Cancelar
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition shadow-md">
                        <i class="fas fa-save mr-2"></i> Actualizar Material
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection