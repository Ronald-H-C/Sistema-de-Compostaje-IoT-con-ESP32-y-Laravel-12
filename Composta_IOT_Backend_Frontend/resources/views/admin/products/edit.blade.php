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
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white flex justify-between items-center p-5">
            <h6 class="m-0 font-semibold text-lg flex items-center">
                <i class="fas fa-edit mr-2"></i> Editar Producto
            </h6>
            {{-- Botón de volver circular --}}
            <a href="{{ route('products.index') }}" 
               title="Volver al listado"
               class="inline-flex items-center justify-center h-11 w-11 rounded-full text-white/80 hover:bg-white/20 hover:text-white transition-colors">
                <i class="fas fa-long-arrow-alt-left text-2xl"></i>
            </a>
        </div>

        <div class="bg-white p-6">
            <form action="{{ route('products.update', $producto->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                            {{-- Input deshabilitado estilizado --}}
                            <input type="text" class="block w-full rounded-lg border-gray-300 shadow-sm sm:text-sm py-2.5 bg-gray-100 text-gray-600 cursor-not-allowed" 
                                   value="{{ $producto->user->name ?? 'Sin asignar' }}" disabled>
                        </div>

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                            <input type="text" name="title" id="title" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5" 
                                   required value="{{ old('title', $producto->title) }}">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción *</label>
                            <textarea name="description" id="description" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-2.5" 
                                      rows="4" required>{{ old('description', $producto->description) }}</textarea>
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipo de abono *</label>
                            <select name="type" id="type" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5" required>
                                <option value="">Seleccionar...</option>
                                <option value="abono_organico" {{ old('type', $producto->type)=='abono_organico'?'selected':'' }}>Orgánico</option>
                                <option value="composta" {{ old('type', $producto->type)=='composta'?'selected':'' }}>Composta</option>
                                <option value="humus" {{ old('type', $producto->type)=='humus'?'selected':'' }}>Humus</option>
                                <option value="otro" {{ old('type', $producto->type)=='otro'?'selected':'' }}>Otro</option>
                            </select>
                        </div>

                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
                            <select name="state" id="state" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5" required>
                                <option value="">Seleccionar...</option>
                                <option value="1" {{ old('state', $producto->state)==1?'selected':'' }}>Activo</option>
                                <option value="0" {{ old('state', $producto->state)==0?'selected':'' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Cantidad (kg) *</label>
                            <input type="number" step="0.01" name="amount" id="amount" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5" 
                                   required value="{{ old('amount', $producto->amount) }}">
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Precio (Bs) *</label>
                            <input type="number" step="0.01" name="price" id="price" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5" 
                                   required value="{{ old('price', $producto->price) }}">
                        </div>

                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                            <input type="number" step="0.01" name="stock" id="stock" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5" 
                                   required value="{{ old('stock', $producto->stock) }}">
                        </div>

                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Imagen del producto</label>
                            <input type="file" name="image" id="image" 
                                   class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg shadow-sm cursor-pointer focus:outline-none
                                          file:py-2.5 file:px-4 file:mr-4 file:border-0
                                          file:bg-gray-50 file:font-medium file:text-gray-700 hover:file:bg-gray-100">
                        </div>

                        @if($producto->image)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Imagen actual:</label>
                                <div class="mt-2">
                                    {{-- Previsualización de imagen estilizada --}}
                                    <img src="{{ asset($producto->image) }}" alt="Imagen actual" 
                                         class="rounded-lg shadow-md border border-gray-200 w-40 h-40 object-cover">
                                </div>
                            </div>
                        @endif

                        <div>
                            <label for="link_google_maps" class="block text-sm font-medium text-gray-700 mb-1">Enlace de Google Maps *</label>
                            <input type="url" name="link_google_maps" id="link_google_maps" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5"
                                   value="{{ old('link_google_maps', $producto->location->link_google_maps ?? '') }}"
                                   required placeholder="https://maps.app.goo.gl/...">
                            <p class="mt-2 text-xs text-gray-500">Pega aquí el enlace "Compartir" de Google Maps.</p>
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Dirección *</label>
                            <textarea name="address" id="address" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-2.5" 
                                      rows="2" required>{{ old('address', $producto->location->address ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium shadow-md transition-all duration-200 ease-in-out">
                        <i class="fas fa-save mr-2"></i> Guardar Cambios
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection