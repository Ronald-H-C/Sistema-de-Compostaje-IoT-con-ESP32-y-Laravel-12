@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 pt-24 pb-24">

    {{-- INICIO: Bloque para mostrar mensajes de la sesión --}}
    @if (session('success'))
        <div class="mb-6 p-4 text-green-800 bg-green-100 border border-green-300 rounded shadow">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 p-4 text-red-800 bg-red-100 border border-red-300 rounded shadow">
            {{ session('error') }}
        </div>
    @endif
    {{-- FIN: Bloque de mensajes --}}

    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Productos de {{ $usuario->name }}</h2>

    {{-- INICIO DE LA MODIFICACIÓN: Un solo formulario para todo --}}
    <form action="{{ route('cart.add') }}" method="POST">
        @csrf
        <input name="idUser" type="hidden" value="{{ $usuario->id }}">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @forelse ($productos as $producto)
                <div class="card p-4 bg-white rounded-2xl shadow-lg h-full flex flex-col justify-between transition-transform hover:scale-105">
                    
                    <!-- Imagen (sin cambios) -->
                    <div class="w-full h-[220px] flex justify-center items-center border-2 border-gray-200 rounded-xl overflow-hidden bg-gray-50">
                        <img src="{{ asset($producto->image) }}" alt="{{ $producto->title }}"
                            class="object-contain h-full max-w-full">
                    </div>

                    <!-- Detalles (sin cambios) -->
                    <div class="mt-4 text-center flex-grow">
                        <h5 class="text-xl font-semibold text-gray-800 mb-1">{{ $producto->title }}</h5>
                        <p class="text-sm text-gray-600 capitalize mb-2">{{ str_replace('_', ' ', $producto->type) }}</p>
                        <p class="text-lg font-bold text-green-600 mb-1">Kg: {{ $producto->amount }}</p>
                        <p class="text-lg font-bold text-green-600 mb-1">Bs {{ $producto->price }}</p>
                        <p class="text-sm text-gray-500 mb-2">
                            <i class="fas fa-map-marker-alt mr-1 text-red-500"></i>
                            {{ $producto->location->address ?? 'Ubicación no disponible' }}
                        </p>
                    </div>

                    <!-- INICIO: Input de Cantidad y Checkbox -->
                    <div class="mt-4 space-y-3">
                        <p class="text-sm text-gray-500 text-center">
                            Stock disponible: <span class="font-medium text-gray-700">{{ $producto->stock ?? 0 }}</span>
                        </p>
                        
                        <div class="flex items-center justify-center space-x-2 quantity-control" data-stock="{{ $producto->stock ?? 0 }}">
                            <label for="quantity-{{ $producto->id }}" class="text-sm font-medium text-gray-700">Cantidad:</label>
                            <input type="number" 
                                   name="products[{{ $producto->id }}][quantity]"
                                   id="quantity-{{ $producto->id }}"
                                   value="1"
                                   min="1"
                                   max="{{ $producto->stock ?? 0 }}"
                                   class="quantity-input w-20 text-center border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-primary disabled:bg-gray-100 disabled:cursor-not-allowed"
                                   {{-- Deshabilitado si no hay stock --}}
                                   @if($producto->stock <= 0) disabled @endif 
                                   >
                        </div>
                        
                        <div class="flex items-center justify-center p-2 rounded-lg bg-gray-50 border">
                            <input type="checkbox" 
                                   name="products[{{ $producto->id }}][selected]" 
                                   id="check-{{ $producto->id }}"
                                   value="{{ $producto->id }}"
                                   class="product-checkbox w-5 h-5 text-primary rounded border-gray-300 focus:ring-primary disabled:bg-gray-300 disabled:cursor-not-allowed"
                                   {{-- Deshabilitado si no hay stock --}}
                                   @if($producto->stock <= 0) disabled @endif
                                   >
                            <label for="check-{{ $producto->id }}" class="ml-2 text-sm font-medium text-gray-800">
                                @if($producto->stock <= 0)
                                    Agotado
                                @else
                                    Seleccionar para añadir al carrito
                                @endif
                            </label>
                        </div>
                    </div>
                    <!-- FIN: Input de Cantidad y Checkbox -->
                </div>
            @empty
                <p class="text-center col-span-3 text-gray-600">Este usuario no tiene productos disponibles.</p>
            @endforelse
        </div>

        <div class="text-center mt-6 p-4 sticky bottom-4">
            <button type="submit"
               class="inline-block bg-green-600 hover:bg-green-700 text-white text-lg font-bold py-3 px-8 rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                <i class="fas fa-cart-plus mr-2"></i>
                Añadir Productos Seleccionados al Carrito
            </button>
        </div>

    </form>
    


    {{-- Paginación --}}
    <div class="mt-8 flex justify-center">
        {{ $productos->links() }}
    </div>

    
      
        @if ($usuario->reference)
            <div class="bg-white rounded-xl shadow-lg p-6 mt-12 text-center border border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Información de Contacto</h3>

                @if ($usuario->reference->phone)
                    <p class="text-gray-700 mb-2">
                        <i class="fas fa-phone text-green-600 mr-2"></i>{{ $usuario->reference->phone }}
                    </p>
                @endif

                @if ($usuario->reference->contact_email)
                    <p class="text-gray-700 mb-2">
                        <i class="fas fa-envelope text-blue-600 mr-2"></i>{{ $usuario->reference->contact_email }}
                    </p>
                @endif

                <div class="flex justify-center gap-4 mt-4 text-2xl">
                    @if ($usuario->reference->whatsapp_link)
                        <a href="{{ $usuario->reference->whatsapp_link }}" target="_blank"
                            class="text-green-600 hover:text-green-800">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    @endif
                    @if ($usuario->reference->facebook_link)
                        <a href="{{ $usuario->reference->facebook_link }}" target="_blank"
                            class="text-blue-700 hover:text-blue-900">
                            <i class="fab fa-facebook"></i>
                        </a>
                    @endif
                    @if ($usuario->reference->instagram_link)
                        <a href="{{ $usuario->reference->instagram_link }}" target="_blank"
                            class="text-pink-600 hover:text-pink-800">
                            <i class="fab fa-instagram"></i>
                        </a>
                    @endif
                    @if ($usuario->reference->youtube_link)
                        <a href="{{ $usuario->reference->youtube_link }}" target="_blank"
                            class="text-red-600 hover:text-red-800">
                            <i class="fab fa-youtube"></i>
                        </a>
                    @endif
                    @if ($usuario->reference->tiktok_link)
                        <a href="{{ $usuario->reference->tiktok_link }}" target="_blank"
                            class="text-black hover:text-gray-700">
                            <i class="fab fa-tiktok"></i>
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- Botón flotante de WhatsApp si existe --}}
    @if ($usuario->reference && $usuario->reference->whatsapp_link)
        <a href="{{ $usuario->reference->whatsapp_link }}"
            class="fixed bottom-5 right-5 w-14 h-14 bg-green-500 text-white rounded-full shadow-lg flex items-center justify-center text-2xl hover:bg-green-600 transition"
            target="_blank" title="WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
    @endif

    {{-- Iconos flotantes a la izquierda --}}
    @if ($usuario->reference)
        <div class="fixed top-1/3 left-0 z-50 flex flex-col items-center space-y-2 pl-1">
            @if ($usuario->reference->whatsapp_link)
                <a href="{{ $usuario->reference->whatsapp_link }}" target="_blank"
                    class="bg-green-500 text-white w-10 h-10 flex items-center justify-center rounded-r hover:bg-green-600 transition"
                    title="WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                </a>
            @endif
            @if ($usuario->reference->instagram_link)
                <a href="{{ $usuario->reference->instagram_link }}" target="_blank"
                    class="bg-pink-500 text-white w-10 h-10 flex items-center justify-center rounded-r hover:bg-pink-600 transition"
                    title="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
            @endif
            @if ($usuario->reference->youtube_link)
                <a href="{{ $usuario->reference->youtube_link }}" target="_blank"
                    class="bg-red-600 text-white w-10 h-10 flex items-center justify-center rounded-r hover:bg-red-700 transition"
                    title="YouTube">
                    <i class="fab fa-youtube"></i>
                </a>
            @endif
            @if ($usuario->reference->facebook_link)
                <a href="{{ $usuario->reference->facebook_link }}" target="_blank"
                    class="bg-blue-600 text-white w-10 h-10 flex items-center justify-center rounded-r hover:bg-blue-700 transition"
                    title="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
            @endif
            @if ($usuario->reference->tiktok_link)
                <a href="{{ $usuario->reference->tiktok_link }}" target="_blank"
                    class="bg-black text-white w-10 h-10 flex items-center justify-center rounded-r hover:bg-gray-800 transition"
                    title="TikTok">
                    <i class="fab fa-tiktok"></i>
                </a>
            @endif
        </div>
    @endif
    
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Script para el mensaje de "éxito"
    // Buscamos si existe un mensaje de éxito
    const successAlert = document.querySelector('.bg-green-100');
    if (successAlert) {
        // Mostramos una alerta nativa del navegador
        alert(successAlert.innerText);
    }

    // 2. Script para validar el input numérico contra el stock
    const quantityControls = document.querySelectorAll('.quantity-control');

    quantityControls.forEach(control => {
        const stock = parseInt(control.dataset.stock, 10) || 0;
        const input = control.querySelector('.quantity-input');
        
        // El checkbox en la misma tarjeta
        const checkbox = control.closest('.card').querySelector('.product-checkbox');

        input.addEventListener('input', function() {
            let currentValue = parseInt(this.value, 10);
            
            if (isNaN(currentValue) || currentValue < 1) {
                this.value = 1;
            } else if (currentValue > stock) {
                this.value = stock;
                alert('No puedes seleccionar más que el stock disponible (' + stock + ').');
            }

            // Si el usuario cambia la cantidad, automáticamente marca el checkbox
            if (checkbox && !checkbox.disabled) {
                checkbox.checked = true;
            }
        });

        // Si el usuario desmarca el checkbox, resetea la cantidad a 1
        if (checkbox) {
            checkbox.addEventListener('change', function() {
                if (!this.checked) {
                    input.value = 1;
                }
            });
        }
    });
});
</script>
@endpush