@extends('user.dashboard')

@section('title', 'Materiales No Compostables')

@section('content')
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

        <h1 class="text-4xl font-bold text-red-700 mb-8 text-center">🚫 Materiales No Compostables</h1>

        <!-- Imagen ilustrativa -->
        <div class="mb-8">
            <img src="{{ asset('img/materiales_no_compostables.png') }}" alt="Materiales no compostables que deben evitarse"
                class="mx-auto rounded-lg shadow-md"
                style="width: 80%; max-width: 1200px; height: auto; aspect-ratio: 16/6;">
        </div>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">📋 ¿Qué son los Materiales No Compostables?</h2>
            <p class="text-gray-700 leading-relaxed">
                Los materiales no compostables son aquellos que no deben incluirse en el proceso de compostaje debido a que
                pueden causar problemas de salud, contaminación ambiental, atraer plagas o ralentizar el proceso de
                descomposición.
            </p>
            <div class="border-t border-gray-300 my-6"></div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">⚠️ Riesgos y Peligros Principales</h2>
            <ul class="list-disc list-inside text-gray-700 leading-loose px-6 md:px-24">
                <li>Proliferación de bacterias patógenas y enfermedades</li>
                <li>Atracción de roedores, moscas y otras plagas</li>
                <li>Generación de malos olores por descomposición anaeróbica</li>
                <li>Contaminación del compost con sustancias tóxicas</li>
                <li>Acumulación de materiales no biodegradables</li>
                <li>Inhibición del proceso de compostaje natural</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">📊 Impacto en el Proceso de Compostaje</h2>
            <p
                class="text-gray-700 leading-relaxed bg-red-50 p-4 rounded-lg border-l-4 border-red-500 text-center text-xl font-bold">
                Incluir solo un 5% de materiales no compostables puede arruinar el 100% de tu compost
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">🚫 Lista Completa de Materiales No Compostables</h2>

            <div class="mb-6">
                <h3 class="text-xl font-medium text-red-700 mb-2">❌ Productos de Origen Animal</h3>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>Carne, pescado y mariscos (crudos o cocidos)</li>
                    <li>Huesos de cualquier tipo</li>
                    <li>Productos lácteos (leche, queso, yogurt, mantequilla)</li>
                    <li>Grasas, aceites y manteca animal</li>
                    <li>Huevos (excepto cáscaras limpias)</li>
                </ul>
            </div>

            <div class="mb-6">
                <h3 class="text-xl font-medium text-red-600 mb-2">❌ Materiales Procesados y Sintéticos</h3>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>Plásticos de cualquier tipo (bolsas, envases, films)</li>
                    <li>Vidrio, metal y aluminio</li>
                    <li>Papel plastificado, encerado o con tintas tóxicas</li>
                    <li>Telas sintéticas y productos textiles no naturales</li>
                    <li>Productos de limpieza y químicos domésticos</li>
                </ul>
            </div>

            <div class="mb-6">
                <h3 class="text-xl font-medium text-red-500 mb-2">❌ Residuos Sanitarios y Peligrosos</h3>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>Pañales desechables y toallas sanitarias</li>
                    <li>Papel higiénico usado y productos de higiene personal</li>
                    <li>Medicamentos y productos farmacéuticos</li>
                    <li>Pilas, baterías y componentes electrónicos</li>
                    <li>Colillas de cigarro y cenizas de carbón</li>
                </ul>
            </div>

            <div class="mb-6">
                <h3 class="text-xl font-medium text-red-400 mb-2">❌ Plantas y Materiales Problemáticos</h3>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>Plantas enfermas o con pesticidas</li>
                    <li>Malezas persistentes con semillas maduras</li>
                    <li>Hojas de nogal (contienen juglona, un inhibidor natural)</li>
                    <li>Restos de comida con exceso de sal o aceite</li>
                    <li>Heces de perros, gatos o humanos</li>
                </ul>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">⚠️ Problemas Específicos y Soluciones</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                    <thead class="bg-red-100">
                        <tr>
                            <th class="py-3 px-4 border-b border-gray-300 text-left font-semibold">Material Problemático
                            </th>
                            <th class="py-3 px-4 border-b border-gray-300 text-left font-semibold">Problema Causado</th>
                            <th class="py-3 px-4 border-b border-gray-300 text-left font-semibold">Solución</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-300">Carne y lácteos</td>
                            <td class="py-2 px-4 border-b border-gray-300">Atraen plagas y generan mal olor</td>
                            <td class="py-2 px-4 border-b border-gray-300">Disposición en contenedor de residuos orgánicos
                                no compostables</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-300">Plásticos y vidrios</td>
                            <td class="py-2 px-4 border-b border-gray-300">Contaminación física del compost</td>
                            <td class="py-2 px-4 border-b border-gray-300">Separar para reciclaje</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-300">Plantas enfermas</td>
                            <td class="py-2 px-4 border-b border-gray-300">Propagación de enfermedades</td>
                            <td class="py-2 px-4 border-b border-gray-300">Incineración o disposición especial</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-300">Productos químicos</td>
                            <td class="py-2 px-4 border-b border-gray-300">Toxicidad para microorganismos y plantas</td>
                            <td class="py-2 px-4 border-b border-gray-300">Puntos limpios o recolección especial</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">♻️ Alternativas de Disposición Correcta</h2>

            <div class="mb-4">
                <h3 class="text-xl font-medium text-gray-700 mb-2">Gestión por Categorías</h3>
                <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500">
                    <p class="text-gray-700"><strong>Reciclables:</strong> Plásticos, vidrios, metales, cartones limpios →
                        Contenedor de reciclaje</p>
                    <p class="text-gray-700"><strong>Peligrosos:</strong> Pilas, medicamentos, químicos → Puntos limpios o
                        recolección especial</p>
                    <p class="text-gray-700"><strong>Orgánicos no compostables:</strong> Carnes, lácteos → Contenedor de
                        restos orgánicos</p>
                    <p class="text-gray-700"><strong>Sanitarios:</strong> Pañales, toallas higiénicas → Contenedor de restos
                        no reciclables</p>
                </div>
            </div>
        </section>

        <div class="border-t border-gray-300 my-6"></div>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">🔍 Señales de Contaminación en el Compost</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                    <thead class="bg-red-100">
                        <tr>
                            <th class="py-3 px-4 border-b border-gray-300 text-left font-semibold">Señal</th>
                            <th class="py-3 px-4 border-b border-gray-300 text-left font-semibold">Posible Causa</th>
                            <th class="py-3 px-4 border-b border-gray-300 text-left font-semibold">Acción Correctiva</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-300">Olor putrefacto intenso</td>
                            <td class="py-2 px-4 border-b border-gray-300">Presencia de carne o lácteos</td>
                            <td class="py-2 px-4 border-b border-gray-300">Retirar material contaminado y añadir material
                                marrón</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-300">Presencia de moscas y larvas</td>
                            <td class="py-2 px-4 border-b border-gray-300">Materiales animales o exceso de humedad</td>
                            <td class="py-2 px-4 border-b border-gray-300">Cubrir con capa de material marrón y voltear</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-300">Materiales sin descomponer</td>
                            <td class="py-2 px-4 border-b border-gray-300">Plásticos o materiales sintéticos</td>
                            <td class="py-2 px-4 border-b border-gray-300">Cribar el compost y retirar contaminantes</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-300">Ausencia de actividad microbiana</td>
                            <td class="py-2 px-4 border-b border-gray-300">Productos químicos tóxicos</td>
                            <td class="py-2 px-4 border-b border-gray-300">Reiniciar el compost con materiales seguros</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">📈 Monitoreo con Nuestro Sistema IoT</h2>

            <div class="mb-4">
                <h3 class="text-xl font-medium text-gray-700 mb-2">Detección de Problemas</h3>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>Cambios bruscos de temperatura indicando descomposición anaeróbica</li>
                    <li>Alteraciones en los niveles de gases (metano, sulfhídrico)</li>
                    <li>Patrones anormales de humedad y pH</li>
                </ul>
            </div>

            <div class="mb-4">
                <h3 class="text-xl font-medium text-gray-700 mb-2">Alertas Automáticas</h3>
                <p class="text-gray-700 mb-2">Nuestro sistema te notificará cuando:</p>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>📱 Se detecten olores fuertes (niveles anormales de gases)</li>
                    <li>📱 La temperatura descienda abruptamente (muerte microbiana)</li>
                    <li>📱 Se identifiquen materiales no compostables mediante análisis</li>
                </ul>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">🌱 Casos Prácticos de Contaminación</h2>

            <div class="mb-6 p-4 bg-red-50 rounded-lg border-l-4 border-red-500">
                <h3 class="text-xl font-medium text-red-700 mb-2">Caso: Contaminación por Plásticos</h3>
                <ul class="list-none text-gray-700 space-y-1 px-6 md:px-24">
                    <li>● Situación: Usuario añadió bolsas de té con grapas plásticas</li>
                    <li>● Resultado: Microplásticos en el compost final</li>
                    <li>● Impacto: Contaminación del suelo y plantas</li>
                    <li>● Solución: Cribado exhaustivo y educación sobre materiales</li>
                    <li>● Tiempo de recuperación: 2-3 meses</li>
                </ul>
            </div>

            <div class="p-4 bg-orange-50 rounded-lg border-l-4 border-orange-500">
                <h3 class="text-xl font-medium text-orange-700 mb-2">Caso: Infestación por Carnes</h3>
                <ul class="list-none text-gray-700 px-6 md:px-24">
                    <li>• Situación: Restos de pescado incluidos en el compost</li>
                    <li>• Resultado: Olores putrefactos y proliferación de moscas</li>
                    <li>• Impacto: Compost inutilizable y problemas vecinales</li>
                    <li>• Solución: Eliminación completa y reinicio del proceso</li>
                </ul>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">💡 Consejos para Evitar Errores</h2>

            <div class="mb-4">
                <h3 class="text-xl font-medium text-red-600 mb-2">Para Principiantes</h3>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>Mantén una lista visible de materiales no permitidos en tu área de compostaje</li>
                    <li>Cuando tengas dudas, mejor no añadir el material</li>
                    <li>Educa a todos los miembros del hogar sobre qué puede y no puede compostarse</li>
                </ul>
            </div>

            <div class="mb-4">
                <h3 class="text-xl font-medium text-red-700 mb-2">Para Comunidades y Escuelas</h3>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>Implementa sistemas de etiquetado claro en contenedores</li>
                    <li>Realiza sesiones educativas periódicas</li>
                    <li>Establece un sistema de monitoreo y retroalimentación</li>
                </ul>
            </div>
        </section>

        <div class="border-t border-gray-300 my-6"></div>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">🔄 Integración con Nuestra Plataforma</h2>

            <div class="mb-6">
                <h3 class="text-xl font-medium text-red-700 mb-2">En la App Móvil:</h3>
                <ul class="list-disc list-inside text-gray-700 ml-4 px-6 md:px-24">
                    <li>📈 Gráficos de evolución del compost en tiempo real</li>
                    <li>⏰ Recibe recordatorios para voltear</li>
                    <li>📋 Inventario de materiales disponibles</li>
                    <li>🔔 Alertas cuando necesites añadir materiales</li>
                </ul>
            </div>

            <div class="mb-6">
                <h3 class="text-xl font-medium text-red-700 mb-2">En la Web:</h3>
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
                <p class="text-gray-700 mb-2 font-medium">Guías sobre gestión de residuos no compostables</p>
                <ul class="list-decimal list-inside text-gray-700 ml-4 space-y-2 px-6 md:px-24">
                    <li><strong>Guía de residuos peligrosos domésticos (EPA)</strong> - Manejo seguro de materiales
                        tóxicos<br>
                        <a href="https://www.epa.gov/hw" target="_blank" class="text-red-600 hover:underline">🔗 EPA -
                            Residuos Peligrosos</a>
                    </li>
                    <li><strong>Directrices de compostaje seguro (OMS)</strong> - Prevención de riesgos sanitarios<br>
                        <a href="https://www.who.int" target="_blank" class="text-red-600 hover:underline">🔗
                            Organización
                            Mundial de la Salud</a>
                    </li>
                    <li><strong>Manual de buenas prácticas en compostaje</strong> - Evitando errores comunes<br>
                        <a href="https://www.compost.org" target="_blank" class="text-red-600 hover:underline">🔗
                            Asociación
                            de Compostaje</a>
                    </li>
                </ul>
            </div>

            <div class="mb-4">
                <p class="text-gray-700 mb-2 font-medium">🎥 Video-tutoriales</p>
                <ul class="list-decimal list-inside text-gray-700 ml-4 space-y-2 px-6 md:px-24">
                    <li><strong>Cómo identificar materiales no compostables (7 min)</strong> - Guía visual con ejemplos<br>
                        <a href="https://youtube.com" target="_blank" class="text-red-600 hover:underline">🔗 YouTube -
                            Compostaje Seguro</a>
                    </li>
                    <li><strong>Errores comunes y cómo solucionarlos</strong> - Casos prácticos de recuperación<br>
                        <a href="https://youtube.com" target="_blank" class="text-red-600 hover:underline">🔗 YouTube -
                            Soluciones de Compostaje</a>
                    </li>
                </ul>
            </div>
        </section>

        <section class="mb-8 p-4 bg-red-100 border-l-4 border-red-500 rounded text-center">
            <p class="text-red-800 font-semibold text-lg">
                🌍 Un compostaje responsable comienza con la correcta separación de materiales. ¡Tu atención a los detalles
                hace la diferencia!
            </p>
        </section>

    </div>
@endsection
