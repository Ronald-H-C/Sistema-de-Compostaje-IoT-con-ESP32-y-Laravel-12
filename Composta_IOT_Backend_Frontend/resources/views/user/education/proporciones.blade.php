@extends('user.dashboard')
@section('content')

<div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen">

    <!-- Flecha para volver atrás -->
    <div class="mb-4">
        <a href="{{ url()->previous() }}" class="inline-flex items-center text-yellow-700 hover:text-yellow-900 font-semibold transition duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
            </svg>
            Volver
        </a>
    </div>

    <!-- Título -->
    <h1 class="text-4xl font-bold text-green-700 mb-4 text-center">🧪 Proporciones para un Compostaje Efectivo</h1>

    <!-- Subtítulo -->
    <p class="text-center text-gray-600 mb-6 text-lg max-w-3xl mx-auto">
        Para un compost sano y eficiente, mezcla correctamente materiales ricos en carbono (🟤 marrones) y nitrógeno (🟢 verdes). <br>
        <strong class="text-green-800">Proporción ideal: 2-3 partes marrones por cada parte verde.</strong>
    </p>

    <!-- Visualización de proporción -->
    <div class="w-full max-w-3xl mx-auto mb-10">
        <div class="flex text-sm text-gray-800 font-medium mb-2 justify-between">
            <span>🟤 Marrones (2-3 partes)</span>
            <span>🟢 Verdes (1 parte)</span>
        </div>
        <div class="flex h-6 overflow-hidden rounded-lg shadow">
            <div class="w-2/3 bg-yellow-400 flex items-center justify-center text-white text-sm">Marrones</div>
            <div class="w-1/3 bg-green-500 flex items-center justify-center text-white text-sm">Verdes</div>
        </div>
    </div>

    <!-- Tarjetas de materiales -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12 max-w-5xl mx-auto">
        <!-- Marrones -->
        <div class="p-6 bg-yellow-50 hover:bg-yellow-100 rounded-xl shadow-lg border-l-4 border-yellow-500 transition">
            <h2 class="text-2xl font-semibold text-yellow-800 mb-4">🟤 Marrones (2-3 partes)</h2>
            <ul class="list-disc list-inside text-gray-800 leading-loose">
                <li>Hojas secas</li>
                <li>Ramas trituradas</li>
                <li>Papel y cartón sin tinta tóxica</li>
                <li>Viruta de madera</li>
                <li>Cáscaras de huevo secas</li>
            </ul>
        </div>

        <!-- Verdes -->
        <div class="p-6 bg-green-50 hover:bg-green-100 rounded-xl shadow-lg border-l-4 border-green-500 transition">
            <h2 class="text-2xl font-semibold text-green-800 mb-4">🟢 Verdes (1 parte)</h2>
            <ul class="list-disc list-inside text-gray-800 leading-loose">
                <li>Restos de frutas y verduras</li>
                <li>Posos de café y té</li>
                <li>Cáscaras de vegetales</li>
                <li>Césped recién cortado</li>
                <li>Flores marchitas</li>
            </ul>
        </div>
    </div>

    <!-- Consejos -->
    <section class="bg-gray-50 p-6 rounded-xl shadow max-w-4xl mx-auto border border-gray-200">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">💡 Consejos clave para mantener el equilibrio</h2>
        <ul class="list-disc list-inside text-gray-700 leading-relaxed space-y-2">
            <li><strong>¿Huele mal?</strong> Añade más materiales marrones para absorber la humedad y el olor.</li>
            <li><strong>¿Muy seco?</strong> Agrega materiales verdes o un poco de agua.</li>
            <li><strong>Remueve</strong> la mezcla cada 1-2 semanas para airearla.</li>
            <li><strong>Tritura</strong> los materiales grandes para acelerar su descomposición.</li>
        </ul>
    </section>

    <!-- Consejo visual -->
    <div class="mt-10 text-center bg-green-100 border border-green-300 p-6 rounded-lg max-w-3xl mx-auto">
        <p class="text-green-800 font-semibold text-lg">
            🌿 Tip: ¡Un compost equilibrado no huele mal y se convierte en abono en menos tiempo!
        </p>
    </div>

</div>
@endsection
