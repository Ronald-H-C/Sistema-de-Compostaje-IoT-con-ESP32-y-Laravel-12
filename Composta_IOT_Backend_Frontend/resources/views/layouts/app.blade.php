<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Compostaje IoT - Villa Israel</title>
    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <!-- Leaflet Routing Machine -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">






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

    <style>
        /* PAGINACION */
        /* **************/
        /* ***************POR VERSE ************************ */
        /* Efectos para el header */
        #mainHeader {
            transition: transform 0.4s cubic-bezier(0.25, 0.8, 0.5, 1);
            will-change: transform;
        }

        #mainHeader.hide {
            transform: translateY(-100%);
        }

        #mainHeader.scrolled {
            background: linear-gradient(to right, rgba(27, 94, 32, 0.98), rgba(46, 125, 50, 0.98));
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Efecto 3d para las letras del logotipo */

        /* Compostaje: verde suave con profundidad */
        .text-3d-green {
            /* color: #04a773; */
            color: #ffffff;
            text-shadow:
                0 1px 0 #047857,
                0 2px 0 #0b4635,
                0 3px 0 #123f33,
                0 0 10px rgba(10, 17, 27, 0.5),
                0 4px 6px rgba(0, 0, 0, 0.10),
                0 8px 12px rgba(0, 0, 0, 0.95);
            ;
        }

        /* IoT: azul eléctrico con glow controlado */
        .text-3d-blue {
            color: #2e92f0;
            text-shadow:
                0 1px 0 #0cdb85,
                0 2px 0 #00fff2,
                0 3px 0 #00c3ff,
                0 0 10px rgba(3, 47, 53, 0.5),
                0 4px 6px rgba(0, 0, 0, 0.10),
                0 8px 12px rgba(0, 0, 0, 0.99);
        }

        /* Animación sutil */
        .text-3d-animated {
            animation: glow3d 4s ease-in-out infinite alternate;
        }

        @keyframes glow3d {
            from {
                text-shadow:
                    0 1px 0 #1d4ed8,
                    0 0 5px #3b82f6;
            }

            to {
                text-shadow:
                    0 1px 0 #1d4ed8,
                    0 0 10px #2563eb,
                    0 4px 6px rgba(0, 0, 0, 0.4);
            }
        }

        /* Responsividad */
        @media (max-width: 768px) {
            .logo-text {
                font-size: 1.5rem !important;
            }
        }

        @media (max-width: 480px) {
            .logo-text {
                font-size: 1.25rem !important;
            }
        }

        /* ----------------- */

        /* Efecto subrayado para enlaces */
        .nav-link {
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: white;
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s ease;
        }

        .nav-link:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        /* Hero section */
        .hero-section {
            position: relative;
            height: 70vh;
            overflow: hidden;
        }

        .hero-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
            background-blend-mode: overlay;
            background-color: rgba(55, 65, 81, 0.7);
        }

        .hero-image.active {
            opacity: 1;
        }

        /* ***************POR VERSE ************************ */
        /* Efecto hover para tarjetas */
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* Footer moderno */
        .footer-link {
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .footer-link:hover {
            color: #a7f3a0;
            transform: translateX(5px);
        }

        .social-icon {
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .social-icon:hover {
            transform: translateY(-3px);
            color: #a7f3a0;
        }

        /* Ajustes específicos para el footer */
        .footer-grid {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }

        @media (max-width: 640px) {
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .footer-logo {
                text-align: center;
                justify-content: center;
            }

            .footer-social {
                justify-content: center;
            }

            .footer-column {
                text-align: center;
            }

            .footer-contact li {
                justify-content: center;
            }
        }

        /* ***************POR VERSE ************************ */
        .card {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.3s ease;
        }

        .card .image-container {
            width: 100%;
            max-width: 200px;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fff;
            border: 2px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            margin: auto;
        }

        .card img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .card h5 {
            text-align: center;
        }

        .btn-ver-detalles {
            align-self: center;
            width: 100%;
        }

        /* Ajustes para vista vertical */
        .vertical .card {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        /* Asegurar alturas iguales para las tarjetas en modo vertical */
        .card-item {
            display: flex;
            flex-direction: column;
        }
    </style>
    @stack('styles')
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
</head>

<body class="bg-gray-50 font-sans">

    <!-- *****************                   HEADER                    ********************* -->
    <header id="mainHeader" class="bg-gradient-to-r from-primary to-green-800 text-white fixed w-full z-50">
        <div class="container mx-auto px-4">
            <nav class="flex justify-between items-center py-3 h-20">
                <a href="https://compos.alwaysdata.net" class="flex items-center space-x-2 group">
                    <div class="w-14 h-14 rounded-full overflow-hidden flex items-center justify-center bg-white">
                        <img src="{{ asset('logo/compostLogo.png') }}" alt="Logo"
                            class="w-full h-full object-cover">
                    </div>
                    <span class="text-3xl font-bold logo-text text-3d-green transition-all duration-300">
                        Compostaje <span class="text-3d-blue text-3d-animated">IoT</span>
                    </span>
                </a>

                <!-- Menú Desktop -->
                <ul class="hidden md:flex items-center space-x-1">
                    <li><a href="{{ route('index') }}"
                            class="nav-link px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-all duration-300 flex items-center group">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-1 group-hover:rotate-12 transition-transform" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Inicio
                        </a></li>
                    <li><a href="{{ route('proyecto') }}"
                            class="nav-link px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-all duration-300">Proyecto</a>
                    </li>
                    <li><a href="{{ route('nosotros') }}"
                            class="nav-link px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-all duration-300">Nosotros</a>
                    </li>
                    <li><a href="{{ route('contactanos') }}"
                            class="nav-link px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-all duration-300">Contactanos</a>
                    </li>
                    @if (auth()->check() && auth()->user()->role === 'admin')
                        <li>
                            <a href="{{ route('dashboardAdmin') }}"
                                class="nav-link px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-all duration-300 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Panel de control
                            </a>
                        </li>
                    @endif


                    @if (auth()->check() && auth()->user()->role === 'user')
                        <li>
                            <a href="{{ route('gUs') }}"
                                class="nav-link px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-all duration-300 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Panel de control
                            </a>
                        </li>
                    @endif

                    @if (auth()->check() && auth()->user()->role === 'client')
                        <li>
                            <a href="{{ route('shop') }}"
                                class="nav-link px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-all duration-300 flex items-center">
                                Historial de compras
                            </a>
                        </li>
                    @endif

                    @guest
                        <!-- Botones para invitados -->
                        <li>
                            <a href="{{ route('login') }}"
                                class="nav-link px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-all duration-300 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Iniciar Sesión
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('registro') }}"
                                class="ml-2 px-4 py-2 rounded-lg bg-secondary hover:bg-green-700 transition-all duration-300 shadow-md hover:shadow-lg flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                Registrarse
                            </a>
                        </li>
                    @else
                        @if (auth()->check())
                            <!-- Cliente autenticado -->
                            <li>
                                <button onclick="document.getElementById('editModal').showModal()"
                                    class="flex items-center space-x-2 px-3 py-2 bg-green-750 hover:bg-green-700 rounded text-white font-semibold focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    <!-- Ícono -->
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 9A3.75 3.75 0 1112 5.25 3.75 3.75 0 0115.75 9zM4.5 20.25a7.5 7.5 0 0115 0" />
                                    </svg>

                                    <span class="text-sm font-semibold">{{ auth()->user()->name }}</span>
                                </button>
                            </li>

                            <!-- Botón de logout -->
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-semibold shadow transition duration-300">
                                        Cerrar sesión
                                    </button>
                                </form>
                            </li>
                        @endif

                    @endguest

                </ul>

                <!-- Botón Hamburguesa -->
                <button id="menuBtn"
                    class="md:hidden p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 transition-all">
                    <svg id="menuIcon" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </nav>

            <!-- Menú Móvil -->
            <div id="mobileMenu"
                class="md:hidden hidden bg-white bg-opacity-10 backdrop-blur-lg rounded-lg overflow-hidden transition-all duration-300 transform origin-top">
                <ul class="space-y-1 py-2">
                    <li><a href="{{ route('index') }}"
                            class="block px-4 py-3 hover:bg-white hover:bg-opacity-20 transition-all duration-300 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Inicio
                        </a></li>
                    <li><a href="{{ route('proyecto') }}"
                            class="block px-4 py-3 hover:bg-white hover:bg-opacity-20 transition-all duration-300">Proyecto</a>
                    </li>
                    <li><a href="{{ route('nosotros') }}"
                            class="block px-4 py-3 hover:bg-white hover:bg-opacity-20 transition-all duration-300">Nosotros</a>
                    </li>
                    <li><a href="{{ route('contactanos') }}"
                            class="block px-4 py-3 hover:bg-white hover:bg-opacity-20 transition-all duration-300">Contactanos</a>
                    </li>

                    @if (auth()->check() && auth()->user()->role === 'admin')
                        <li>
                            <a href="{{ route('dashboardAdmin') }}"
                                class="nav-link px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-all duration-300 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Panel de control
                            </a>
                        </li>
                    @endif


                    @if (auth()->check() && auth()->user()->role === 'user')
                        <li>
                            <a href="{{ route('gUs') }}"
                                class="nav-link px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-all duration-300 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Panel de control
                            </a>
                        </li>
                    @endif

                    @if (auth()->check() && auth()->user()->role === 'client')
                        <li>
                            <a href="{{ route('shop') }}"
                                class="nav-link px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-all duration-300 flex items-center">
                                Historial de compras
                            </a>
                        </li>
                    @endif

                    @guest
                        <!-- Botones para invitados -->
                        <li><a href="{{ route('login') }}"
                                class="block px-4 py-3 hover:bg-white hover:bg-opacity-20 transition-all duration-300 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Iniciar Sesión
                            </a></li>
                        <li><a href="{{ route('registro') }}"
                                class="block mx-4 my-2 px-4 py-3 bg-secondary hover:bg-green-700 transition-all duration-300 rounded-lg text-center font-medium shadow hover:shadow-md">
                                Registrarse
                            </a></li>
                    @else
                        @if (auth()->check())
                            <!-- Cliente autenticado -->
                            <li>
                                <button onclick="document.getElementById('editModal').showModal()"
                                    class="flex items-center space-x-2 px-3 py-2 bg-green-750 hover:bg-green-700 rounded text-white font-semibold focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    <!-- Ícono -->
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 9A3.75 3.75 0 1112 5.25 3.75 3.75 0 0115.75 9zM4.5 20.25a7.5 7.5 0 0115 0" />
                                    </svg>

                                    <span class="text-sm font-semibold">{{ auth()->user()->name }}</span>
                                </button>
                            </li>

                            <!-- Botón de logout -->
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-semibold shadow transition duration-300">
                                        Cerrar sesión
                                    </button>
                                </form>
                            </li>
                        @endif

                    @endguest
                </ul>
            </div>

            <!-- Modal: Editar Perfil -->
            <dialog id="editModal" class="rounded-3xl p-0 max-w-xl w-full shadow-2xl backdrop:bg-black/40 z-50">
                <form method="POST" action="{{ route('profile.update') }}"
                    class="bg-white p-6 rounded-3xl space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Mensajes de éxito o error -->
                    @if (session('success'))
                        <div class="bg-green-100 text-green-800 p-3 rounded-lg border border-green-300">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 text-red-800 p-3 rounded-lg border border-red-300">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 text-red-800 p-3 rounded-lg border border-red-300">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Título -->
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3 mb-6">
                        <i class="fas fa-user-edit text-green-600"></i> Editar Perfil
                    </h2>

                    <!-- Campos: grid responsive 2 columnas -->
                    @php
                        $fields = [
                            ['name' => 'name', 'label' => 'Nombre', 'icon' => 'fa-user', 'required' => true],
                            [
                                'name' => 'firstLastName',
                                'label' => 'Primer Apellido',
                                'icon' => 'fa-user',
                                'required' => true,
                            ],
                            [
                                'name' => 'secondLastName',
                                'label' => 'Segundo Apellido',
                                'icon' => 'fa-user',
                                'required' => false,
                            ],
                            [
                                'name' => 'username',
                                'label' => 'Usuario',
                                'icon' => 'fa-user-circle',
                                'required' => true,
                            ],
                            [
                                'name' => 'email',
                                'label' => 'Correo Electrónico',
                                'icon' => 'fa-envelope',
                                'required' => true,
                            ],
                        ];
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @foreach ($fields as $field)
                            <div class="relative group">
                                <i
                                    class="fas {{ $field['icon'] }} absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-green-600 transition-colors duration-300"></i>
                                <input type="{{ $field['name'] == 'email' ? 'email' : 'text' }}"
                                    name="{{ $field['name'] }}"
                                    value="{{ old($field['name'], optional(auth()->user())->{$field['name']}) }}"
                                    {{ $field['required'] ? 'required' : '' }} placeholder=" "
                                    class="peer w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-500 transition text-gray-800" />
                                <label
                                    class="absolute left-10 -top-2.5 text-gray-500 text-sm peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base transition-all duration-300">
                                    {{ $field['label'] }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <!-- Campos para cambiar contraseña -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">
                        <div class="relative group">
                            <i
                                class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-green-600 transition-colors duration-300"></i>
                            <input type="password" name="current_password" placeholder=" "
                                class="peer w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-500 transition text-gray-800" />
                            <label
                                class="absolute left-10 -top-2.5 text-gray-500 text-sm peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base transition-all duration-300">
                                Contraseña Actual
                            </label>
                        </div>

                        <div class="relative group">
                            <i
                                class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-green-600 transition-colors duration-300"></i>
                            <input type="password" name="new_password" placeholder=" "
                                class="peer w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-500 transition text-gray-800" />
                            <label
                                class="absolute left-10 -top-2.5 text-gray-500 text-sm peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base transition-all duration-300">
                                Nueva Contraseña
                            </label>
                        </div>

                        <div class="relative group md:col-span-2">
                            <i
                                class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-green-600 transition-colors duration-300"></i>
                            <input type="password" name="new_password_confirmation" placeholder=" "
                                class="peer w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-500 transition text-gray-800" />
                            <label
                                class="absolute left-10 -top-2.5 text-gray-500 text-sm peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base transition-all duration-300">
                                Confirmar Nueva Contraseña
                            </label>
                        </div>
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



        </div>
    </header>
    <!-- *****************                  FIN HEADER                    ********************* -->



    <!-- *****************                   MAIN                     ********************* -->
    <main>
        @yield('content')
    </main>
    <!-- *****************                  FIN MAIN                     ********************* -->


    <!-- *****************                   FOOTER                     ********************* -->
    <footer style="background-color: #381c00ff; color: white; padding-top: 4rem; padding-bottom: 2rem;">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Columna 1: Logo y descripción -->
                <div class="footer-logo flex flex-col items-center md:items-start">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-14 h-14 rounded-full overflow-hidden flex items-center justify-center bg-white">
                            <img src="{{ asset('logo/compostLogo.png') }}" alt="Logo"
                                class="w-full h-full object-cover">
                        </div>
                        <span class="text-3xl font-bold logo-text text-3d-green transition-all duration-300">
                            Compostaje <span class="text-3d-blue text-3d-animated">IoT</span>
                        </span>
                    </div>
                    <p class="text-white mb-4 text-center md:text-left">Sistema inteligente para el monitoreo de
                        compostaje doméstico mediante tecnología IoT.</p>

                    <!-- Redes Sociales -->
                    <div class="footer-social flex justify-center flex-wrap gap-4 mt-6">
                        <a href="https://www.facebook.com/"
                            class="social-icon text-white hover:text-tertiary text-xl">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://instagram.com/" class="social-icon text-white hover:text-tertiary text-xl">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://wa.me/59163906225"
                            class="social-icon text-white hover:text-tertiary text-xl">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="mailto:compostajeiot@gmail.com"
                            class="social-icon text-white hover:text-tertiary text-xl">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </div>

                </div>

                <!-- Columna 2: Enlaces rápidos -->
                <div class="footer-column">
                    <h3 class="text-xl font-semibold mb-4 text-white">Enlaces Rápidos</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('index') }}" class="footer-link text-white">Inicio</a></li>
                        <li><a href="{{ route('proyecto') }}" class="footer-link text-white">Proyecto</a></li>
                        <li><a href="{{ route('nosotros') }}" class="footer-link text-white">Nosotros</a></li>
                        <li><a href="{{ route('contactanos') }}" class="footer-link text-white">Contactanos</a></li>
                        <li><a href="{{ route('login') }}" class="footer-link text-white">Iniciar Sesión</a></li>
                        <li><a href="{{ route('registro') }}" class="footer-link text-white">Registro de Usuario</a>
                        </li>
                    </ul>
                </div>

                <!-- Columna 3: Contacto -->
                <div class="footer-column">
                    <h3 class="text-xl font-semibold mb-4 text-white">Contacto</h3>
                    <ul class="space-y-3 footer-contact">
                        <li class="flex items-start space-x-3">
                            <i class="fas fa-map-marker-alt text-secondary mt-1"></i>
                            <span class="text-white">Av. Villa Israel #123, Cochabamba, Bolivia</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-phone-alt text-secondary"></i>
                            <span class="text-white">+591 63906225</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-secondary"></i>
                            <span class="text-white">compostajeiot@gmail.com</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-clock text-secondary"></i>
                            <span class="text-white">Lun-Vie: 8:00 - 18:00</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Derechos de autor -->
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-white">
                <p>&copy; 2025 Sistema de Compostaje IoT - Villa Israel. Todos los derechos reservados.</p>
                <div class="flex flex-wrap justify-center space-x-6 mt-4">
                    <a href="#" class="text-white hover:text-tertiary">Términos de Servicio</a>
                    <a href="#" class="text-white hover:text-tertiary">Política de Privacidad</a>
                    <a href="#" class="text-white hover:text-tertiary">Cookies</a>
                </div>
            </div>
        </div>
    </footer>
    <!-- *****************                  FIN FOOTER                     ********************* -->

    <!-- **********MENU HEDER ITERACTIVO CON CARRUSER DE PORTADA*********** -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Control del menú móvil
            const menuBtn = document.getElementById('menuBtn');
            const mobileMenu = document.getElementById('mobileMenu');
            const menuIcon = document.getElementById('menuIcon');

            menuBtn.addEventListener('click', function() {
                const isOpen = mobileMenu.classList.toggle('hidden');
                menuBtn.setAttribute('aria-expanded', !isOpen);

                if (!isOpen) {
                    menuIcon.innerHTML =
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
                } else {
                    menuIcon.innerHTML =
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />';
                }
            });

            // Cerrar menú al hacer clic en enlace
            document.querySelectorAll('#mobileMenu a').forEach(item => {
                item.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                    menuBtn.setAttribute('aria-expanded', 'false');
                    menuIcon.innerHTML =
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />';
                });
            });

            // Control del header al hacer scroll
            const header = document.getElementById('mainHeader');
            let lastScroll = 0;
            const scrollThreshold = 100;

            window.addEventListener('scroll', function() {
                const currentScroll = window.pageYOffset;

                if (currentScroll <= 10) {
                    // Parte superior - resetear
                    header.classList.remove('hide', 'scrolled');
                } else if (currentScroll > scrollThreshold) {
                    // Cambiar estilo
                    header.classList.add('scrolled');

                    // Scroll hacia abajo
                    if (currentScroll > lastScroll) {
                        header.classList.add('hide');
                    }
                    // Scroll hacia arriba
                    else {
                        header.classList.remove('hide');
                    }
                }

                lastScroll = currentScroll;
            });

            // Carrusel de imágenes de portada
            const images = document.querySelectorAll('.hero-image');
            let currentIndex = 0;

            function changeImage() {
                if (images.length === 0) return;

                images.forEach(img => img.classList.remove('active'));
                currentIndex = (currentIndex + 1) % images.length;
                images[currentIndex].classList.add('active');
            }

            if (images.length > 0) {
                setInterval(changeImage, 5000);
            }

        });
    </script>





    {{-- ************ PAGINACION DE PRODUCTOS  ************** --}}
    <script>
        // Puedes definir estos valores dinámicamente
        const urlParams = new URLSearchParams(window.location.search);
        const currentPage = parseInt(urlParams.get('page')) || 1;
        const totalPages = 10; // Cambia esto al total real de páginas

        const pagination = document.getElementById('pagination');

        function createPageItem(page, label, isActive = false, isDisabled = false) {
            const li = document.createElement('li');
            li.className = 'page-item' + (isActive ? ' active' : '') + (isDisabled ? ' disabled' : '');

            const a = document.createElement('a');
            a.className = 'page-link border-0 rounded-circle';
            a.setAttribute('aria-label', label);

            if (isDisabled) {
                a.classList.add('bg-light', 'text-muted');
                a.href = '#';
            } else {
                a.classList.add(isActive ? 'bg-primary' : 'bg-light');
                a.classList.add(isActive ? 'text-white' : 'text-dark');
                a.href = '?page=' + page;
            }

            if (label === 'Anterior') {
                a.innerHTML = '<i class="fas fa-chevron-left"></i>';
            } else if (label === 'Siguiente') {
                a.innerHTML = '<i class="fas fa-chevron-right"></i>';
            } else {
                a.textContent = page;
            }

            li.appendChild(a);
            return li;
        }

        // Botón Anterior
        pagination.appendChild(createPageItem(currentPage - 1, 'Anterior', false, currentPage === 1));

        // Números de página
        for (let i = 1; i <= totalPages; i++) {
            pagination.appendChild(createPageItem(i, i.toString(), i === currentPage));
        }

        // Botón Siguiente
        pagination.appendChild(createPageItem(currentPage + 1, 'Siguiente', false, currentPage === totalPages));
    </script>
    {{-- **********CARDS O TARJETAS DE HORIZONTAL A VERTICAL***************** --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cambiar disposición de las tarjetas
            const btnHorizontal = document.getElementById('btn-horizontal');
            const btnVertical = document.getElementById('btn-vertical');
            const cardsContainer = document.getElementById('cards-container');

            if (btnHorizontal && btnVertical && cardsContainer) {
                // Cambiar a disposición horizontal
                btnHorizontal.addEventListener('click', () => {
                    cardsContainer.classList.remove('grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-3');
                    cardsContainer.classList.add('grid-cols-1');

                    // Cambiar clases de cada card-item
                    document.querySelectorAll('.card-item').forEach(card => {
                        card.classList.remove('col-span-1');
                        card.classList.add('w-full');
                    });
                });

                // Cambiar a disposición vertical
                btnVertical.addEventListener('click', () => {
                    cardsContainer.classList.remove('grid-cols-1');
                    cardsContainer.classList.add('grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-3');

                    // Cambiar clases de cada card-item
                    document.querySelectorAll('.card-item').forEach(card => {
                        card.classList.remove('w-full');
                        card.classList.add('col-span-1');
                    });
                });
            }
        });
    </script>
    <!-- FILTRO Y UBICACION -->

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Restablecer filtros
            document.getElementById('restablecerBtn').addEventListener('click', function() {
                document.getElementById('titulo').value = '';
                document.getElementById('tipo').value = '';
                document.getElementById('precio_max').value = '';
                document.getElementById('busqueda-form').submit();
                localStorage.setItem('scrollToMap', 'true');
            });

            // Validación del formulario
            document.getElementById('busqueda-form').addEventListener('submit', function(event) {
                const tituloInput = document.getElementById('titulo').value.trim();
                const tipoInput = document.getElementById('tipo').value.trim();
                const precioInput = document.getElementById('precio_max').value.trim();

                if (!tituloInput && !tipoInput && !precioInput) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'info',
                        title: 'Filtros vacíos',
                        text: 'Por favor, ingrese al menos un criterio de búsqueda',
                        confirmButtonColor: '#2e7d32'
                    });
                    return;
                }

                if (precioInput && (isNaN(parseFloat(precioInput)) || parseFloat(precioInput) < 0)) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Precio inválido',
                        text: 'Por favor, ingrese un valor numérico válido para el precio',
                        confirmButtonColor: '#2e7d32'
                    });
                    return;
                }

                localStorage.setItem('scrollToMap', 'true');
            });

            // Scroll automático al mapa si viene de una búsqueda
            if (localStorage.getItem('scrollToMap') === 'true') {
                const mapa = document.getElementById('contenedor-del-mapa');
                if (mapa) {
                    setTimeout(() => {
                        mapa.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }, 300);
                }
                localStorage.removeItem('scrollToMap');
            }

            // Cambiar disposición de tarjetas
            const btnHorizontal = document.getElementById('btn-horizontal');
            const btnVertical = document.getElementById('btn-vertical');
            const cardsContainer = document.getElementById('cards-container');

            btnHorizontal.addEventListener('click', () => {
                cardsContainer.classList.remove('grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-3');
                cardsContainer.classList.add('grid-cols-1');
            });

            btnVertical.addEventListener('click', () => {
                cardsContainer.classList.remove('grid-cols-1');
                cardsContainer.classList.add('grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-3');
            });
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.min.js"></script>
    @yield('scripts')
</body>

</html>
