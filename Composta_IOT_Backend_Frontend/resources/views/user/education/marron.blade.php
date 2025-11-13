@extends('user.dashboard')

@section('title', 'Materiales Marrones en el Compostaje')

@section('content')
    <!-- Flecha para volver atrás -->


    <div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen">
        <div class="mb-4">
            <a href="{{ route('materiales.index') }}"
                class="inline-flex items-center text-green-700 hover:text-green-900 font-semibold transition duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
                Volver
            </a>
        </div>
        <h1 class="text-4xl font-bold text-yellow-700 mb-8 text-center">🟤 Materiales Marrones</h1>

        <div class="mb-8">
            <img src="{{ asset('img/materiales_marrones.png') }}" alt="Materiales marrones para compostaje"
                class="mx-auto rounded-lg shadow-md"
                style="width: 80%; max-width: 1200px; height: auto; aspect-ratio: 16/6;">
        </div>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">📋 ¿Qué son los Materiales Marrones?</h2>
            <p class="text-gray-700 leading-relaxed">
                Los materiales marrones son aquellos ricos en carbono, esenciales para proporcionar energía a los
                microorganismos que descomponen la materia orgánica. Son generalmente secos, duros y de color marrón.
            </p>
            <div class="border-t border-gray-300 my-6"></div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">🟤 Beneficios Clave</h2>
            <ul class="list-disc list-inside text-gray-700 leading-loose px-6 md:px-24">
                <li>Proporcionan carbono necesario para la energía de los microorganismos</li>
                <li>Mantienen la estructura del compost permitiendo una buena aireación</li>
                <li>Controlan la humedad absorbiendo el exceso de líquidos</li>
                <li>Previenen malos olores y compactación</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">📊 Proporción Recomendada</h2>
            <p
                class="text-gray-700 leading-relaxed bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-500 text-center text-xl font-bold">
                2 partes de materiales marrones : 1 parte de materiales verdes
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">🍂 Lista de Materiales Marrones Aceptados</h2>

            <div class="mb-6">
                <h3 class="text-xl font-medium text-yellow-700 mb-2">✅ Alta Disponibilidad de Carbono</h3>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>Hojas secas (roble, arce, etc.)</li>
                    <li>Paja y heno seco</li>
                    <li>Ramitas y pequeñas ramas trituradas</li>
                    <li>Serrín y virutas de madera natural (no tratada)</li>
                    <li>Cartón sin tintas tóxicas (troceado)</li>
                    <li>Periódico triturado (solo papel sin colorantes)</li>
                </ul>
            </div>

            <div class="mb-6">
                <h3 class="text-xl font-medium text-yellow-600 mb-2">✅ Moderada Disponibilidad de Carbono</h3>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>Cáscaras de huevo trituradas</li>
                    <li>Bolsa de papel marrón triturado</li>
                    <li>Tallos secos de plantas</li>
                    <li>Aserrín de carpintería (madera no tratada)</li>
                </ul>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">⚠️ Precauciones Importantes</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                    <thead class="bg-yellow-100">
                        <tr>
                            <th class="py-3 px-4 border-b border-gray-300 text-left font-semibold">Riesgo</th>
                            <th class="py-3 px-4 border-b border-gray-300 text-left font-semibold">Solución</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-300">Falta de humedad</td>
                            <td class="py-2 px-4 border-b border-gray-300">Añadir agua o materiales verdes</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-300">Proceso de compostaje lento</td>
                            <td class="py-2 px-4 border-b border-gray-300">Aumentar proporción de materiales verdes</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-300">Materiales demasiado grandes</td>
                            <td class="py-2 px-4 border-b border-gray-300">Triturar o cortar en trozos pequeños</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">🚫 Materiales Marrones a Evitar</h2>
            <ul class="list-disc list-inside text-gray-700 leading-loose px-6 md:px-24">
                <li>Madera tratada con productos químicos</li>
                <li>Papel brillante o con tintas de colores</li>
                <li>Cartón plastificado o con recubrimientos</li>
                <li>Cenizas de carbón o de madera tratada</li>
                <li>Restos de plantas enfermas o con pesticidas</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">✂️ Técnicas de Preparación</h2>

            <div class="mb-4">
                <h3 class="text-xl font-medium text-gray-700 mb-2">Trituración y Corte</h3>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>Triturar hojas secas y ramas para acelerar descomposición</li>
                    <li>Cortar cartón y papel en tiras de 2-5 cm</li>
                    <li>Usar trituradora manual o mecánica para materiales duros</li>
                </ul>
            </div>

            <div class="mb-4">
                <h3 class="text-xl font-medium text-gray-700 mb-2">Mezcla Ideal</h3>
                <div class="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-500">
                    <p class="text-gray-700"><strong>material_marrón</strong> = 60-70% del total</p>
                    <p class="text-gray-700"><strong>material_verde</strong> = 25-30% del total</p>
                    <p class="text-gray-700"><strong>tierra</strong> = 10% del total</p>
                </div>
            </div>
        </section>

        <div class="border-t border-gray-300 my-6"></div>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">🔍 Señales de Problemas y Soluciones</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                    <thead class="bg-yellow-100">
                        <tr>
                            <th class="py-3 px-4 border-b border-gray-300 text-left font-semibold">Problema</th>
                            <th class="py-3 px-4 border-b border-gray-300 text-left font-semibold">Causa</th>
                            <th class="py-3 px-4 border-b border-gray-300 text-left font-semibold">Solución</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-300">Compost muy seco</td>
                            <td class="py-2 px-4 border-b border-gray-300">Exceso de materiales marrones</td>
                            <td class="py-2 px-4 border-b border-gray-300">Añadir agua o materiales verdes</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-300">Proceso muy lento</td>
                            <td class="py-2 px-4 border-b border-gray-300">Falta de nitrógeno</td>
                            <td class="py-2 px-4 border-b border-gray-300">Aumentar materiales verdes</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-300">Material sin descomponer</td>
                            <td class="py-2 px-4 border-b border-gray-300">Trozos demasiado grandes</td>
                            <td class="py-2 px-4 border-b border-gray-300">Triturar más finamente</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">📈 Monitoreo con Nuestro Sistema IoT</h2>

            <div class="mb-4">
                <h3 class="text-xl font-medium text-gray-700 mb-2">Parámetros Ideales con Materiales Marrones</h3>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>Temperatura: 45-60°C</li>
                    <li>Humedad: 40-50% (ligeramente menor que con verdes)</li>
                    <li>Relación C/N: 25:1 a 30:1</li>
                    <li>Textura: esponjosa y bien aireada</li>
                </ul>
            </div>

            <div class="mb-4">
                <h3 class="text-xl font-medium text-gray-700 mb-2">Alertas Automáticas</h3>
                <p class="text-gray-700 mb-2">Nuestro sistema te notificará cuando:</p>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>📱 La humedad baje del 30% (necesita riego urgente)</li>
                    <li>📱 La temperatura sea inferior a 40°C (proceso lento)</li>
                    <li>📱 Se detecte compactación excesiva</li>
                </ul>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">🌱 Ejemplos Prácticos</h2>

            <div class="mb-6 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                <h3 class="text-xl font-medium text-blue-700 mb-2">Compostaje Equilibrado</h3>
                <ul class="list-none text-gray-700 space-y-1 px-6 md:px-24">
                    <li>● Materiales marrones (hojas secas, cartón): 4.0 kg</li>
                    <li>● Materiales verdes (restos de cocina): 2.0 kg</li>
                    <li>● Tierra: 0.5 kg</li>
                    <li>● Agua: 0.1 L (aplicada con sistema automático)</li>
                    <li>● 🔢 Masa total inicial: 4.0+2.0+0.5+0.1=6.6 kg</li>
                    <li>● ⏳ Tiempo estimado de proceso: 40–50 días</li>
                    <li>● ✅ Rendimiento observado: 95 % de la masa inicial</li>
                    <li>● ⚖️ Peso final obtenido: 6.3 kg de compost maduro</li>
                </ul>
            </div>

            <div class="p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                <h3 class="text-xl font-medium text-yellow-700 mb-2">Compostaje con Exceso de Marrones</h3>
                <ul class="list-none text-gray-700 px-6 md:px-24">
                    <li>• Materiales marrones: 8 kg</li>
                    <li>• Materiales verdes: 1 kg</li>
                    <li>• ⏳ Tiempo estimado de proceso: 90–120 días</li>
                    <li>• ⚠️ Resultado: Proceso muy lento, compost seco</li>
                </ul>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">💡 Consejos Expertos</h2>

            <div class="mb-4">
                <h3 class="text-xl font-medium text-yellow-600 mb-2">Para Principiantes</h3>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>Empieza con hojas secas y cartón marrón triturado</li>
                    <li>Mantén una reserva de materiales marrones para ajustar humedad</li>
                    <li>Observa la textura del compost semanalmente</li>
                </ul>
            </div>

            <div class="mb-4">
                <h3 class="text-xl font-medium text-yellow-700 mb-2">Para Avanzados</h3>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>Experimenta con diferentes tipos de materiales marrones</li>
                    <li>Combina diferentes texturas para mejor aireación</li>
                    <li>Pre-composta materiales muy leñosos antes de añadirlos</li>
                </ul>
            </div>
        </section>

        <div class="border-t border-gray-300 my-6"></div>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">🔄 Integración con Nuestra Plataforma</h2>

            <div class="mb-6">
                <h3 class="text-xl font-medium text-yellow-700 mb-2">En la App Móvil:</h3>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>📈 Gráficos de evolución del compost en tiempo real</li>
                    <li>⏰ Recibe recordatorios para voltear</li>
                    <li>📋 Inventario de materiales disponibles</li>
                    <li>🔔 Alertas cuando necesites añadir materiales</li>
                </ul>
            </div>

            <div class="mb-6">
                <h3 class="text-xl font-medium text-yellow-700 mb-2">En la Web:</h3>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li><strong>📊 Dashboard</strong> - Visualización principal con gráficos y datos en tiempo real del
                        compostaje (temperatura, humedad, gases)</li>
                    <li><strong>📦 Gestión de Productos</strong> - Registrar, editar y gestionar los productos de compost
                        listos para la venta</li>
                    <li><strong>🧾 Gestión de Comprobantes de Pago</strong> - Emitir y administrar comprobantes de venta en
                        formato digital</li>
                    <li><strong>🌿 Materiales Compostables</strong> - Consultar lista de materiales que se pueden compostar,
                        con filtros y recomendaciones</li>
                    <li><strong>💎 Adquirir Planes</strong> - Suscripción a planes que habilitan funciones extras en la
                        plataforma</li>
                    <li><strong>📈 Gestión de Reportes</strong> - Generar reportes de ventas y lecturas de sensores, con
                        opción de exportar en PDF o Excel</li>
                    <li><strong>⚙️ Cambiar Contraseña</strong> - Actualizar credenciales de acceso desde el perfil</li>
                </ul>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">📚 Recursos Adicionales</h2>

            <div class="mb-4">
                <h3 class="text-xl font-medium text-gray-700 mb-2">Enlaces Recomendados:</h3>
                <p class="text-gray-700 mb-2 font-medium">Guías sobre materiales marrones y carbono</p>
                <ul class="list-decimal list-inside text-gray-700 ml-4 space-y-2 px-6 md:px-24">
                    <li><strong>Guía de materiales carbonosos (EPA)</strong> - Lista completa de materiales marrones<br>
                        <a href="https://www.epa.gov/recycle/composting-home" target="_blank"
                            class="text-yellow-600 hover:underline">🔗 EPA - Compostaje Doméstico</a>
                    </li>
                    <li><strong>Manual de compostaje con materiales secos</strong> - Técnicas específicas para marrones<br>
                        <a href="https://www.compost.org.uk/" target="_blank" class="text-yellow-600 hover:underline">🔗
                            The Composting Association</a>
                    </li>
                    <li><strong>Tabla de relaciones C/N</strong> - Valores específicos de carbono por material<br>
                        <a href="https://compostajedomestico.wordpress.com" target="_blank"
                            class="text-yellow-600 hover:underline">🔗 compostajedomestico.wordpress.com</a>
                    </li>
                </ul>
            </div>

            <div class="mb-4">
                <p class="text-gray-700 mb-2 font-medium">🎥 Video-tutoriales</p>
                <ul class="list-decimal list-inside text-gray-700 ml-4 space-y-2 px-6 md:px-24">
                    <li><strong>Cómo preparar materiales marrones (5 min)</strong> - Técnicas de trituración y
                        almacenamiento<br>
                        <a href="https://youtube.com" target="_blank" class="text-yellow-600 hover:underline">🔗 YouTube
                            - Compostaje Fácil</a>
                    </li>
                    <li><strong>Balance perfecto verde/marrón</strong> - Explicación visual de proporciones<br>
                        <a href="https://youtube.com" target="_blank" class="text-yellow-600 hover:underline">🔗 YouTube
                            - Huerto Urbano</a>
                    </li>
                </ul>
            </div>
        </section>

        <section class="mb-8 p-4 bg-yellow-100 border-l-4 border-yellow-500 rounded text-center">
            <p class="text-yellow-800 font-semibold text-lg">
                🌍 El equilibrio perfecto entre materiales verdes y marrones es clave para un compostaje exitoso
            </p>
        </section>

    </div>

@endsection
