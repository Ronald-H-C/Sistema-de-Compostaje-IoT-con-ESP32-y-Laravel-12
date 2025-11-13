@extends('user.dashboard')

@section('title', 'Materiales para Compostaje')

@push('styles')
<style>
    .badge {
        @apply inline-block px-3 py-1 rounded-full text-xs font-semibold;
    }
</style>
@endpush

@section('content')
<div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen">
    <h1 class="text-4xl font-bold text-center text-green-700 mb-6">Materiales para Compostaje</h1>

    <!-- Botón de proporciones adecuadas -->
    <div class="flex justify-center mb-8">
        <a href="{{ route('proporciones') }}"
           class="inline-flex items-center bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded shadow transition">
            📏 Ver proporciones adecuadas
        </a>
    </div>
    <!-- Filtros -->
    <form method="POST" action="{{ route('materials.filter') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    @csrf

    <input type="text" name="search" value="{{ old('search') }}" placeholder="Buscar material..."
           class="form-input px-4 py-2 border border-gray-300 rounded shadow-sm">

    <select name="clasification" class="form-select px-4 py-2 border border-gray-300 rounded">
        <option value="">-- Clasificación --</option>
        <option value="verde" @selected(old('clasificacion') === 'verde')>Verde</option>
        <option value="marron" @selected(old('clasificacion') === 'marron')>Marrón</option>
        <option value="no_compostable" @selected(old('clasificacion') === 'no_compostable')>No compostable</option>
    </select>

    <select name="aptitude" class="form-select px-4 py-2 border border-gray-300 rounded">
        <option value="">-- Aptitud --</option>
        <option value="casero" @selected(old('aptitud') === 'casero')>Casero</option>
        <option value="industrial" @selected(old('aptitud') === 'industrial')>Industrial</option>
        <option value="no_recomendado" @selected(old('aptitud') === 'no_recomendado')>No recomendado</option>
    </select>

    <select name="type_category" class="form-select px-4 py-2 border border-gray-300 rounded">
        <option value="">-- Tipo --</option>
        <option value="alimentos" @selected(old('type_category') === 'alimentos')>Alimentos</option>
        <option value="jardin" @selected(old('type_category') === 'jardin')>Jardín</option>
        <option value="papel_carton" @selected(old('type_category') === 'papel_carton')>Papel/Cartón</option>
        <option value="otros" @selected(old('type_category') === 'otros')>Otros</option>
    </select>

    <button type="submit" class="md:col-span-4 mt-4 md:mt-0 btn bg-green-600 text-white px-4 py-2 rounded">
        Filtrar
    </button>
</form>


    <!-- Resultados -->
   <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @forelse($materials as $index => $material)
        <form method="POST" action="{{ route('verDetalle') }}" id="form-{{ $index }}" class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition cursor-pointer">
            @csrf
            <!-- Campo oculto -->
            <input type="hidden" name="clasification" value="{{ $material->clasification }}">

            <!-- Imagen que envía el formulario al hacer clic -->
            @if ($material->image)
                <img 
                    src="{{ asset($material->image) }}"
                    alt="Imagen de {{ $material->name }}" 
                    class="w-full h-48 object-cover rounded-md mb-4 hover:opacity-90 transition"
                    onclick="document.getElementById('form-{{ $index }}').submit();"
                >
            @endif

            <h2 class="text-xl font-bold text-green-800 mb-2">{{ $material->name }}</h2>
            <p class="text-gray-600 text-sm mb-4">{{ $material->description }}</p>

            <div class="flex flex-wrap gap-2">
                <span class="badge {{ $material->clasificacion === 'verde' ? 'bg-green-500 text-white' : ($material->clasificacion === 'marron' ? 'bg-yellow-500 text-black' : 'bg-red-400 text-white') }}">
                    {{ ucfirst($material->clasification) }}
                </span>
                <span class="badge bg-blue-200 text-blue-800">{{ ucfirst($material->aptitude) }}</span>
                <span class="badge bg-gray-200 text-gray-800">{{ str_replace('_', ' ', ucfirst($material->type_category)) }}</span>
            </div>
        </form>
    @empty
        <p class="col-span-3 text-center text-gray-500">No se encontraron materiales con los filtros seleccionados.</p>
    @endforelse
</div>



    <!-- Paginación -->
    <div class="mt-6">
        {{ $materials->withQueryString()->links() }}
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.clickable-image').forEach(function (img) {
            img.addEventListener('click', function () {
                const formId = img.getAttribute('data-form-id');
                const form = document.getElementById(formId);
                if (form) form.submit();
            });
        });
    });
</script>
@endsection
