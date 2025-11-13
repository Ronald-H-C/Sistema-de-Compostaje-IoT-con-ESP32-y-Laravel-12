@extends('layouts.app')

@section('title', 'Inicio')
@push('styles')
    <style>
        #contenedor-del-mapa {
            width: 100%;
            height: 400px;
            border-radius: 10px;
            margin-top: 20px;
        }

        /* ************** ESTILO DEL MARCADOR PERSONALIZADO (tipo pin de ubicación) ************** */
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

        /* ************** ESTILO DEL POPUP (ventana que se abre al hacer clic en marcador) ************** */
        .popup-content {
            min-width: 200px;
            text-align: center;
        }

        .popup-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            margin: 0 auto 10px;
            border: 2px solid #2e7d32;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
            background-color: white;
        }

        .popup-title {
            color: #2e7d32;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .popup-link {
            display: inline-block;
            margin-top: 5px;
            color: #2e7d32;
            font-weight: bold;
            text-decoration: none;
        }



        /* ----------- */
        /********** DIMENSIONES Y ESTILO DE MAPA ***********/
        .btn-refresh {
            background-color: #f1f1f1;
            border: 1px solid #ccc;
            color: #555;
            padding: 5px 15px;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-refresh i {
            margin-right: 5px;
        }

        @media (max-width: 768px) {
            .card {
                flex-direction: column;
                /* Columna para pantallas pequeñas */
                align-items: stretch;
                /* Estirar contenido */
            }

            .card .image-container {
                margin-bottom: 15px;
                /* Separar imagen del contenido */
                width: 100%;
                /* Ajustar imagen al ancho disponible */
                height: auto;
                /* Mantener proporciones */
            }

            .card .btn {
                width: 100%;
                /* Botón de ancho completo */
            }
        }

        #contenedor-del-mapa {
            max-height: 400px;
            /* Altura máxima del mapa */
            min-height: 200px;
            /* Altura mínima para dispositivos pequeños */
        }

        @media (max-width: 768px) {
            #contenedor-del-mapa {
                height: 300px;
                /* Ajusta la altura del mapa para dispositivos móviles */
            }
        }
    </style>
