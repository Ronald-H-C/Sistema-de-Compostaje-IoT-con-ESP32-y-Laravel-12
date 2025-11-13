@extends('layouts.app')

@section('title', 'Contáctanos - Compostero IoT')

@section('content')



    <div class="bg-gradient-to-b from-green-50 to-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Encabezado mejorado -->
            <div class="text-center mb-16 animate-fade-in">
                <div class="inline-block bg-green-100 rounded-full p-3 mb-6">
                    <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Contáctanos</h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    ¿Tienes preguntas sobre nuestro proyecto de compostaje inteligente? Estamos aquí para ayudarte.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Formulario de contacto mejorado -->
                <div class="bg-white rounded-2xl shadow-xl p-8 transition-all hover:shadow-2xl">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="h-6 w-6 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        Envíanos un mensaje
                    </h2>

                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                        @csrf


                        <div class="space-y-1">
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre completo</label>
                            <div class="relative">
                                <input type="text" id="nombre" name="nombre" required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 pl-10"
                                    placeholder="Tu nombre">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                            <div class="relative">
                                <input type="email" id="email" name="email" required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 pl-10"
                                    placeholder="tucorreo@ejemplo.com">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label for="asunto" class="block text-sm font-medium text-gray-700">Asunto</label>
                            <div class="relative">
                                <select id="asunto" name="asunto"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 pl-10 appearance-none">
                                    <option value="" disabled selected>Selecciona un tema</option>
                                    <option value="informacion">Información sobre el proyecto</option>
                                    <option value="tecnico">Soporte técnico</option>
                                    <option value="colaboracion">Posibilidad de colaboración</option>
                                    <option value="otro">Otro</option>
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 20 20"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7l3-3 3 3m0 6l-3 3-3-3" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label for="mensaje" class="block text-sm font-medium text-gray-700">Mensaje</label>
                            <textarea id="mensaje" name="mensaje" rows="5" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Escribe tu mensaje aquí..."></textarea>
                        </div>

                        <div>
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold py-3 px-4 rounded-lg transition-all duration-300 transform hover:scale-[1.01] shadow-md hover:shadow-lg flex items-center justify-center">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Enviar mensaje
                            </button>
                        </div>
                    </form>
                </div>


                <!-- Información de contacto mejorada -->
                <div class="space-y-8">
                    <div class="bg-white rounded-2xl shadow-xl p-8 transition-all hover:shadow-2xl">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="h-6 w-6 text-green-600 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Información de contacto
                        </h2>

                        <div class="space-y-6">
                            <div class="flex items-start group">
                                <div
                                    class="flex-shrink-0 bg-green-100 rounded-lg p-3 group-hover:bg-green-600 group-hover:text-white transition-all duration-300">
                                    <svg class="h-6 w-6 text-green-600 group-hover:text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3
                                        class="text-lg font-medium text-gray-900 group-hover:text-green-600 transition-all duration-300">
                                        Teléfono</h3>
                                    <p class="text-gray-600">+591 12345678</p>
                                    <p class="text-gray-500 text-sm">Lunes a Viernes, 9:00 - 18:00</p>
                                    <a href="tel:+59112345678"
                                        class="mt-2 inline-flex items-center text-sm font-medium text-green-600 hover:text-green-800 transition-colors duration-200">
                                        Llamar ahora
                                        <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <div class="flex items-start group">
                                <div
                                    class="flex-shrink-0 bg-green-100 rounded-lg p-3 group-hover:bg-green-600 group-hover:text-white transition-all duration-300">
                                    <svg class="h-6 w-6 text-green-600 group-hover:text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3
                                        class="text-lg font-medium text-gray-900 group-hover:text-green-600 transition-all duration-300">
                                        Correo electrónico</h3>
                                    <p class="text-gray-600">info@composteroiot.com</p>
                                    <p class="text-gray-500 text-sm">Respondemos en menos de 24 horas</p>
                                    <a href="mailto:info@composteroiot.com"
                                        class="mt-2 inline-flex items-center text-sm font-medium text-green-600 hover:text-green-800 transition-colors duration-200">
                                        Enviar correo
                                        <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <div class="flex items-start group">
                                <div
                                    class="flex-shrink-0 bg-green-100 rounded-lg p-3 group-hover:bg-green-600 group-hover:text-white transition-all duration-300">
                                    <svg class="h-6 w-6 text-green-600 group-hover:text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3
                                        class="text-lg font-medium text-gray-900 group-hover:text-green-600 transition-all duration-300">
                                        Dirección</h3>
                                    <p class="text-gray-600">Av. Villa Israel #123</p>
                                    <p class="text-gray-600">La Paz, Bolivia</p>
                                    <a href="https://maps.google.com?q=Av.+Villa+Israel+%23123,+La+Paz,+Bolivia"
                                        target="_blank"
                                        class="mt-2 inline-flex items-center text-sm font-medium text-green-600 hover:text-green-800 transition-colors duration-200">
                                        Ver en mapa
                                        <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mapa de ubicación mejorado -->
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden transition-all hover:shadow-2xl">
                        <div class="h-64 w-full">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15287.123456789012!2d-66.1785108!3d-17.4872352!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTfCsDI5JzE0LjUiUyA2NsKwMTAnNDIuNiJX!5e0!3m2!1ses!2sbo!4v1700000000000"
                                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                        <div class="p-4 text-center bg-gray-50 border-t border-gray-200">
                            <!-- ENLACE CORREGIDO: usa la URL normal de Google Maps -->
                            <a href="https://www.google.com/maps?q=-17.4872352,-66.1785108&z=17" target="_blank"
                                class="text-green-600 hover:text-green-800 font-medium transition-colors duration-200">
                                Ver ubicación en Google Maps
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif
            <!-- Redes sociales mejoradas -->
            <div class="mt-20 text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Conéctate con nosotros</h2>
                <p class="text-gray-600 max-w-2xl mx-auto mb-8">
                    Síguenos en nuestras redes sociales para mantenerte actualizado sobre nuestro proyecto de compostaje
                    inteligente.
                </p>
                <div class="flex justify-center space-x-6">
                    <a href="#"
                        class="h-12 w-12 rounded-full bg-gray-100 hover:bg-blue-600 text-gray-700 hover:text-white flex items-center justify-center transition-all duration-300 transform hover:-translate-y-1 shadow hover:shadow-lg">
                        <span class="sr-only">Facebook</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#"
                        class="h-12 w-12 rounded-full bg-gray-100 hover:bg-blue-400 text-gray-700 hover:text-white flex items-center justify-center transition-all duration-300 transform hover:-translate-y-1 shadow hover:shadow-lg">
                        <span class="sr-only">Twitter</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                        </svg>
                    </a>
                    <a href="#"
                        class="h-12 w-12 rounded-full bg-gray-100 hover:bg-pink-600 text-gray-700 hover:text-white flex items-center justify-center transition-all duration-300 transform hover:-translate-y-1 shadow hover:shadow-lg">
                        <span class="sr-only">Instagram</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#"
                        class="h-12 w-12 rounded-full bg-gray-100 hover:bg-blue-700 text-gray-700 hover:text-white flex items-center justify-center transition-all duration-300 transform hover:-translate-y-1 shadow hover:shadow-lg">
                        <span class="sr-only">LinkedIn</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                        </svg>
                    </a>
                    <a href="#"
                        class="h-12 w-12 rounded-full bg-gray-100 hover:bg-red-600 text-gray-700 hover:text-white flex items-center justify-center transition-all duration-300 transform hover:-translate-y-1 shadow hover:shadow-lg">
                        <span class="sr-only">YouTube</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
