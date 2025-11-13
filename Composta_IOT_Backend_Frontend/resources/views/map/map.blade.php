@extends('layouts.app')

@section('title', 'Detalle del Producto en el Mapa')
@push('styles')
    <style>
        #mapa-detalle {
            width: 100%;
            height: 600px;
            border-radius: 0.5rem;
            z-index: 0;
        }

        .custom-marker {
            position: relative;
            width: 50px;
            height: 70px;
            background-color: transparent;
        }

        /* Contenedor circular */
        .custom-marker .marker-wrapper {
            position: absolute;
            top: -2px;
            left: 5px;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 3px solid #ee0000;
            box-shadow: 0 0 5px rgba(255, 1, 1, 0.5);
            background-color: white;
            overflow: hidden;
            /* 👈 Recorta cualquier exceso de la img */
        }

        /* Imagen dentro del círculo */
        .custom-marker .marker-wrapper .marker-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* 👈 Ajusta proporcionalmente sin deformar */
            display: block;
        }

        /* Triángulo tipo pin debajo */
        .custom-marker::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 15px 10px 0 10px;
            border-color: #ee0000 transparent transparent transparent;
        }

        /*  */
        .position-arrow {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .arrow-top {
            position: absolute;
            top: 0;
            left: 5px;
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 0 10px 20px 10px;
            border-color: transparent transparent #3388ff transparent;
            filter: drop-shadow(0px 5px 7px rgba(0, 0, 0, 0.4));
            transform: scale(1.2);
        }

        .arrow-bottom {
            position: absolute;
            top: 20px;
            left: 12px;
            width: 6px;
            height: 10px;
            background: linear-gradient(to bottom, #3388ff, #2266cc);
            border-radius: 3px;
            box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.3);
            transform: rotate(5deg);
        }

        .mini-route-panel {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            padding: 12px;
            font-size: 14px;
            min-width: 200px;
            z-index: 1000;
        }

        .leaflet-routing-container {
            display: none !important;
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .leaflet-marker-icon {
            transition: transform 0.3s ease;
        }

        .leaflet-circle {
            stroke: true;
            color: '#3388ff';
            weight: 1;
            opacity: 1;
            fill: true;
            fillColor: '#3388ff';
            fillOpacity: 0.2;
        }
    </style>
@endpush
@section('content')


    <!-- CONTENEDOR -->
    <div class="min-h-screen flex flex-col bg-gray-50">
        <a href="{{ route('index') }}"
            class="bg-green-500 text-white hover:bg-green-600 px-4 py-2 rounded flex items-center w-max ml-4 mt-4">
            <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>

        <main class="flex-grow container mx-auto p-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white rounded-lg shadow-md overflow-hidden">
                    <div id="mapa-detalle" class="w-full"></div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    @if ($product)
                        <div class="mb-6">
                            <div class="flex items-start space-x-4 mb-4">
                                @if ($product->image)
                                    <img src="{{ asset($product->image ?? 'images/default-product.png') }}"
                                        alt="{{ $product->title }}" class="w-20 h-20 object-cover rounded-lg">
                                @else
                                    <div
                                        class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                                        <i class="fas fa-image text-2xl"></i>
                                    </div>
                                @endif
                                <div>
                                    <h2 class="text-xl font-bold text-green-800">{{ $product->title }}</h2>
                                    <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                        {{ ucfirst(str_replace('_', ' ', $product->type)) }}
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Precio:</span>
                                    <span class="font-bold">Bs. {{ number_format($product->price, 2) }} /kg</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Disponible:</span>
                                    <span class="font-bold">{{ number_format($product->amount, 2) }} kg</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Vendedor:</span>
                                    <span class="font-bold">{{ $product->user->name ?? 'No disponible' }}</span>
                                </div>
                                @if ($product->user->telefono ?? false)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Contacto:</span>
                                        <a href="tel:{{ $producto->user->telefono }}"
                                            class="font-bold text-green-600 hover:text-green-800">
                                            {{ $product->user->telefono }}
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <p class="mt-4 text-gray-700 whitespace-pre-line">
                                {{ $product->description ?? 'Sin descripción adicional' }}
                            </p>
                        </div>

                        <div class="space-y-3">
                            <button id="iniciarRutaBtn"
                                class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg flex items-center justify-center">
                                <i class="fas fa-route mr-2"></i> Iniciar Ruta
                            </button>

                            <button id="compartirBtn"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg flex items-center justify-center">
                                <i class="fas fa-share-alt mr-2"></i> Compartir Ubicación
                            </button>

                            <button id="favoritosBtn"
                                class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded-lg flex items-center justify-center">
                                <i class="fas fa-star mr-2"></i> Guardar en Favoritos
                            </button>

                            <button id="reportarBtn"
                                class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-lg flex items-center justify-center">
                                <i class="fas fa-flag mr-2"></i> Reportar Problema
                            </button>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-triangle text-4xl text-yellow-500 mb-4"></i>
                            <h2 class="text-xl font-bold text-gray-700">Producto no encontrado</h2>
                            @if (!$lat || !$lng)
                                <div class="bg-yellow-100 text-yellow-800 p-4 rounded mt-4">
                                    Las coordenadas de este producto no están disponibles o son inválidas.
                                </div>
                            @endif

                            <p class="text-gray-600 mt-2">No se encontró información del producto para esta ubicación.</p>
                            <a href="{{ route('index') }}"
                                class="inline-block mt-4 bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700">
                                Volver al mapa principal
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

@endsection
@section('scripts')
    <script>
        const lat = @json($lat);
        const lng = @json($lng);

        if (lat && lng) {
            // 1. Inicia el mapa centrado en el producto
            const map = L.map('mapa-detalle').setView([lat, lng], 15);

            // 2. Capa base OSM
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // 3. Icono del producto (no se modifica)
            const productIcon = L.divIcon({
                className: 'custom-marker',
                html: `
        <div class="marker-wrapper">
            <img src="{{ $product['image'] ? asset($product['image']) : 'https://via.placeholder.com/40' }}"
                 alt="{{ e($product['title'] ?? 'Producto') }}"
                 class="marker-img">
        </div>
    `,
                iconSize: [58, 58],
            });


            L.marker([lat, lng], {
                    icon: productIcon
                })
                .addTo(map)
                .bindPopup(
                    `<div style="text-align:center;min-width:200px;"><strong><h6 class="text-center" style="color:#2e7d32;margin-bottom:5px;">{{ e($producto['title'] ?? 'Producto') }}</h6></strong></div>`
                );

            // ======================
            //  NUEVO ICONO DEL USUARIO
            // ======================
            const userIcon = L.icon({
                iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png', // pin azul
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41], // la punta del pin
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            let routeControl = null;
            let positionMarker = null;
            let positionCircle = null;
            let miniPanel = null;
            let watchId = null;

            const iniciarRutaBtn = document.getElementById('iniciarRutaBtn');
            if (iniciarRutaBtn) {
                iniciarRutaBtn.addEventListener('click', function() {
                    if (!navigator.geolocation) {
                        alert('La geolocalización no está disponible en este navegador.');
                        return;
                    }

                    const btn = this;
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-sync-alt animate-spin mr-2"></i> Calculando ruta...';
                    btn.disabled = true;

                    // Limpia si ya había algo
                    if (watchId) navigator.geolocation.clearWatch(watchId);
                    if (routeControl) map.removeControl(routeControl);
                    if (positionMarker) map.removeLayer(positionMarker);
                    if (positionCircle) map.removeLayer(positionCircle);
                    if (miniPanel) map.removeControl(miniPanel);

                    // Empieza a vigilar la posición
                    watchId = navigator.geolocation.watchPosition(
                        position => {
                            const latUser = position.coords.latitude;
                            const lngUser = position.coords.longitude;
                            const accuracy = Number(position.coords.accuracy);

                            if (
                                typeof latUser !== 'number' || isNaN(latUser) ||
                                typeof lngUser !== 'number' || isNaN(lngUser) ||
                                isNaN(accuracy) || accuracy <= 0
                            ) {
                                console.warn('Datos de geolocalización inválidos', {
                                    latUser,
                                    lngUser,
                                    accuracy
                                });
                                return;
                            }

                            const userLatLng = [latUser, lngUser];

                            // -------- MARCADOR DEL USUARIO --------
                            if (!positionMarker) {
                                positionMarker = L.marker(userLatLng, {
                                        icon: userIcon // ¡Aquí usamos el pin azul!
                                    })
                                    .addTo(map)
                                    .bindPopup('Tu posición actual');
                            } else {
                                positionMarker.setLatLng(userLatLng);
                            }

                            // -------- CÍRCULO DE PRECISIÓN --------
                            if (!positionCircle) {
                                positionCircle = L.circle(userLatLng, {
                                    radius: accuracy,
                                    color: '#3388ff',
                                    fillColor: '#3388ff',
                                    fillOpacity: 0.2
                                }).addTo(map);
                            } else {
                                positionCircle.setLatLng(userLatLng).setRadius(accuracy);
                            }

                            // -------- CALCULA LA RUTA 1 sola vez --------
                            if (!routeControl) {
                                calculateRoute(userLatLng, [lat, lng], btn, originalText);
                            }
                        },
                        error => {
                            console.error('Error en geolocalización:', error);
                            alert('Error al obtener tu ubicación: ' + error.message);
                            btn.innerHTML = originalText;
                            btn.disabled = false;
                        }, {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0
                        }
                    );
                });
            }

            function calculateRoute(start, end, btn, originalText) {
                routeControl = L.Routing.control({
                    waypoints: [L.latLng(start), L.latLng(end)],
                    routeWhileDragging: false,
                    show: false,
                    addWaypoints: false,
                    draggableWaypoints: false,
                    lineOptions: {
                        styles: [{
                            color: '#4a80f5',
                            opacity: 0.8,
                            weight: 6
                        }]
                    },
                    createMarker: () => null
                }).addTo(map);

                routeControl.on('routesfound', function(e) {
                    const summary = e.routes[0].summary;

                    miniPanel = L.control({
                        position: 'bottomleft'
                    });
                    miniPanel.onAdd = function() {
                        const div = L.DomUtil.create('div', 'mini-route-panel');
                        div.innerHTML = `
                        <div class=\"flex justify-between items-center mb-1\">
                            <strong class=\"text-blue-700\">Ruta calculada</strong>
                            <button class=\"close-panel text-gray-500 hover:text-gray-700\">
                                <i class=\"fas fa-times\"></i>
                            </button>
                        </div>
                        <div class=\"text-sm\">
                            <span>Distancia: ${(summary.totalDistance / 1000).toFixed(1)} km</span><br>
                            <span>Tiempo: ${Math.floor(summary.totalTime / 3600)}h ${Math.floor((summary.totalTime % 3600) / 60)}min</span>
                        </div>
                        <div class=\"mt-2\">
                            <button id=\"stopTrackingBtn\" class=\"text-sm text-red-500 hover:text-red-700\">
                                <i class=\"fas fa-map-marker-alt mr-1\"></i> Detener seguimiento
                            </button>
                        </div>
                    `;
                        L.DomEvent.on(div.querySelector('.close-panel'), 'click', clearRoute);
                        L.DomEvent.on(div.querySelector('#stopTrackingBtn'), 'click', clearRoute);
                        return div;
                    };
                    miniPanel.addTo(map);

                    map.fitBounds(L.latLngBounds([start, end]), {
                        padding: [50, 50]
                    });

                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
            }

            function clearRoute() {
                if (watchId) navigator.geolocation.clearWatch(watchId);
                if (routeControl) map.removeControl(routeControl);
                if (positionMarker) map.removeLayer(positionMarker);
                if (positionCircle) map.removeLayer(positionCircle);
                if (miniPanel) map.removeControl(miniPanel);

                watchId = null;
                routeControl = null;
                positionMarker = null;
                positionCircle = null;
                miniPanel = null;
            }
        } else {
            console.error('Coordenadas no válidas.');
        }
        const compartirBtn = document.getElementById('compartirBtn');
        if (compartirBtn) {
            compartirBtn.addEventListener('click', async () => {
                if (!navigator.geolocation) {
                    alert("La geolocalización no está disponible.");
                    return;
                }

                navigator.geolocation.getCurrentPosition(position => {
                    const {
                        latitude,
                        longitude
                    } = position.coords;
                    const link = `https://www.google.com/maps?q=${latitude},${longitude}`;

                    if (navigator.share) {
                        navigator.share({
                            title: 'Mi ubicación actual',
                            text: 'Estoy aquí:',
                            url: link
                        }).catch(console.error);
                    } else {
                        prompt("Copia este enlace y compártelo:", link);
                    }
                }, () => {
                    alert("No se pudo obtener la ubicación.");
                });
            });
        }

        // ✅ Guardar en favoritos (localStorage)
        const favoritosBtn = document.getElementById('favoritosBtn');
        if (favoritosBtn) {
            favoritosBtn.addEventListener('click', () => {
                const favorito = {
                    lat: lat,
                    lng: lng,
                    title: "{{ $product['title'] ?? 'Producto' }}",
                    url: window.location.href
                };

                let favoritos = JSON.parse(localStorage.getItem('favoritos')) || [];
                favoritos.push(favorito);
                localStorage.setItem('favoritos', JSON.stringify(favoritos));

                alert("¡Agregado a favoritos!");
            });
        }

        // ✅ Reportar problema (simple modal o redirección)
        const reportarBtn = document.getElementById('reportarBtn');
        if (reportarBtn) {
            reportarBtn.addEventListener('click', () => {
                const mensaje = prompt("Describe el problema con este producto:");
                if (mensaje) {
                    // Aquí podrías enviar a una API o registrar en consola
                    console.log("Reporte enviado:", mensaje);
                    alert("Gracias por tu reporte. Lo revisaremos pronto.");
                }
            });
        }
    </script>

@endsection
