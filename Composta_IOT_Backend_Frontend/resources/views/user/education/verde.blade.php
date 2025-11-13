@extends('user.dashboard')

@section('title', 'Materiales Verdes en el Compostaje')

@section('content')

   <div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen">
        <!-- Flecha para volver atrás -->
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
        <h1 class="text-4xl font-bold text-green-700 mb-8 text-center">🍃 Materiales Verdes</h1>

        <div class="mb-8">
            <img src="{{ asset('img/materiales_verdes.png') }}" alt="Materiales verdes para compostaje"
                class="mx-auto rounded-lg shadow-md"
                style="width: 80%; max-width: 1200px; height: auto; aspect-ratio: 16/6;">
        </div>


        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">📋 ¿Qué son los Materiales Verdes?</h2>
            <p class="text-gray-700 leading-relaxed">
                Los materiales verdes son aquellos ricos en nitrógeno, esenciales para alimentar los microorganismos que
                descomponen la materia orgánica. Son generalmente húmedos, frescos y de color verde.
            </p>
            <div class="border-t border-gray-300 my-6"></div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">🟢 Beneficios Clave</h2>
            <ul class="list-disc list-inside text-gray-700 leading-loose px-6 md:px-24">
                <li>Aceleran el proceso de compostaje al proporcionar proteínas y nutrientes</li>
                <li>Generan calor necesario para la descomposición</li>
                <li>Mejoran la calidad del compost final</li>
                <li>Atraen microorganismos beneficiosos</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">📊 Proporción Recomendada</h2>
            <p
                class="text-gray-700 leading-relaxed bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-500 text-center text-xl font-bold">
                1 parte de materiales verdes : 2 partes de materiales marrones
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">🍎 Lista de Materiales Verdes Aceptados</h2>

            <div class="mb-6">
                <h3 class="text-xl font-medium text-green-700 mb-2">✅ Alta Disponibilidad de Nitrógeno</h3>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>Restos de frutas (manzanas, plátanos, naranjas)</li>
                    <li>Verduras (lechuga, zanahorias, tomates)</li>
                    <li>Césped fresco (en capas delgadas)</li>
                    <li>Hojas verdes recién cortadas</li>
                    <li>Restos de café y filtros</li>
                    <li>Bolistas de té (sin grapa)</li>
                </ul>
            </div>

            <div class="mb-6">
                <h3 class="text-xl font-medium text-green-600 mb-2">✅ Moderada Disponibilidad de Nitrógeno</h3>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>Estiércol de animales herbívoros (vacuno, equino)</li>
                    <li>Algas marinas</li>
                    <li>Plantas de jardín sin enfermedades</li>
                    <li>Paja semi-descompuesta</li>
                </ul>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">⚠️ Precauciones Importantes</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                    <thead class="bg-green-100">
                        <tr>
                            <th class="py-3 px-4 border-b border-gray-300 text-left font-semibold">Riesgo</th>
                            <th class="py-3 px-4 border-b border-gray-300 text-left font-semibold">Solución</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-300">Exceso de humedad</td>
                            <td class="py-2 px-4 border-b border-gray-300">Mezclar con materiales marrones</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-300">Malos olores</td>
                            <td class="py-2 px-4 border-b border-gray-300">Aireación adecuada</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-300">Compactación</td>
                            <td class="py-2 px-4 border-b border-gray-300">Capas delgadas y mezcla</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">🚫 Materiales Verdes a Evitar</h2>
            <ul class="list-disc list-inside text-gray-700 leading-loose px-6 md:px-24">
                <li>Carne y pescado (atraen plagas)</li>
                <li>Productos lácteos (generan mal olor)</li>
                <li>Aceites y grasas</li>
                <li>Plantas enfermas o con pesticidas</li>
                <li>Heces de perros o gatos</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">✂️ Técnicas de Preparación</h2>

            <div class="mb-4">
                <h3 class="text-xl font-medium text-gray-700 mb-2">Corte y Troceado</h3>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>Cortar en trozos de 2-5 cm para acelerar descomposición</li>
                    <li>Usar tijeras o triturador manual</li>
                </ul>
            </div>

            <div class="mb-4">
                <h3 class="text-xl font-medium text-gray-700 mb-2">Mezcla Ideal</h3>
                <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500">
                    <p class="text-gray-700"><strong>material_verde</strong> = 25-30% del total</p>
                    <p class="text-gray-700"><strong>material_marrón</strong> = 60-70% del total</p>
                    <p class="text-gray-700"><strong>tierra</strong> = 10% del total</p>
                </div>
            </div>
        </section>

        <div class="border-t border-gray-300 my-6"></div>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">🔍 Señales de Problemas y Soluciones</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                    <thead class="bg-green-100">
                        <tr>
                            <th class="py-3 px-4 border-b border-gray-300 text-left font-semibold">Problema</th>
                            <th class="py-3 px-4 border-b border-gray-300 text-left font-semibold">Causa</th>
                            <th class="py-3 px-4 border-b border-gray-300 text-left font-semibold">Solución</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-300">Olor a podrido</td>
                            <td class="py-2 px-4 border-b border-gray-300">Exceso de materiales verdes</td>
                            <td class="py-2 px-4 border-b border-gray-300">Añadir materiales marrones</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-300">Mosquitos</td>
                            <td class="py-2 px-4 border-b border-gray-300">Materiales muy húmedos</td>
                            <td class="py-2 px-4 border-b border-gray-300">Mezclar y airear</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-300">Proceso lento</td>
                            <td class="py-2 px-4 border-b border-gray-300">Falta de materiales verdes</td>
                            <td class="py-2 px-4 border-b border-gray-300">Añadir más nitrógeno</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">📈 Monitoreo con Nuestro Sistema IoT</h2>

            <div class="mb-4">
                <h3 class="text-xl font-medium text-gray-700 mb-2">Parámetros Ideales para Materiales Verdes</h3>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>Temperatura: 55-65°C</li>
                    <li>Humedad: 40-60%</li>
                    <li>Trituración: sistema mecánico</li>
                    <li>Humectación: sistema automático</li>
                    <li>Ventilación: sistema automático</li>
                </ul>
            </div>

            <div class="mb-4">
                <h3 class="text-xl font-medium text-gray-700 mb-2">Alertas Automáticas</h3>
                <p class="text-gray-700 mb-2">Nuestro sistema te notificará cuando:</p>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>📱 La temperatura supere 70°C (exceso de actividad)</li>
                    <li>📱 La humedad baje del 40% (necesita riego)</li>
                    <li>📱 Se detecten condiciones anaeróbicas o condiciones críticas</li>
                </ul>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">🌱 Ejemplos Prácticos</h2>

            <div class="mb-6 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                <h3 class="text-xl font-medium text-blue-700 mb-2">Compostaje Rápido</h3>
                <ul class="list-none text-gray-700 space-y-1 px-6 md:px-24">
                    <li>● Materiales marrones (hojas secas): 3.0 kg</li>
                    <li>● Materiales verdes (residuos frescos): 1.5 kg</li>
                    <li>● Complementos: 0.5 kg de tierra + 0.05 kg de agua con miel (aplicada con bomba automática)</li>
                    <li>● 🔢 Masa total inicial: 3.0+1.5+0.5+0.05=5.05 kg</li>
                    <li>● ⏳ Tiempo estimado de proceso: 30–35 días</li>
                    <li>● ✅ Rendimiento observado: 99 % de la masa inicial</li>
                    <li>● ⚖️ Peso final obtenido: 5.0 kg de compost fresco</li>
                    <li>● 📦 Escalado a contenedor de 50 kg: 49.5–50 kg de compost fresco (manteniendo proporciones y
                        condiciones)</li>
                </ul>
            </div>

            <div class="p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                <h3 class="text-xl font-medium text-yellow-700 mb-2">🐢 Compostaje Tradicional – Parámetros Generales</h3>
                <ul class="list-none text-gray-700 px-6 md:px-24">
                    <li>• Materiales verdes (residuos frescos): 2 kg</li>
                    <li>• Materiales marrones (hojas secas): 8 kg</li>
                    <li>• ⏳ Tiempo estimado de proceso: 60–90 días</li>
                </ul>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">💡 Consejos Expertos</h2>

            <div class="mb-4">
                <h3 class="text-xl font-medium text-green-600 mb-2">Para Principiantes</h3>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>Empieza con restos de frutas y verduras</li>
                    <li>Mantén un diario de lo que añades</li>
                    <li>Observa los cambios semanalmente</li>
                </ul>
            </div>

            <div class="mb-4">
                <h3 class="text-xl font-medium text-green-700 mb-2">Para Avanzados</h3>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>Experimenta con diferentes proporciones</li>
                    <li>Usa aceleradores naturales (ortiga, consuelda)</li>
                    <li>Implementa vermicompostaje combinado</li>
                </ul>
            </div>
        </section>

        <div class="border-t border-gray-300 my-6"></div>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">🔄 Integración con Nuestra Plataforma</h2>

            <div class="mb-6">
                <h3 class="text-xl font-medium text-green-700 mb-2">En la App Móvil:</h3>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>📈 Gráficos de evolución del compost en tiempo real</li>
                    <li>⏰ Recibe recordatorios para voltear</li>
                    <li>📋 Inventario de materiales disponibles</li>
                    <li>🔔 Alertas cuando necesites añadir materiales</li>
                </ul>
            </div>

            <div class="mb-6">
                <h3 class="text-xl font-medium text-green-700 mb-2">En la Web:</h3>
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
                <p class="text-gray-700 mb-2 font-medium">Guías de compostaje doméstico</p>
                <ul class="list-decimal list-inside text-gray-700 ml-4 space-y-2 px-6 md:px-24">
                    <li><strong>Guía básica de compostaje (España)</strong> - Explica paso a paso cómo hacer compost en
                        casa<br>
                        <a href="https://miteco.gob.es" target="_blank" class="text-green-600 hover:underline">🔗
                            miteco.gob.es</a>
                    </li>
                    <li><strong>Manual práctico de compost (Chile)</strong> - Guía con imágenes para aprender desde cero<br>
                        <a href="https://repositorioambiental.mma.gob.cl" target="_blank"
                            class="text-green-600 hover:underline">🔗 repositorioambiental.mma.gob.cl</a>
                    </li>
                    <li><strong>Guía rápida para compostar en casa (El Salvador)</strong> - Tríptico con instrucciones
                        básicas<br>
                        <a href="https://cidoc.ambiente.gob.sv" target="_blank" class="text-green-600 hover:underline">🔗
                            cidoc.ambiente.gob.sv</a>
                    </li>
                    <li><strong>Mini-guía descargable (PDF)</strong> - Hoja resumida para imprimir o consultar<br>
                        <a href="https://anacossostenibilidad.com" target="_blank"
                            class="text-green-600 hover:underline">🔗
                            anacossostenibilidad.com</a>
                    </li>
                </ul>
            </div>

            <div class="mb-4">
                <p class="text-gray-700 mb-2 font-medium">🧮 Tabla de Carbono/Nitrógeno (C/N)</p>
                <ul class="list-decimal list-inside text-gray-700 ml-4 space-y-2 px-6 md:px-24">
                    <li><strong>Tabla de materiales con su relación C/N</strong> - Muestra el valor C/N de hojas, frutas,
                        estiércol, etc.<br>
                        <a href="https://compostajedomestico.wordpress.com" target="_blank"
                            class="text-green-600 hover:underline">🔗 compostajedomestico.wordpress.com</a>
                    </li>
                    <li><strong>Explicación sencilla del equilibrio C/N</strong> - Explica por qué es importante y cómo
                        ajustarlo<br>
                        <a href="https://compostandociencia.com" target="_blank"
                            class="text-green-600 hover:underline">🔗
                            compostandociencia.com</a>
                    </li>
                </ul>
            </div>

            <div class="mb-4">
                <p class="text-gray-700 mb-2 font-medium">🎥 Video-tutoriales</p>
                <ul class="list-decimal list-inside text-gray-700 ml-4 space-y-2 px-6 md:px-24">
                    <li><strong>Cómo hacer compost en casa (3 min)</strong> - Video corto con demostración clara<br>
                        <a href="https://youtube.com" target="_blank" class="text-green-600 hover:underline">🔗 YouTube -
                            Basura Cero</a>
                    </li>
                    <li><strong>Guía paso a paso del INTA (Argentina)</strong> - Detallado y fácil de seguir<br>
                        <a href="https://youtube.com" target="_blank" class="text-green-600 hover:underline">🔗 YouTube -
                            INTA</a>
                    </li>
                    <li><strong>7 técnicas de compostaje (FAO)</strong> - Muestra distintos métodos caseros<br>
                        <a href="https://fao.org" target="_blank" class="text-green-600 hover:underline">🔗 FAO</a>
                    </li>
                </ul>
            </div>
        </section>

        <section class="mb-8 p-4 bg-green-100 border-l-4 border-green-500 rounded text-center">
            <p class="text-green-800 font-semibold text-lg">
                🌍 Juntos hacemos del compostaje una práctica accesible y tecnológica
            </p>
        </section>

    </div>
@endsection
