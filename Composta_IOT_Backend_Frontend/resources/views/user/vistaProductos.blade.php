@extends('user.dashboard')

{{-- Importaciones de Leaflet (CSS y JS) --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhA9w8emcffCqFSRaCOiGg9dNTgfEoP0lFmc="
     crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZPqEOFjsKliQBGVYCunUdjIcZGo="
     crossorigin=""></script>

@section('content')

<div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen">
    
    {{-- Mensajes de éxito o error --}}
    @if(session('success'))
        <div class="mb-6 p-4 text-green-800 bg-green-100 border border-green-300 rounded-lg shadow-sm" role="alert">
            <strong class="font-bold">¡Éxito!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @elseif(session('error'))
        <div class="mb-6 p-4 text-red-800 bg-red-100 border border-red-300 rounded-lg shadow-sm" role="alert">
            <strong class="font-bold">¡Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    {{-- =================================================== --}}
    {{-- FORMULARIO DE CREACIÓN (Con onclick="abrirModalMapa(null)") --}}
    {{-- =================================================== --}}
    <div class="bg-white p-6 md:p-8 rounded-xl shadow-xl mb-8">
        <h2 class="text-3xl font-bold text-green-800 mb-6 flex items-center">
            <i class="fas fa-leaf mr-3 text-green-600"></i> Agregar Nuevo Producto
        </h2>
        
        <form method="POST" action="{{ route('fertilizers.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Fila 1: Título, Precio, Cantidad, Stock --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-stone-700">Título del producto</label>
                    <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-green-600 focus:ring-green-600" placeholder="Ej: Composta Premium 10kg" required>
                </div>
                <div>
                    <label for="price" class="block text-sm font-medium text-stone-700">Precio (Bs)</label>
                    <input type="number" step="0.01" name="price" id="price" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-green-600 focus:ring-green-600" placeholder="Ej: 50.00" required>
                </div>
                <div>
                    <label for="amount" class="block text-sm font-medium text-stone-700">Cantidad (kg)</label>
                    <input type="number" name="amount" id="amount" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-green-600 focus:ring-green-600" placeholder="Ej: 10" required>
                </div>
                <div>
                    <label for="stock" class="block text-sm font-medium text-stone-700">Stock (Unidades)</label>
                    <input type="number" name="stock" id="stock" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-green-600 focus:ring-green-600" placeholder="Ej: 100" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="md:col-span-1">
                    <label for="address" class="block text-sm font-medium text-stone-700">Dirección</label>
                    <input type="text" name="address" id="address" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-green-600 focus:ring-green-600" placeholder="Ej: Av. Villarroel #123" required>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-stone-700">Ubicación en Mapa</label>
                    
                    {{-- ESTE ES EL CAMBIO (onclick="abrirModalMapa(null)") --}}
                    <button type="button" onclick="abrirModalMapa(null)" class="mt-1 inline-flex w-full items-center justify-center px-4 py-2 border border-stone-300 bg-stone-100 text-sm text-stone-700 rounded-md hover:bg-stone-200 focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600">
                        <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                        Seleccionar Ubicación
                    </button>
                    <span id="coords-feedback" class="text-xs text-green-700 mt-1 hidden">
                        <i class="fas fa-check-circle"></i> Ubicación seleccionada
                    </span>
                </div>

                <div class="md:col-span-1">
                    <label for="type" class="block text-sm font-medium text-stone-700">Tipo de producto</label>
                    <select name="type" id="type" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-green-600 focus:ring-green-600" required>
                        <option value="" disabled selected>Selecciona el tipo</option>
                        <option value="composta">Composta</option>
                        <option value="humus">Humus</option>
                        <option value="abono_organico">Abono organico</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>

                {{-- CAMPOS OCULTOS PARA "CREAR" (IDs base) --}}
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                <input type="hidden" name="link" id="maps_link">
            </div>
            
            {{-- Fila 3: Imagen y Descripción --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="image" class="block text-sm font-medium text-stone-700">Imagen del producto</label>
                    <input type="file" name="image" id="image" class="mt-1 block w-full text-sm text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100" accept="image/*" required>
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-stone-700">Descripción</label>
                    <textarea name="description" id="description" class="mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-green-600 focus:ring-green-600" rows="3" placeholder="Descripción breve del producto, sus beneficios..." required></textarea>
                </div>
            </div>

            <div class="text-right pt-4">
                <button type="submit" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-md text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    <i class="fas fa-plus-circle mr-2"></i>Guardar producto
                </button>
            </div>
        </form>
    </div>

    {{-- =================================================== --}}
    {{-- LISTADO DE PRODUCTOS (Con formularios "Actualizar" CORREGIDOS) --}}
    {{-- =================================================== --}}
    <h3 class="text-3xl font-bold text-stone-800 mb-6 flex items-center">
        <i class="fas fa-boxes mr-3 text-stone-600"></i> Productos Registrados
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($fertilizers as $fertilizer)
            <div class="flex flex-col bg-white rounded-xl shadow-xl overflow-hidden
                {{ $fertilizer->featured == 1 ? 'border-4 border-yellow-400 bg-yellow-50' : 'border border-stone-200' }}">
                
                <div class="relative">
                    @if ($fertilizer->featured)
                        <span class="absolute top-3 left-3 bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-xs font-bold shadow-md flex items-center z-10">
                            <i class="fas fa-star mr-1"></i> Destacado
                        </span>
                    @endif
                    <form method="POST" action="{{ route('destacado', $fertilizer->id) }}" class="absolute top-3 right-3 z-10">
                        @csrf
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" onchange="this.form.submit()" class="sr-only peer" {{ $fertilizer->featured == 1 ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-stone-300 rounded-full peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-yellow-400 peer-checked:bg-green-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                        </label>
                    </form>
                    @if ($fertilizer->image)
                        <div class="h-56 w-full flex items-center justify-center bg-stone-50 p-2">
                            <img src="{{ asset($fertilizer->image) }}" alt="{{ $fertilizer->title }}" class="max-h-full max-w-full object-contain">
                        </div>
                    @else
                        <div class="h-56 w-full flex items-center justify-center bg-stone-100 text-stone-400">
                            <i class="fas fa-seedling fa-4x"></i>
                        </div>
                    @endif
                </div>
                
                <div class="p-6 flex flex-col flex-grow">
                    <h5 class="text-2xl font-bold text-green-800 mb-2">{{ $fertilizer->title }}</h5>
                    <p class="text-stone-600 mb-1"><strong>Precio:</strong> <span class="text-lg font-semibold text-stone-900">Bs {{ number_format($fertilizer->price, 2) }}</span></p>
                    <p class="text-stone-600 mb-1"><strong>Cantidad:</strong> {{ $fertilizer->amount }} kg</p>
                    <p class="text-stone-600 mb-4"><strong>Stock:</strong> {{ $fertilizer->stock }} unidades</p>

                    <form method="POST" action="{{ route('fertilizers.update', $fertilizer->id) }}" enctype="multipart/form-data" class="space-y-4 flex-grow flex flex-col">
                        @csrf
                        @method('PUT')

                        <div class="space-y-3 flex-grow">
                            <div>
                                <label class="block text-xs font-medium text-stone-500">Título</label>
                                <input type="text" name="title" value="{{ $fertilizer->title }}" class="text-sm mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-green-600 focus:ring-green-600" required>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-2">
                                <div>
                                    <label class="block text-xs font-medium text-stone-500">Precio</label>
                                    <input type="number" step="0.01" name="price" value="{{ $fertilizer->price }}" class="text-sm mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-green-600 focus:ring-green-600" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-stone-500">Cantidad</label>
                                    <input type="number" name="amount" value="{{ $fertilizer->amount }}" class="text-sm mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-green-600 focus:ring-green-600" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-stone-500">Stock</label>
                                    <input type="number" name="stock" value="{{ $fertilizer->stock }}" class="text-sm mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-green-600 focus:ring-green-600" required>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-stone-500">Dirección</label>
                                <input type="text" name="address" value="{{ $fertilizer->location->address ?? '' }}" class="text-sm mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-green-600 focus:ring-green-600" required>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-stone-500">Ubicación en Mapa</label>
                                <button type="button" 
                                        onclick="abrirModalMapa({{ $fertilizer->id }}, '{{ $fertilizer->location->latitude ?? '' }}', '{{ $fertilizer->location->longitude ?? '' }}')"
                                        class="text-sm mt-1 inline-flex w-full items-center justify-center px-4 py-1.5 border border-stone-300 bg-stone-50 text-stone-700 rounded-md hover:bg-stone-100 focus:outline-none focus:ring-2 focus:ring-green-600">
                                    <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                                    Editar Ubicación
                                </button>
                                <span id="coords-feedback-{{ $fertilizer->id }}" class="text-xs text-green-700 mt-1 hidden">
                                    <i class="fas fa-check-circle"></i> Ubicación actualizada
                                </span>
                            </div>

                            <input type="hidden" name="latitude" id="latitude-{{ $fertilizer->id }}" value="{{ $fertilizer->location->latitude ?? '' }}">
                            <input type="hidden" name="longitude" id="longitude-{{ $fertilizer->id }}" value="{{ $fertilizer->location->longitude ?? '' }}">
                            <input type="hidden" name="link" id="maps_link-{{ $fertilizer->id }}" value="{{ $fertilizer->location->link_google_maps ?? '' }}">
                            
                            <div>
                                <label class="block text-xs font-medium text-stone-500">Nueva Imagen (Opcional)</label>
                                <input type="file" name="image" class="text-sm mt-1 block w-full file:mr-2 file:py-1 file:px-2 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-stone-100 file:text-stone-700 hover:file:bg-stone-200" accept="image/*">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-stone-500">Descripción</label>
                                <textarea name="description" class="text-sm mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-green-600 focus:ring-green-600" rows="2">{{ $fertilizer->description }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-stone-500">Tipo</label>
                                <select name="type" class="text-sm mt-1 block w-full rounded-md border-stone-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                                    <option value="composta" @selected($fertilizer->type == 'composta')>Composta</option>
                                    <option value="humus" @selected($fertilizer->type == 'humus')>Humus</option>
                                    <option value="abono_organico" @selected($fertilizer->type == 'abono_organico')>Abono Orgánico</option>
                                    <option value="otro" @selected($fertilizer->type == 'otro')>Otro</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-between gap-3 pt-6">
                            <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                <i class="fas fa-save mr-2"></i>Actualizar
                            </button>
                    </form>

                    <form method="POST" action="{{ route('fertilizers.destroy', $fertilizer->id) }}" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este producto? Esta acción no se puede deshacer.')" class="flex-none">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                        </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 p-6 rounded-md shadow-sm" role="alert">
                <p class="font-bold text-lg"><i class="fas fa-info-circle mr-2"></i> No hay productos</p>
                <p>No hay productos registrados aún. Utiliza el formulario de arriba para agregar el primero.</p>
            </div>
        @endforelse
    </div>
</div>

<div id="mapModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-3/4 lg:w-1/2 p-6">
        
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-stone-800">Selecciona la Ubicación</h3>
            <button type="button" onclick="cerrarModalMapa()" class="text-stone-400 hover:text-stone-600">
                <i class="fas fa-times fa-lg"></i>
            </button>
        </div>

        <div id="map" class="h-[400px] w-full rounded z-10"></div>
        <p class="text-sm text-stone-600 mt-2">Haz clic en el mapa para colocar el marcador.</p>

        <div class="mt-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <button type="button" id="btn-geolocalizacion" onclick="obtenerUbicacionActual(this)" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-crosshairs mr-2"></i>
                Usar Mi Ubicación Actual
            </button>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="cerrarModalMapa()" class="px-4 py-2 bg-stone-200 text-stone-800 rounded-md hover:bg-stone-300">
                    Cancelar
                </button>
                <button type="button" onclick="confirmarUbicacion()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    Confirmar Ubicación
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    // Variables globales
    let map;
    let marker;
    let tempLat, tempLng;
    let currentTargetFormId = null; // 'null' para "Crear", (ID) para "Actualizar"

    const mapModal = document.getElementById('mapModal');
    
    // Función de inicialización
    function inicializarMapa(initialView, initialZoom) {
        if (!map) { // Solo inicializa el mapa una vez
            map = L.map('map').setView(initialView, initialZoom);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            map.on('click', function(e) {
                const { lat, lng } = e.latlng;
                tempLat = lat;
                tempLng = lng;

                if (!marker) {
                    marker = L.marker(e.latlng).addTo(map)
                        .bindPopup('Nueva Ubicación')
                        .openPopup();
                } else {
                    marker.setLatLng(e.latlng)
                        .bindPopup('Nueva Ubicación')
                        .openPopup();
                }
            });
        } else {
            map.setView(initialView, initialZoom);
        }
    }

    // Función "inteligente" para abrir el modal
    function abrirModalMapa(targetId, lat = null, lng = null) {
        currentTargetFormId = targetId;
        
        let initialView = [-17.39, -66.15]; // Vista por defecto (Bolivia)
        let initialZoom = 13;

        tempLat = null;
        tempLng = null;

        // Si es "Actualizar" (ID no nulo) Y tiene lat/lng válidos
        if (targetId !== null && lat && lng) {
            initialView = [lat, lng];
            initialZoom = 17; 
            tempLat = lat; 
            tempLng = lng;
        }

        inicializarMapa(initialView, initialZoom);

        if(marker) {
            map.removeLayer(marker);
            marker = null;
        }

        if (tempLat && tempLng) {
            marker = L.marker([tempLat, tempLng]).addTo(map)
                .bindPopup('Ubicación Actual')
                .openPopup();
        }

        mapModal.classList.remove('hidden');
        setTimeout(function() {
            map.invalidateSize();
        }, 100);
    }

    function cerrarModalMapa() {
        mapModal.classList.add('hidden');
    }

    // Función "inteligente" para confirmar
    function confirmarUbicacion() {
        if (!tempLat || !tempLng) {
            alert('Por favor, selecciona una ubicación en el mapa haciendo clic.');
            return;
        }

        let latField, lngField, linkField, feedbackSpan;

        if (currentTargetFormId === null) {
            // Es el formulario de "Crear"
            latField = document.getElementById('latitude');
            lngField = document.getElementById('longitude');
            linkField = document.getElementById('maps_link');
            feedbackSpan = document.getElementById('coords-feedback');
        } else {
            // Es un formulario de "Actualizar"
            latField = document.getElementById('latitude-' + currentTargetFormId);
            lngField = document.getElementById('longitude-' + currentTargetFormId);
            linkField = document.getElementById('maps_link-' + currentTargetFormId);
            feedbackSpan = document.getElementById('coords-feedback-' + currentTargetFormId);
        }

        // Actualiza los campos del formulario correcto
        latField.value = tempLat;
        lngField.value = tempLng;
        linkField.value = `https://www.google.com/maps?q=${tempLat},${tempLng}`;
        
        feedbackSpan.classList.remove('hidden');
        cerrarModalMapa();
    }

    // --- Funciones de Geolocalización (Sin Cambios) ---
    function obtenerUbicacionActual(btn) {
        if (!navigator.geolocation) {
            alert('Lo sentimos, tu navegador no soporta la geolocalización.');
            return;
        }

        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Buscando...';
        btn.disabled = true;

        navigator.geolocation.getCurrentPosition(
            function(position) {
                tempLat = position.coords.latitude;
                tempLng = position.coords.longitude;

                map.setView([tempLat, tempLng], 17);

                if (!marker) {
                    marker = L.marker([tempLat, tempLng]).addTo(map)
                        .bindPopup('Ubicación encontrada')
                        .openPopup();
                } else {
                    marker.setLatLng([tempLat, tempLng])
                        .bindPopup('Ubicación encontrada')
                        .openPopup();
                }

                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }, 
            function(error) {
                handleLocationError(error);
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }
        );
    }

    function handleLocationError(error) {
        let message;
        switch(error.code) {
            case error.PERMISSION_DENIED:
                message = 'Permiso denegado. Por favor, activa los permisos de ubicación en tu navegador para usar esta función.';
                break;
            case error.POSITION_UNAVAILABLE:
                message = 'La información de la ubicación no está disponible en este momento.';
                break;
            case error.TIMEOUT:
                message = 'La solicitud de ubicación tardó demasiado (timeout).';
                break;
            default:
                message = 'Ocurrió un error desconocido al obtener la ubicación.';
        }
        alert(message);
    }
</script>
@endsection