@endpush
@section('content')
    <!-- Hero Section -->
    <section class="hero-section pt-20 text-white">
        <!-- Imágenes del carrusel -->
        <div class="hero-image active" style="background-image: url('{{ asset('img/Cultivo.png') }}');"></div>
        <div class="hero-image" style="background-image: url('{{ asset('img/BeneficiosClave.png') }}');"></div>
        <div class="hero-image" style="background-image: url('{{ asset('img/Cultivo.png') }}');"></div>

        <!-- Contenido sobrepuesto -->
        <div class="relative z-10 h-full flex items-center justify-center">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-4xl md:text-5xl mb-6">COMPOSTAJE DOMÉSTICO CON
                    MONITOREO IOT</h1>
                <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto"></p>
                
            </div>
        </div>
    </section>

    <!-- Sección de características -->
    <section class="container mx-auto px-4 py-16 bg-green-50 rounded-xl">
        <h2 class="text-3xl font-bold text-center mb-12 text-green-800">¿Por qué hacer Composta?</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Tarjeta 1: Importancia -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                <img src="img/CompostarSembrar.png" alt="Beneficios de la composta"
                    class="w-full h-48 object-cover rounded-t-lg mb-4">
                <h3 class="text-xl font-semibold mb-3 text-green-700 text-center">🌱 Compostar es sembrar esperanza</h3>
                <p class="text-gray-700">
                    La composta convierte residuos orgánicos (como cáscaras de fruta o hojas secas) en abono natural,
                    reduciendo hasta un 50% los desechos en hogares y evitando emisiones de metano en vertederos.
                </p>
            </div>

            <!-- Tarjeta 2: Proceso -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                <img src="img/ComoseHace.png" alt="Proceso de compostaje"
                    class="w-full h-48 object-cover rounded-t-lg mb-4">
                <h3 class="text-xl font-semibold mb-3 text-green-700">♻️ ¿Cómo se hace?</h3>
                <ul class="list-disc pl-5 text-gray-700 space-y-2">
                    <li><strong>Materiales:</strong> Restos de comida, hojas secas, cáscaras de huevo.</li>
                    <li><strong>Evita:</strong> Carnes, lácteos o aceites.</li>
                    <li><strong>Proceso:</strong> Capas alternas de materia verde (nitrógeno) y marrón (carbono).</li>
                    <li><strong>Tiempo:</strong> 2 a 6 meses con riego y aireación.</li>
                </ul>
            </div>

            <!-- Tarjeta 3: Beneficios -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                <img src="img/BeneficiosClave.png" alt="Suelo fértil" class="w-full h-48 object-cover rounded-t-lg mb-4">
                <h3 class="text-xl font-semibold mb-3 text-green-700">💡 Beneficios Clave</h3>
                <div class="space-y-3 text-gray-700">
                    <p><strong class="text-green-600">+ Fertilidad:</strong> Enriquece el suelo con nutrientes.</p>
                    <p><strong class="text-green-600">+ Ahorro:</strong> Reduce necesidad de químicos.</p>
                    <p><strong class="text-green-600">+ Biodiversidad:</strong> Atrae microorganismos beneficiosos.</p>
                </div>
            </div>
        </div>

        <!-- Llamado a la acción -->
        <div class="text-center mt-12">
            <a href="{{ asset('manuales/guia-completa.pdf') }}" download
                class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-full transition-colors inline-block">
                ¡Descargar Guía Completa!
            </a>
        </div>

    </section>
    <!-- Sección de Productos -->
    <section class="productos-section">
        <div class="container mx-auto px-4 mt-5">
            <h2 class="text-black text-center my-4 mb-5 text-2xl font-bold">Productos de Abono Disponibles</h2>

            <!-- Botones para cambiar disposición -->
            <div class="flex justify-end my-3">
                <button id="btn-horizontal"
                    class="px-4 py-2 border border-primary text-primary rounded-lg mr-2 hover:bg-primary hover:text-white transition">
                    <i class="fas fa-list mr-2"></i> Horizontal
                </button>
                <button id="btn-vertical"
                    class="px-4 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition">
                    <i class="fas fa-th-large mr-2"></i> Vertical
                </button>
            </div>

            <!-- Listado de Productos -->
            <div class="container mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-5" id="cards-container">
                    @foreach ($productos as $producto)
                        <div class="mb-4 card-item">
                            <div
                                class="card p-3 bg-white rounded-lg shadow-md h-full flex flex-col justify-between transition-all duration-300">
                                <div
                                    class="w-full max-w-[200px] h-[200px] flex items-center justify-center bg-white border-2 border-gray-200 rounded-lg overflow-hidden mx-auto">
                                    <img src="{{ asset($producto->image ?? 'images/default-product.png') }}"
                                        class="max-w-full max-h-full object-contain" alt="{{ $producto->title }}">
                                </div>
                                <div class="flex-grow mt-3">
                                    <h5 class="mb-1 font-bold text-center">{{ $producto->title }}</h5>
                                    <span class="text-primary font-bold block text-center capitalize">
                                        {{ str_replace('_', ' ', $producto->type) }}
                                    </span>
                                    <div class="flex justify-between my-2">
                                        <span class="text-lg font-bold text-gray-800">
                                            <i class="fas fa-tag mr-1"></i> Bs {{ number_format($producto->price, 2) }}
                                        </span>
                                        <span class="text-lg font-bold text-gray-800">
                                            <i class="fas fa-weight-hanging mr-1"></i> {{ $producto->amount }} kg
                                        </span>
                                    </div>
                                    <p class="mb-1 text-sm text-gray-600 text-center">
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $producto->user->name ?? 'Vendedor no especificado' }}
                                    </p>
                                    <p class="mb-1 text-sm text-black text-center">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        {{ $producto->location->address ?? 'Ubicación no especificada' }}
                                    </p>
                                    @if ($producto->location && $producto->location->latitud && $producto->location->longitud)
                                        <div class="mt-2 text-center">
                                            <a href="{{ route('map.ver', ['lat' => $producto->location->latitud, 'lng' => $producto->location->longitud]) }}"
                                                class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                                rel="noopener noreferrer">
                                                <i class="fas fa-map-marked-alt mr-1"></i> Ver en mapa
                                            </a>
                                        </div>
                                    @endif

                                </div>
                                <div class="mt-3 flex space-x-2">
                                    <a href="{{ route('products.userProducts', ['id' => $producto->user->id]) }}"
                                        class="flex-1 bg-primary hover:bg-secondary text-white text-center py-2 px-4 rounded-lg transition">
                                        Ver Producto
                                    </a>

                                    <a href="{{ route('products.userProducts', ['id' => $producto->user->id]) }}"
                                        class="bg-green-600 hover:bg-green-700 text-white py-2 px-3 rounded-lg transition inline-block text-center">
                                        <i class="fas fa-shopping-cart"></i>
                                    </a>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 flex justify-center">
                <nav role="navigation" aria-label="Pagination Navigation"
                    class="inline-flex items-center space-x-2 text-sm font-medium">

                    @if ($productos->onFirstPage())
                        <span class="px-3 py-2 rounded-lg bg-gray-300 text-white cursor-not-allowed">
                            <i class="fas fa-angle-left mr-1"></i> Anterior
                        </span>
                    @else
                        <a href="{{ $productos->previousPageUrl() }}"
                            class="px-3 py-2 rounded-lg bg-primary hover:bg-secondary text-white transition">
                            <i class="fas fa-angle-left mr-1"></i> Anterior
                        </a>
                    @endif

                    {{-- Números de página --}}
                    @foreach ($productos->getUrlRange(1, $productos->lastPage()) as $page => $url)
                        <a href="{{ $url }}"
                            class="px-3 py-2 rounded-lg {{ $productos->currentPage() === $page ? 'bg-secondary text-white' : 'bg-white text-gray-700 hover:bg-gray-200' }}">
                            {{ $page }}
                        </a>
                    @endforeach

                    @if ($productos->hasMorePages())
                        <a href="{{ $productos->nextPageUrl() }}"
                            class="px-3 py-2 rounded-lg bg-primary hover:bg-secondary text-white transition">
                            Próximo <i class="fas fa-angle-right ml-1"></i>
                        </a>
                    @else
                        <span class="px-3 py-2 rounded-lg bg-gray-300 text-white cursor-not-allowed">
                            Próximo <i class="fas fa-angle-right ml-1"></i>
                        </span>
                    @endif
                </nav>
            </div>

        </div>
    </section>

    <!-- Sección de Filtros y Mapa -->
    <div class="mt-5 mx-auto max-w-7xl px-4">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold text-center mb-6"
                style="background: linear-gradient(145deg, #002600, #007500, #008f00); -webkit-background-clip: text; background-clip: text; color: transparent;">
                Productos de Abono Disponibles
            </h2>

            <!-- Filtros -->
            <div class="flex justify-center mb-6">
                <div class="w-full md:w-4/5">
                    <div class="bg-gradient-to-r from-green-700 to-green-800 p-6 rounded-xl shadow-md">
                        <h3 class="text-xl font-semibold text-white text-center mb-4">Buscar productos</h3>

                        <form method="GET" action="{{ route('index') }}" id="busqueda-form" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="titulo" class="block text-white mb-2 font-medium">Nombre del
                                        producto</label>
                                    <input type="text" id="titulo" name="title" value="{{ $filtroTitulo }}"
                                        class="w-full px-4 py-2 pl-10 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500"
                                        placeholder="Ej: Composta orgánica">
                                </div>

                                <div>
                                    <label for="tipo" class="block text-white mb-2 font-medium">Tipo de abono</label>
                                    <select id="tipo" name="type"
                                        class="w-full px-4 py-2 pl-10 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 bg-white">
                                        <option value="">Todos los tipos</option>
                                        <option value="composta" {{ $filtroTipo == 'composta' ? 'selected' : '' }}>
                                            Composta
                                        </option>
                                        <option value="humus" {{ $filtroTipo == 'humus' ? 'selected' : '' }}>Humus
                                        </option>
                                        <option value="abono_organico"
                                            {{ $filtroTipo == 'abono_organico' ? 'selected' : '' }}>Abono Orgánico</option>
                                        <option value="otro" {{ $filtroTipo == 'otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="precio_max" class="block text-white mb-2 font-medium">Precio máximo
                                        (Bs/kg)</label>
                                    <input type="number" id="precio_max" name="precio_max" step="0.5"
                                        min="0" value="{{ $filtroPrecioMax }}"
                                        class="w-full px-4 py-2 pl-10 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500"
                                        placeholder="Ej: 15.50">
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row justify-center gap-4 pt-2">
                                <button type="submit"
                                    class="bg-white text-green-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-50 transition-colors shadow-sm">
                                    <i class="bi bi-search mr-2"></i> Buscar Productos
                                </button>
                                <button type="button" id="restablecerBtn"
                                    class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-200 transition-colors shadow-sm">
                                    <i class="bi bi-arrow-clockwise mr-2"></i> Limpiar Filtros
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mapa -->
            <div class="bg-gradient-to-r from-green-700 to-green-800 p-6 rounded-xl shadow-md">
                <div class="text-center mb-4">
                    <p class="text-white text-lg">Visualiza los productos disponibles en el mapa interactivo</p>
                </div>
                <div id="contenedor-del-mapa" class="w-full rounded-xl shadow-md" style="height: 500px;"></div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var map = L.map('contenedor-del-mapa').setView([-17.3936, -66.1570], 10);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            var productos = @json($productos->items());

            productos.forEach(function(producto) {
                if (producto.location && producto.location.latitude && producto.location.longitude) {
                    var imagenProducto = producto.image ?
                        '{{ asset('') }}' + producto.image :
                        'https://via.placeholder.com/40';
                    var customIcon = L.divIcon({
                        html: `
                        <div class="marker-wrapper">
                        <img src="${producto.image}" alt="${producto.title}" class="marker-img">
                         </div> `,
                        className: 'custom-marker',
                        iconSize: [58, 58]
                    });

                    const baseMapaUrl = "{{ url('mapa') }}";

                    var marker = L.marker([producto.location.latitude, producto.location.longitude], {
                        icon: customIcon
                    }).addTo(map);

                    marker.bindPopup(`
                        <div class="popup-content">
                         <img src="${producto.image}" alt="${producto.title}" class="popup-img">
                         <h6 class="popup-title">${producto.title}</h6>
                         <p><strong>Tipo:</strong> ${producto.type.replace('_', ' ')}</p>
                         <p><strong>Precio:</strong> $${parseFloat(producto.price).toFixed(2)}</p>
                         <p><strong>Cantidad:</strong> ${producto.amount} kg</p>
                         <a href="${baseMapaUrl}?lat=${producto.location.latitude}&lng=${producto.location.longitude}">

                        <i class="fas fa-map-marked-alt"></i> Ver en Google Maps
                            </a>
                        </div>
                    `);

                }
            });
        });
    </script>

@endsection
