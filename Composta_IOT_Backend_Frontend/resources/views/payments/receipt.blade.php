@extends('layouts.app')

@section('content')
    {{-- Toast Message (sin cambios) --}}
    @if (session('success'))
        <div id="toast"
            class="fixed top-5 right-5 z-50 px-4 py-2 rounded shadow-lg text-white bg-green-600 transition-opacity duration-500">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('toast');
                if (toast) {
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 500);
                }
            }, 4000);
        </script>
    @endif

    <div class="container mx-auto px-4 max-w-3xl pt-28 pb-24">
        <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-300">

            {{-- CAMBIO: Título y N° de Pedido (ya no es un recibo) --}}
            <div class="flex justify-between items-start border-b pb-4 mb-6">
                <div class="w-32">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-auto w-full"> {{-- Asegúrate que la ruta del logo sea correcta --}}
                </div>
                <div class="text-right">
                    <h1 class="text-2xl font-bold text-gray-800 uppercase">CONFIRMACIÓN DE COMPRA</h1>
                    <div class="text-sm text-gray-600 mt-2">
                        <p><span class="font-semibold">N° de Pedido:</span> <span class="font-mono">{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</span></p>
                        <p><span class="font-semibold">Fecha:</span> {{ $sale->created_at->format('d/m/Y') }}</p>
                        <p><span class="font-semibold">Hora:</span> {{ $sale->created_at->format('H:i:s') }}</p>
                    </div>
                </div>
            </div>

            {{-- Datos del Cliente y Vendedor (sin cambios) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="text-base font-semibold text-gray-800 border-b pb-2 mb-3">DATOS DEL CLIENTE</h3>
                    <div class="space-y-1 text-sm">
                        <p><span class="font-semibold text-gray-700">Nombre:</span> {{ $sale->client->name ?? 'N/A' }}</p>
                        <p><span class="font-semibold text-gray-700">Correo:</span> {{ $sale->client->email ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="text-base font-semibold text-gray-800 border-b pb-2 mb-3">DATOS DEL VENDEDOR</h3>
                    <div class="space-y-1 text-sm">
                        <p><span class="font-semibold text-gray-700">Nombre:</span> {{ $sale->user->name ?? 'No disponible' }}</p>
                        <p><span class="font-semibold text-gray-700">Correo:</span> {{ $sale->user->email ?? 'No disponible' }}</p>
                        @if ($sale->user->reference && $sale->user->reference->phone)
                            <p><span class="font-semibold text-gray-700">Teléfono:</span> {{ $sale->user->reference->phone }}</p>
                        @endif
                        @php $firstProductLocation = $sale->products->first()->fertilizer->location ?? null; @endphp
                        @if ($firstProductLocation)
                            <p><span class="font-semibold text-gray-700">Ubicación Aprox:</span>
                                {{ $firstProductLocation->address ?? '' }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Detalle del Pedido (sin cambios) --}}
            <div class="mb-6">
                <h3 class="text-base font-semibold text-gray-800 mb-3">DETALLE DEL PEDIDO</h3>
                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="min-w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Producto</th>
                                <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Cantidad</th>
                                <th class="px-4 py-2 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Precio Unit.</th>
                                <th class="px-4 py-2 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($sale->products as $item)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-800">{{ $item->fertilizer->title ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-center text-gray-800">{{ $item->amount }}</td>
                                <td class="px-4 py-3 text-sm text-right text-gray-800">Bs {{ number_format($item->price, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-right text-gray-800">Bs {{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Estado del Pago y Total (sin cambios) --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-6">
                <div class="w-full md:w-1/2">
                    <h3 class="text-base font-semibold text-gray-800 mb-2">ESTADO DEL PAGO</h3>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                            @switch($sale->state)
                                @case(0) bg-gray-200 text-gray-800 @break
                                @case(1) bg-yellow-100 text-yellow-800 @break {{-- Pendiente Revisión --}}
                                @case(2) bg-green-100 text-green-800 @break {{-- Aprobado --}}
                                @case(3) bg-red-100 text-red-800 @break {{-- Rechazado --}}
                                @default bg-gray-100 text-gray-800
                            @endswitch">
                        @switch($sale->state)
                            @case(0) Pendiente de Verificación @break
                            @case(1) Aprobado @break
                            @case(2) Rechazado @break
                            @default Desconocido
                        @endswitch
                    </span>
                    @if($sale->state == 1) {{-- Asumiendo 1 = Pendiente de Revisión --}}
                       <p class="text-xs text-yellow-700 mt-1">El vendedor revisará tu comprobante pronto.</p>
                    @endif
                </div>

                <div class="w-full md:w-1/2 bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div class="flex justify-between mt-3 pt-2 border-t border-gray-300">
                        <span class="font-semibold text-lg text-gray-800">TOTAL PAGADO:</span>
                        <span class="font-bold text-lg text-gray-900">Bs {{ number_format($sale->total, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Comprobante Enviado (sin cambios) --}}
            @if ($sale->image)
                <div class="mb-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-3">TU COMPROBANTE ENVIADO</h3>
                    <img src="{{ asset($sale->image) }}"
                         class="w-full max-w-md border rounded-md shadow-md"
                         alt="Comprobante de Pago Enviado">
                </div>
            @endif

            {{-- CAMBIO: Mensaje de información adicional actualizado --}}
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                <h3 class="text-base font-semibold text-gray-800 mb-3">SIGUIENTES PASOS</h3>
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                    <p class="text-sm text-blue-800">
                        <strong>Comprobante enviado.</strong> Podrá realizar la descarga de su recibo electrónico posterior a la aprobación de su comprobante.
                    </p>
                </div>
            </div>

            {{-- CAMBIO: Texto del pie de página actualizado --}}
            <div class="border-t pt-4 text-center">
                <p class="text-sm font-semibold text-gray-700 mb-1">¡GRACIAS POR SU COMPRA!</p>
                <p class="text-xs text-gray-500">compos.alwaysdata.net</p>
                <p class="text-xs text-gray-500 mt-3">
                    Documento generado automáticamente el {{ now()->format('d/m/Y H:i') }}<br>
                    Este es un resumen digital de su pedido y comprobante enviado.
                </p>
            </div>

            {{-- CAMBIO: Botón de descarga reemplazado por "Ir a Historial" --}}
            <div class="text-center mt-6 flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('index')}}" {{-- Enlace a la página principal --}}
                   class="inline-block bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-lg transition-all shadow-md">
                    Volver al inicio
                </a>
                
                {{-- Asegúrate de que 'sales.index' sea la ruta correcta para tu historial de compras --}}
                <a href="{{ route('shop') }}" 
                   class="inline-block bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 px-6 rounded-lg transition-all shadow-md">
                    Ir a Historial de Compras
                </a>
            </div>

        </div>
    </div>
@endsection