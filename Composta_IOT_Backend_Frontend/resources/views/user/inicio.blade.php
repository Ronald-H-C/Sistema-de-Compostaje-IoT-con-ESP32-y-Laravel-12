@extends('user.dashboard')
@section('content')
<div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        /* CSS para los efectos hover de las cards */
        .card-sensor {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .card-sensor:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
        /* Ajustes específicos para canvas para evitar el estiramiento si el contenedor es muy alto */
        canvas {
            max-height: 300px; /* Limita la altura del canvas para evitar que se estire demasiado */
            width: 100% !important;
            height: auto !important;
        }
    </style>

    <h1 class="text-3xl font-extrabold text-center mb-8 text-gray-800 tracking-tight">
        Dashboard IoT - <span class="text-green-600">Compostaje</span>
    </h1>

    <div id="alerta" class="hidden bg-red-600 text-white text-center p-4 rounded-lg mb-8 shadow-md animate-pulse">
        <p class="text-lg font-semibold">⚠️ ¡ALERTA! Niveles peligrosos detectados por el sensor MQ-135 ⚠️</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <div class="bg-white p-4 rounded-lg shadow-md border border-gray-100 card-sensor">
            <h2 class="text-base font-semibold text-gray-600 mb-1">Temp. Ambiente <span class="text-xs text-gray-400">(DHT22)</span></h2>
            <p id="temp" class="text-2xl font-bold text-blue-600">-- °C</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md border border-gray-100 card-sensor">
            <h2 class="text-base font-semibold text-gray-600 mb-1">Humedad Ambiente <span class="text-xs text-gray-400">(DHT22)</span></h2>
            <p id="hum" class="text-2xl font-bold text-teal-600">-- %</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md border border-gray-100 card-sensor">
            <h2 class="text-base font-semibold text-gray-600 mb-1">Conc. de Gas <span class="text-xs text-gray-400">(MQ-135)</span></h2>
            <p id="mq135" class="text-2xl font-bold text-purple-600">--</p>
            <p id="air_status" class="text-sm mt-1 text-gray-500 font-medium">---</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md border border-gray-100 card-sensor">
            <h2 class="text-base font-semibold text-gray-600 mb-1">Temp. Suelo <span class="text-xs text-gray-400">(DS18B20)</span></h2>
            <p id="ds18b20" class="text-2xl font-bold text-orange-600">-- °C</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md border border-gray-100 card-sensor">
            <h2 class="text-base font-semibold text-gray-600 mb-1">Humedad Suelo <span class="text-xs text-gray-400">(Capacitivo)</span></h2>
            <p id="soil" class="text-2xl font-bold text-indigo-600">-- %</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md border border-gray-100 card-sensor flex flex-col justify-between">
            <h2 class="text-base font-semibold text-gray-600 mb-1">Exportar Reporte</h2>
            <p id="status" class="text-xl font-bold text-gray-800">---</p>
            <button onclick="descargarCSV()" class="mt-3 bg-green-500 hover:bg-green-600 text-white py-2 px-3 rounded-md text-sm font-semibold transition duration-200">
                📥 CSV
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <div class="bg-white p-4 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-xl font-semibold mb-3 text-gray-700">Gráfico de Tiempo Real</h3>
            <canvas id="lineChart"></canvas>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md border border-gray-100">
            <h3 class="text-xl font-semibold mb-3 text-gray-700">Composición Estimada de Gases</h3>
            <canvas id="doughnutChart"></canvas>
        </div>
    </div>

    <audio id="alert_sound" preload="auto">
        <source src="{{ asset('alert.mp3') }}" type="audio/mp3">
        Your browser does not support the audio element.
    </audio>

    <script>
    let lineChart, doughnutChart;
    let registros = @json($data); // Carga inicial de datos históricos
    const MAX_PUNTOS_GRAFICO = 50; // ¿Cuántos puntos mostrar a la vez?

    // --- TUS FUNCIONES (actualizarCards, mostrarAlerta, mapData) ---
    // ... (Estas se quedan igual que en tu código original) ...
    // ... (La función descargarCSV también se queda igual) ...

   function actualizarCards(d) {
        document.getElementById("temp").textContent = d.temperature !== undefined ? d.temperature + " °C" : "-- °C";
        document.getElementById("hum").textContent = d.humidity !== undefined ? d.humidity + " %" : "-- %";
        document.getElementById("mq135").textContent = d.mq135 !== undefined ? d.mq135 + " ppm" : "--";
        document.getElementById("status").textContent = d.status !== undefined ? d.status : "Sin datos";
        document.getElementById("ds18b20").textContent = d.ds18b20_temp !== undefined ? d.ds18b20_temp + " °C" : "-- °C";
        document.getElementById("soil").textContent = d.soil_moisture !== undefined ? d.soil_moisture + " %" : "-- %";
        
        const airStatusElement = document.getElementById("air_status");
        if (d.air_quality_status !== undefined) {
            airStatusElement.textContent = d.air_quality_status;
            airStatusElement.classList.remove('text-green-600', 'text-orange-500', 'text-red-600');
            if (d.air_quality_status === "Aire Limpio") {
                airStatusElement.classList.add('text-green-600');
            } else if (d.air_quality_status === "Calidad Moderada") {
                airStatusElement.classList.add('text-orange-500');
            } else if (d.air_quality_status === "Gases nocivos") {
                airStatusElement.classList.add('text-red-600');
            }
        } else {
            airStatusElement.textContent = "Evaluando...";
            airStatusElement.classList.add('text-gray-500');
        }
    }

    function mostrarAlerta(d) {
        const alerta = document.getElementById("alerta");
        const alertSound = document.getElementById("alert_sound");
        if (d.air_quality_status === "Gases nocivos" || (d.mq135 && d.mq135 > 600)) {
            alerta.classList.remove("hidden");
            if (alertSound.paused) {
                alertSound.play().catch(e => console.error("Error al reproducir audio:", e));
            }
        } else {
            alerta.classList.add("hidden");
            alertSound.pause();
            alertSound.currentTime = 0;
        }
    }

    
    function mapData(dataArray, property) {
        return dataArray.map(d => d[property] !== undefined ? d[property] : null);
    }

    /**
     * Dibuja los gráficos POR PRIMERA VEZ con los datos históricos
     */
    function dibujarGraficosIniciales(data) {
        const labels = data.map(d => d.date + " " + d.time);
        const ctx = document.getElementById("lineChart").getContext("2d");

        if (lineChart) lineChart.destroy(); // Limpia por si acaso

        lineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    { label: "Temp. Ambiente (°C)", data: data.map(d => d.temperature), borderColor: "#EF4444", /*... (resto de tu config) */ },
                    { label: "Humedad Ambiente (%)", data: data.map(d => d.humidity), borderColor: "#3B82F6", /*... (resto de tu config) */ },
                    { label: "Temp. Suelo (°C)", data: data.map(d => d.ds18b20_temp), borderColor: "#F59E0B", /*... (resto de tu config) */ },
                    { label: "Humedad Suelo (%)", data: mapData(data, 'soil_moisture'), borderColor: "#8B5CF6", /*... (resto de tu config) */ },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                // ... (tu función de callback de tooltip) ...
                            }
                        }
                    },
                    title: { display: false }
                },
                scales: {
                    x: {
                        title: { display: true, text: 'Fecha y Hora' },
                        ticks: { autoSkip: true, maxRotation: 45, minRotation: 45, maxTicksLimit: 10 }
                    },
                    y1: {
                        type: 'linear',
                        position: 'left',
                        title: { display: true, text: 'Valores (%) / (°C)' },
                        min: 0, max: 100, 
                        grid: { drawOnChartArea: true }
                    },
                    y2: {
                        type: 'linear',
                        position: 'right',
                        title: { display: true },
                        min: 0, max: 100,
                        grid: { drawOnChartArea: false }
                    }
                },
                
                // --- ¡AQUÍ ESTÁ LA LÍNEA AÑADIDA! ---
                animation: {
                    duration: 400 // Una animación suave al actualizar
                }
                // ------------------------------------
            }
        });

        // Dibuja el gráfico de dona (solo usa el último dato)
        const lastData = data.at(-1) || {};
        actualizarGraficoDona(lastData, true); // true = es la primera vez
    }

    /**
     * ¡NUEVA! Actualiza el gráfico de dona
     * El parámetro 'primeraVez' es para crear el gráfico o solo actualizarlo
     */
    function actualizarGraficoDona(d, primeraVez = false) {
        const gases = {
            "Amoníaco (NH₃)": parseFloat(d.ammonia || 0),
            "Dióxido de Carbono (CO₂)": parseFloat(d.co2 || 0),
            "Monóxido de Carbono (CO)": parseFloat(d.co || 0),
            "Benceno": parseFloat(d.benzene || 0),
            "Alcohol": parseFloat(d.alcohol || 0),
            "Humo": parseFloat(d.smoke || 0)
        };

        if (primeraVez) {
            const ctx2 = document.getElementById("doughnutChart").getContext("2d");
            if (doughnutChart) doughnutChart.destroy();
            doughnutChart = new Chart(ctx2, {
                type: "doughnut",
                data: {
                    labels: Object.keys(gases),
                    datasets: [{
                        data: Object.values(gases),
                        // ... (tu config de colores, etc.) ...
                    }]
                },
                options: {
                    // ... (Todas tus opciones de 'doughnutChart' van aquí) ...
                }
            });
        } else {
            // Si ya existe, solo actualiza los datos
            doughnutChart.data.datasets[0].data = Object.values(gases);
            doughnutChart.update();
        }
    }

    /**
     * ¡NUEVA! Esta es la función clave para el "movimiento"
     */
    function actualizarGraficoLinea(nuevoDato) {
        if (!lineChart) return; // No hacer nada si el gráfico no se ha inicializado

        // 1. Añadir el nuevo label
        const nuevoLabel = nuevoDato.date + " " + nuevoDato.time;
        lineChart.data.labels.push(nuevoLabel);

        // 2. Añadir los nuevos datos a cada dataset
        lineChart.data.datasets[0].data.push(nuevoDato.temperature);
        lineChart.data.datasets[1].data.push(nuevoDato.humidity);
        lineChart.data.datasets[2].data.push(nuevoDato.ds18b20_temp);
        lineChart.data.datasets[3].data.push(nuevoDato.soil_moisture !== undefined ? nuevoDato.soil_moisture : null);

        // 3. Quitar el dato más antiguo (para crear el efecto de scroll)
        if (lineChart.data.labels.length > MAX_PUNTOS_GRAFICO) {
            lineChart.data.labels.shift(); // Quita el primer label
            lineChart.data.datasets.forEach((dataset) => {
                dataset.data.shift(); // Quita el primer dato de cada dataset
            });
        }

        // 4. ¡Actualizar el gráfico con animación!
        lineChart.update();
    }


    // --- LÓGICA DE CARGA INICIAL (casi igual) ---
    if (registros.length > 0) {
        actualizarCards(registros.at(-1));
        mostrarAlerta(registros.at(-1));
        
        // Limita los registros iniciales si son demasiados (opcional pero recomendado)
        const registrosIniciales = registros.slice(-MAX_PUNTOS_GRAFICO);
        dibujarGraficosIniciales(registrosIniciales);
    } else {
        dibujarGraficosIniciales([]);
        actualizarCards({});
    }

    // --- SET INTERVAL (MODIFICADO) ---
    // Lo cambié a 5 segundos (5000ms). 1 segundo es demasiado.
    setInterval(async () => {
        try {
            // ¡Usamos la NUEVA RUTA que solo trae el último dato!
            const res = await fetch("{{ route('dashboard.latestData') }}"); 
            if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
            
            const nuevoDato = await res.json();

            // Evitar actualizar si el dato es el mismo que el último
            // (Comparamos por tiempo)
            const ultimoLabel = lineChart.data.labels.at(-1);
            const nuevoLabel = nuevoDato.date + " " + nuevoDato.time;
            
            if (nuevoLabel !== ultimoLabel) {
            
                // 1. Actualizar las tarjetas
                actualizarCards(nuevoDato);
                
                // 2. Mostrar alertas
                mostrarAlerta(nuevoDato);
                
                // 3. Actualizar el gráfico de línea (con movimiento)
                actualizarGraficoLinea(nuevoDato);

                // 4. Actualizar el gráfico de dona
                actualizarGraficoDona(nuevoDato, false); // false = no es la primera vez

                // 5. Agregar al array 'registros' para el CSV (opcional)
                registros.push(nuevoDato);
            }

        } catch (e) {
            console.error("Error al actualizar datos en vivo:", e);
        }
    }, 5000); // Actualiza cada 5 segundos

    function descargarCSV() {
        const encabezado = "Fecha,Hora,Temp,Hum,MQ135,Estado,NH3,CO2,CO,Benceno,Alcohol,Humo,DS18B20,Suelo\n";
        const filas = registros.map(d => [
            d.date, d.time, d.temperature, d.humidity, d.mq135, d.air_quality_status,
            d.ammonia, d.co2, d.co, d.benzene, d.alcohol, d.smoke,
            d.ds18b20_temp, d.soil_moisture
        ].join(",")).join("\n");

        const blob = new Blob([encabezado + filas], { type: "text/csv;charset=utf-8;" });
        const url = URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = "datos_compost.csv";
        a.style.display = "none";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

</script>

    <div class="mt-8 text-center">
        <a href="{{ route('historial') }}"
           class="inline-flex items-center px-5 py-2.5 bg-green-500 text-white font-semibold rounded-lg shadow-md hover:bg-green-600 transition duration-200 ease-in-out transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
            Ver Historial de Lecturas
        </a>
    </div>
</div>
@endsection