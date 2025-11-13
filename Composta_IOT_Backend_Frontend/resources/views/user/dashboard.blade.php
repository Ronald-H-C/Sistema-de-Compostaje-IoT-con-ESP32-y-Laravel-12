<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Compostaje IoT - Villa Israel</title>

    <!-- CSS FILES -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <!-- Bootstrap CSS desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2e7d32',
                        secondary: '#1b5e20',
                        tertiary: '#a7f3a0',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 font-sans">
    <!-- Barra de navegación superior -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-gray-800 ps-16">
        <a class="navbar-brand ps-3" href="/admin/dashboard/">Inicio</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle">
            <i class="fas fa-bars text-white"></i>
        </button>

        <ul class="flex items-center gap-4 ms-auto me-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user fa-fw text-white"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li>
                        <button onclick="document.getElementById('editModal').showModal()" class="dropdown-item">Editar Perfil</button>
                    </li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li>
                        <button onclick="document.getElementById('editModalR').showModal()" class="dropdown-item">Agregar Referencias</button>
                    </li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li>
                        <!-- Botón para descargar el APK -->
                        <a href="{{ asset('Composta_IoT_APK/compost.apk') }}" class="dropdown-item" download>
                            Descargar APK
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">Cerrar Sesión</button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>

        <dialog id="editModalR" class="rounded-xl p-0 max-w-2xl w-full shadow-xl backdrop:bg-black/40 z-50">
            <form method="POST" action="{{ route('references.store') }}" enctype="multipart/form-data"
                class="bg-white p-6 rounded-xl space-y-5">
                @csrf
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-address-book text-green-600"></i> Agregar Referencias
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Teléfono --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <div class="flex items-center border rounded-md mt-1 focus-within:ring focus-within:border-blue-300">
                            <i class="fas fa-phone text-gray-400 px-2"></i>
                            <input type="text" name="phone" placeholder="Ej: +591 71234567"
                                class="w-full p-2 outline-none rounded-r-md"
                                value="{{ old('phone', auth()->user()->reference->phone ?? '') }}">
                        </div>
                    </div>

                    {{-- Correo --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700">Correo de Contacto</label>
                        <div class="flex items-center border rounded-md mt-1 focus-within:ring focus-within:border-blue-300">
                            <i class="fas fa-envelope text-gray-400 px-2"></i>
                            <input type="email" name="contact_email" placeholder="ejemplo@correo.com"
                                class="w-full p-2 outline-none rounded-r-md"
                                value="{{ old('contact_email', auth()->user()->reference->contact_email ?? '') }}">
                        </div>
                    </div>

                    {{-- WhatsApp --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700">WhatsApp</label>
                        <div class="flex items-center border rounded-md mt-1 focus-within:ring focus-within:border-green-400">
                            <i class="fab fa-whatsapp text-green-500 px-2"></i>
                            <input type="url" name="whatsapp_link" placeholder="https://wa.me/591..."
                                class="w-full p-2 outline-none rounded-r-md"
                                value="{{ old('whatsapp_link', auth()->user()->reference->whatsapp_link ?? '') }}">
                        </div>
                    </div>

                    {{-- Facebook --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700">Facebook</label>
                        <div class="flex items-center border rounded-md mt-1 focus-within:ring focus-within:border-blue-600">
                            <i class="fab fa-facebook text-blue-600 px-2"></i>
                            <input type="url" name="facebook_link" placeholder="https://facebook.com/usuario"
                                class="w-full p-2 outline-none rounded-r-md"
                                value="{{ old('facebook_link', auth()->user()->reference->facebook_link ?? '') }}">
                        </div>
                    </div>

                    {{-- Instagram --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700">Instagram</label>
                        <div class="flex items-center border rounded-md mt-1 focus-within:ring focus-within:border-pink-500">
                            <i class="fab fa-instagram text-pink-500 px-2"></i>
                            <input type="url" name="instagram_link" placeholder="https://instagram.com/usuario"
                                class="w-full p-2 outline-none rounded-r-md"
                                value="{{ old('instagram_link', auth()->user()->reference->instagram_link ?? '') }}">
                        </div>
                    </div>

                    {{-- YouTube --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700">YouTube</label>
                        <div class="flex items-center border rounded-md mt-1 focus-within:ring focus-within:border-red-500">
                            <i class="fab fa-youtube text-red-500 px-2"></i>
                            <input type="url" name="youtube_link" placeholder="https://youtube.com/c/usuario"
                                class="w-full p-2 outline-none rounded-r-md"
                                value="{{ old('youtube_link', auth()->user()->reference->youtube_link ?? '') }}">
                        </div>
                    </div>

                    {{-- TikTok --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700">TikTok</label>
                        <div class="flex items-center border rounded-md mt-1 focus-within:ring focus-within:border-black">
                            <i class="fab fa-tiktok text-black px-2"></i>
                            <input type="url" name="tiktok_link" placeholder="https://tiktok.com/@usuario"
                                class="w-full p-2 outline-none rounded-r-md"
                                value="{{ old('tiktok_link', auth()->user()->reference->tiktok_link ?? '') }}">
                        </div>
                    </div>

                    {{-- Imagen QR --}}
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Imagen QR</label>
                        @if(!empty(auth()->user()->reference->qr_image))
                        <div class="mb-2">
                            <img src="{{ asset(auth()->user()->reference->qr_image) }}" alt="QR Image"
                                class="max-h-40 rounded-md shadow-md">
                        </div>
                        @endif
                        <input type="file" name="qr_image" accept="image/*"
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" onclick="document.getElementById('editModalR').close()"
                        class="flex items-center gap-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit"
                        class="flex items-center gap-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </form>
        </dialog>

        <!-- Modal: Editar Perfil -->
        <!-- Modal: Editar Perfil -->
        <dialog id="editModal" class="rounded-3xl p-0 max-w-xl w-full shadow-2xl backdrop:bg-black/40 z-50">
            <form method="POST" action="{{ route('profile.update') }}" class="bg-white p-6 rounded-3xl space-y-6">
                @csrf
                @method('PUT')

                <!-- Título -->
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3 mb-6">
                    <i class="fas fa-user-edit text-green-600"></i> Editar Perfil
                </h2>

                <!-- Campos: grid responsive 2 columnas -->
                @php
                $fields = [
                ['name'=>'name','label'=>'Nombre','icon'=>'fa-user','required'=>true],
                ['name'=>'firstLastName','label'=>'Primer Apellido','icon'=>'fa-user','required'=>true],
                ['name'=>'secondLastName','label'=>'Segundo Apellido','icon'=>'fa-user','required'=>false],
                ['name'=>'username','label'=>'Usuario','icon'=>'fa-user-circle','required'=>true],
                ['name'=>'email','label'=>'Correo Electrónico','icon'=>'fa-envelope','required'=>true],
                ];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach($fields as $field)
                    <div class="relative group">
                        <i class="fas {{ $field['icon'] }} absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-green-600 transition-colors duration-300"></i>
                        <input type="{{ $field['name']=='email'?'email':'text' }}"
                            name="{{ $field['name'] }}"
                            value="{{ old($field['name'], auth()->user()->{$field['name']}) }}"
                            {{ $field['required'] ? 'required' : '' }}
                            placeholder=" "
                            class="peer w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-500 transition text-gray-800" />
                        <label class="absolute left-10 -top-2.5 text-gray-500 text-sm peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base transition-all duration-300">
                            {{ $field['label'] }}
                        </label>
                    </div>
                    @endforeach
                </div>

                <!-- Acciones -->
                <div class="flex flex-col md:flex-row justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('editModal').close()"
                        class="flex items-center justify-center gap-2 px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-xl shadow transition duration-300">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit"
                        class="flex items-center justify-center gap-2 px-5 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg transition duration-300">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </form>
        </dialog>



    </nav>

    <!-- Estructura principal -->
    <div id="layoutSidenav">
        <!-- Barra lateral -->
        <div id="layoutSidenav_nav" class="fixed top-0 left-0 h-screen w-64 bg-gray-800 text-white">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav pt-4">
                        <div class="sb-sidenav-menu-heading text-white">Menú Principal</div>

                        <a class="nav-link" href="/user/dashboard">
                            <div class="sb-nav-link-icon "><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>

                        <a class="nav-link text-white" href="/sales">
                            <div class="sb-nav-link-icon "><i class="fas fa-shopping-cart"></i></div>
                            Historial de Ventas
                        </a>

                        <a class="nav-link text-white" href="{{route('vista')}}">
                            <div class="sb-nav-link-icon text-white"><i class="fas fa-boxes"></i></div>
                            Gestión de Productos
                        </a>

                        <a class="nav-link" href="{{route('deployC')}}">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                            Gestión de Comprobantes de Pago
                        </a>

                        <a class="nav-link" href="{{route('materiales.index')}}">
                            <div class="sb-nav-link-icon"><i class="fas fa-leaf"></i></div>
                            Materiales Compostables
                        </a>

                        <a class="nav-link" href="{{route('uplans.index')}}">
                            <div class="sb-nav-link-icon"><i class="fas fa-credit-card"></i></div>
                            Adquirir Planes
                        </a>

                        <a class="nav-link" href="{{route('select')}}">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                            Gestión de Reportes
                        </a>

                        <div class="sb-sidenav-menu-heading text-white">Configuración</div>
                        <a class="nav-link" href="{{route('cambiarCU')}}">
                            <div class="sb-nav-link-icon"><i class="fas fa-key"></i></div>
                            Cambiar Contraseña
                        </a>

                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small text-white">Bienvenid@:</div>
                    <p>{{ auth()->user()->username }}</p>

                     {{-- Mostrar Plan Activo --}}
                @if(auth()->user()->activePlan && auth()->user()->activePlan->plan)
                    <div class="small text-gray-400 mt-2">Plan Actual:</div>
                    <p class="font-semibold text-green-400 truncate">
                       <i class="fas fa-check-circle text-xs mr-1"></i> {{ auth()->user()->activePlan->plan->name }}
                    </p>
                    {{-- Opcional: Mostrar expiración --}}
                     <p class="text-xs text-gray-500 mt-1">
                        Expira: {{ \Carbon\Carbon::parse(auth()->user()->activePlan->expires_at)->format('d/m/Y') }}
                    </p> 
                @else
                     {{-- Mostrar si es admin o si no tiene plan --}}
                     @if(auth()->user()->isAdmin())
                        <div class="small text-gray-400 mt-2">Rol:</div>
                        <p class="font-semibold text-blue-400 truncate">
                            <i class="fas fa-user-shield text-xs mr-1"></i> Administrador
                        </p>
                     @else
                        <div class="small text-gray-400 mt-2">Sin plan activo</div>
                     @endif
                @endif
                </div>
            </nav>
        </div>



        
             @yield('content')
        


    </div>
        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
        <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

        <script>
            // Toggle sidebar
            document.addEventListener('DOMContentLoaded', function() {
                const sidebarToggle = document.getElementById('sidebarToggle');
                const layoutSidenav = document.getElementById('layoutSidenav_nav');
                const layoutSidenavContent = document.getElementById('layoutSidenav_content');
                const body = document.body;

                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();

                    if (window.getComputedStyle(layoutSidenav).transform === 'matrix(1, 0, 0, 1, 0, 0)') {
                        layoutSidenav.style.transform = 'translateX(-225px)';
                        layoutSidenavContent.style.marginLeft = '0';
                    } else {
                        layoutSidenav.style.transform = 'translateX(0)';
                        layoutSidenavContent.style.marginLeft = '225px';
                    }
                });

                // Carrusel de imágenes
                const images = document.querySelectorAll('.hero-image');
                let currentIndex = 0;

                function changeImage() {
                    images.forEach(img => img.classList.remove('active'));
                    currentIndex = (currentIndex + 1) % images.length;
                    images[currentIndex].classList.add('active');
                }

                setInterval(changeImage, 5000);

                // Mapa
                var map = L.map('contenedor-del-mapa').setView([-17.3936, -66.1570], 10);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                // Restablecer mapa
                document.getElementById('restablecerBtn').addEventListener('click', function() {
                    const url = new URL(window.location.href);
                    url.searchParams.delete('titulo');
                    url.searchParams.delete('categoria');
                    window.location.href = url.toString();
                });
            });
        </script>
</body>

</html>