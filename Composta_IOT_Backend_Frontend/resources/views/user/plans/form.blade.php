@extends('user.dashboard')

@section('content')
<div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen"> {{-- Padding aumentado --}}

    {{-- Alertas (Añadidas por consistencia) --}}
    @if (session('success'))
        <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-300 text-green-800 font-medium shadow-sm flex items-center">
            <i class="fas fa-check-circle mr-2 text-green-600"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-300 text-red-800 font-medium shadow-sm flex items-center">
            <i class="fas fa-exclamation-triangle mr-2 text-red-600"></i> {{ session('error') }}
        </div>
    @endif
    
    {{-- Card principal --}}
    <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-200">
        <h2 class="text-3xl font-bold text-center text-green-700 mb-8 uppercase tracking-wide">
            Compra del Plan
        </h2>

        {{-- Detalles del Plan --}}
        <div class="text-center mb-8 pb-8 border-b border-gray-200">
            <p class="text-sm text-gray-500 mb-1">Plan seleccionado:</p>
            <p class="text-2xl font-semibold text-gray-800">{{ $plan->name }}</p>
            <p class="text-sm text-gray-600 mt-2 space-x-2">
                <strong>Duración:</strong> <span class="font-medium">{{ $plan->duration }} días</span> |
                <strong>Publicaciones:</strong> <span class="font-medium">{{ $plan->post_limit ?? 'Ilimitadas' }}</span> |
                <strong>Precio:</strong> 
                {{-- CAMBIO: Moneda a Bs --}}
                <span class="font-semibold text-green-700">
                    {{ $plan->cost > 0 ? 'Bs ' . number_format($plan->cost, 2) : 'Gratis' }}
                </span>
            </p>
        </div>

        {{-- Instrucciones y QR --}}
        <div class="mb-8 text-center bg-gray-50 p-6 rounded-xl border border-gray-200">
            <p class="text-base text-gray-700 mb-4">
                Para completar la compra, escanea el siguiente código QR y realiza el pago.
            </p>
            <p class="text-lg font-semibold text-gray-800 mb-4">
                Método de Pago: <span class="text-green-600 font-bold">Código QR</span>
            </p>
            @if ($usercodeqr->reference && $usercodeqr->reference->qr_image)
                 {{-- Estilo refinado para la imagen QR --}}
                <img src="{{ asset($usercodeqr->reference->qr_image) }}" 
                     alt="Código QR de Pago"
                     class="mx-auto w-60 h-60 object-contain border border-gray-300 rounded-lg p-1 bg-white shadow-sm">
            @else
                {{-- Mensaje de error estilizado --}}
                <div class="p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 font-medium text-center">
                    ⚠️ No has configurado tu código QR de pago en tu perfil.
                </div>
            @endif
        </div>

        {{-- Formulario de Carga --}}
        <form action="{{ route('planes.pago', $plan->id) }}" method="POST" enctype="multipart/form-data"
              class="bg-gray-50 p-6 rounded-xl shadow-inner border border-dashed border-gray-300">
            @csrf

            <div class="mb-5">
                <label for="receipt" class="block mb-2 text-sm font-medium text-gray-800">
                    📎 Subir comprobante de pago *
                </label>
                {{-- Input de archivo estilizado --}}
                <input type="file" name="receipt" id="receipt" accept="image/*" required
                       class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg shadow-sm cursor-pointer focus:outline-none bg-white
                              file:py-2.5 file:px-4 file:mr-4 file:border-0
                              file:bg-green-600 file:text-white file:font-semibold hover:file:bg-green-700 transition">
                <p class="text-xs text-gray-500 mt-2">Formatos aceptados: JPG, PNG. Tamaño máximo: 2MB</p>
            </div>

            <div class="mt-6">
                {{-- Botón de envío estilizado --}}
                <button type="submit"
                        class="w-full inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium shadow-md transition-all duration-200 ease-in-out">
                     <i class="fas fa-paper-plane mr-2"></i> Enviar Comprobante de Pago
                </button>
            </div>
        </form>

        <div class="mt-8 text-center text-sm text-gray-600 leading-relaxed">
            Una vez enviado el comprobante, tu compra será verificada manualmente.<br>
            Recibirás una notificación cuando tu plan sea activado.
        </div>
    </div>
</div>
@endsection