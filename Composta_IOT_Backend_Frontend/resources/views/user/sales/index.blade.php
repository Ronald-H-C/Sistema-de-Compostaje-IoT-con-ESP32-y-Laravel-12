@extends('user.dashboard')

@section('content')
<div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen">
    
    <h1 class="text-3xl font-extrabold text-center mb-8 text-gray-800 tracking-tight">
        Historial de  <span class="text-green-600">Ventas</span>
    </h1>

    @if (session('success'))
        <div class="mb-6 p-4 text-green-800 bg-green-100 border border-green-300 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg shadow-lg bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-green-600">
                <tr>
                    {{-- Nueva columna para la numeración --}}
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Producto</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider">Cantidad</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider">Precio Unitario</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider">Subtotal</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider">Total Venta</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider">Pago</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider">Fecha</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                
                
                @php $saleNumber = 1; @endphp

                
                @foreach($sales as $sale)
                    
                    @php
                        $rowClass = $loop->even ? 'bg-stone-100' : 'bg-white';
                    @endphp

                    
                    @foreach($sale->products as $index => $item)
                        {{-- 
                          MODIFICACIÓN:
                          Aplicamos la variable $rowClass al <tr>
                        --}}
                        <tr class="{{ $rowClass }} hover:bg-indigo-100 transition-colors duration-150">
                            
                            
                            @if($index === 0)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-bold" rowspan="{{ $sale->products->count() }}">
                                    {{ $saleNumber++ }}
                                </td>
                            @endif

                            {{-- Solo mostramos estos datos en la PRIMERA fila de la venta --}}
                            @if($index === 0)
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900" rowspan="{{ $sale->products->count() }}">
                                    {{ $sale->client->name }} {{ $sale->client->firstLastName ?? '' }} {{ $sale->client->secondLastName ?? '' }}
                                </td>
                            @endif

                            {{-- Estos datos se muestran en CADA fila de producto --}}
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $item->fertilizer->title ?? 'Producto no disponible' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-gray-700 font-semibold">{{ $item->amount }}</td>
                            
                            {{-- Es mejor usar el precio guardado que recalcularlo --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-gray-600">Bs. {{ number_format((float)$item->price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-gray-600">Bs. {{ number_format((float)$item->subtotal, 2) }}</td>

                            {{-- Solo mostramos estos datos en la PRIMERA fila de la venta --}}
                            @if($index === 0)
                                <td class="px-6 py-4 whitespace-nowrap text-right font-semibold text-green-500" rowspan="{{ $sale->products->count() }}">
                                    Bs. {{ number_format((float) $sale->total, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-gray-700" rowspan="{{ $sale->products->count() }}">
                                    {{ $sale->pay }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-gray-500 text-sm" rowspan="{{ $sale->products->count() }}">
                                    {{ \Carbon\Carbon::parse($sale->created_at)->format('d/m/Y H:i') }}
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection