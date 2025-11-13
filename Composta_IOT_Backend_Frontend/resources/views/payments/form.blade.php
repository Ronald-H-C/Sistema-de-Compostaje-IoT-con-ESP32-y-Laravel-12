@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 max-w-2xl pt-28 pb-24">
        <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-200">
            <h2 class="text-3xl font-bold text-center text-primary mb-6 uppercase tracking-wide">
                Confirmación de Pago
            </h2>

            {{-- INICIO: Visualizador de Mensajes de Sesión --}}
            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-800 bg-green-100 rounded-lg shadow border border-green-200" role="alert">
                    <span class="font-medium">¡Éxito!</span> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 text-sm text-red-800 bg-red-100 rounded-lg shadow border border-red-200" role="alert">
                    <span class="font-medium">¡Error!</span> {{ session('error') }}
                </div>
            @endif
            @if (session('info')) {{-- Opcional: para mensajes informativos --}}
                <div class="mb-4 p-4 text-sm text-blue-800 bg-blue-100 rounded-lg shadow border border-blue-200" role="alert">
                    <span class="font-medium">Info:</span> {{ session('info') }}
                </div>
            @endif
            @if (session('warning')) {{-- Opcional: para advertencias --}}
                <div class="mb-4 p-4 text-sm text-yellow-800 bg-yellow-100 rounded-lg shadow border border-yellow-200" role="alert">
                    <span class="font-medium">Atención:</span> {{ session('warning') }}
                </div>
            @endif
            {{-- FIN: Visualizador de Mensajes --}}


            {{-- INICIO: Sección de Resumen del Pedido (desde la sesión) --}}
            <div class="mb-6 bg-gray-50 p-6 rounded-xl shadow-inner border border-dashed border-gray-300">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Resumen de tu Pedido</h3>
                
                @forelse($cartItems as $id => $item)
                <div class="flex justify-between items-center py-2 border-b last:border-b-0">
                    <div>
                        <p class="font-semibold text-gray-700">{{ $item['title'] ?? 'Producto no disponible' }}</p>
                        <p class="text-sm text-gray-500">
                            {{ $item['quantity'] }} x Bs {{ number_format($item['price'], 2) }} {{-- Cambiado a Bs --}}
                        </p>
                    </div>
                    <p class="font-semibold text-gray-800">Bs {{ number_format($item['price'] * $item['quantity'], 2) }}</p> {{-- Cambiado a Bs --}}
                </div>
                @empty
                 <p class="text-center text-gray-500">No hay productos en tu carrito.</p>
                @endforelse

                <div class="flex justify-between items-center pt-4 mt-4 border-t-2 border-primary">
                    <p class="text-xl font-bold text-gray-900">TOTAL A PAGAR:</p>
                    <p class="text-2xl font-bold text-primary">Bs {{ number_format($total, 2) }}</p> {{-- Cambiado a Bs --}}
                </div>
            </div>
            {{-- FIN: Sección de Resumen del Pedido --}}

            {{-- ... (El resto de tu código: QR, Formulario, Botones) ... --}}
            <div class="mb-8 text-center">
                 <p class="text-base text-gray-700 mb-2">
                     Para completar tu compra, escanea el siguiente código QR del vendedor
                     (<span class="font-medium">{{ $seller->name ?? 'Vendedor' }}</span>)
                     y realiza el pago exacto de <strong class="text-lg">Bs {{ number_format($total, 2) }}</strong>. {{-- Cambiado a Bs --}}
                 </p>
                 <p class="text-lg font-semibold text-gray-800 mb-3">
                     Método de Pago: <span class="text-green-600">Código QR</span>
                 </p>
                 
                 @if ($seller && $seller->reference && $seller->reference->qr_image)
                     <img src="{{ asset($seller->reference->qr_image) }}" alt="Código QR de Pago"
                          class="mx-auto w-64 h-64 object-contain border-2 border-gray-300 rounded-xl p-2 bg-white shadow-md">
                 @else
                     <div class="text-red-500 font-semibold text-center mb-4">
                         ⚠️ Este vendedor aún no ha subido su código QR de pago. Contacta con él.
                     </div>
                 @endif
             </div>

             
             {{-- Formulario para subir el comprobante y CREAR la venta --}}
             <form action="{{ route('payment.process') }}" method="POST" enctype="multipart/form-data">
                 @csrf
                 
                 <input type="hidden" name="idClient" value="{{ auth()->id() }}">
                 <input type="hidden" name="idUser" value="{{ $seller->id }}">
                 <input type="hidden" name="pay" value="qr">

                 <div class="mb-4">
                     <label for="receipt" class="block mb-2 text-sm font-medium text-gray-700">
                         📎 Sube aquí tu comprobante de pago
                     </label>
                     <input type="file" name="receipt" accept="image/*" required
                            class="block w-full text-sm text-gray-800 border border-gray-300 rounded-lg cursor-pointer bg-white file:bg-primary file:text-white file:py-2 file:px-4 file:rounded-lg file:text-sm file:font-semibold hover:file:bg-secondary transition @error('receipt') border-red-500 @enderror">
                     <p class="text-xs text-gray-500 mt-1">Archivos permitidos: JPG, PNG | Tamaño máximo: 2MB</p>
                     @error('receipt')
                         <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                     @enderror
                 </div>

                 <div class="mt-6">
                     <button type="submit"
                             @if (!($seller && $seller->reference && $seller->reference->qr_image)) disabled @endif
                             class="w-full bg-primary hover:bg-secondary text-white font-semibold py-3 rounded-lg transition duration-300 ease-in-out shadow-md hover:shadow-lg disabled:bg-gray-400 disabled:cursor-not-allowed">
                         Enviar Comprobante y Finalizar Compra
                     </button>
                 </div>
             </form>

             <div class="text-center mt-6">
                 {{-- Botón para cancelar y vaciar el carrito --}}
                 <form action="{{ route('cart.cancel') }}" method="POST" class="inline-block mr-4">
                     @csrf
                     {{-- Corregido: El input hidden debe tener 'value', no contenido --}}
                     <input name="idUser" type="hidden" value="{{ $seller->id }}">
                     <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-6 rounded-lg transition-all shadow-md">
                        Cancelar Pedido
                     </button>
                 </form>
                 {{-- Botón para volver a la tienda --}}
                 {{-- Asegúrate que 'index' sea la ruta correcta a tu listado de productos --}}
                 <a href="{{ route('index') }}" 
                    class="inline-block bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-lg transition-all shadow-md">
                     Volver al inicio
                 </a>
             </div>

             <div class="mt-6 text-center text-sm text-gray-600">
                 Al enviar el comprobante, se registrará tu pedido.<br>
                 El vendedor verificará tu pago manualmente.
             </div>

        </div>
    </div>
@endsection