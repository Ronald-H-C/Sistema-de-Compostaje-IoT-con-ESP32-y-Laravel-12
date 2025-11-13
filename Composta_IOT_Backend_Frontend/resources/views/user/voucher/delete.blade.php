@extends('user.dashboard')

@section('content')
<div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">🛑 Gestión de Comprobantes Rechazados</h1>

        <div class="flex gap-2">
            <a href="{{ route('deployC') }}" 
               class="text-sm bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Volver
            </a>
        </div>
    </div>

    @if ($vouchers->count())
    <div class="overflow-x-auto bg-white rounded-lg shadow-lg">
        <table class="min-w-full table-auto text-sm text-center border">
            <thead class="bg-red-600 text-white">
                <tr>
                    <th class="p-2">Nro Venta</th>
                    <th class="p-2">Cliente</th>
                    <th class="p-2">Producto</th>
                    <th class="p-2">Descripción</th>
                    <th class="p-2">Tipo</th>
                    <th class="p-2">Precio Unitario</th>
                    <th class="p-2">Cantidad</th>
                    <th class="p-2">Subtotal</th>
                    <th class="p-2">Total Venta</th>
                    <th class="p-2">Imagen (Voucher)</th>
                    <th class="p-2">Estado</th>
                    <th class="p-2">Fecha de Envío</th>
                    <th class="p-2">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                
                {{-- Bucle 1: Itera sobre cada VENTA (Voucher) --}}
                @forelse ($vouchers as $sale)

                    {{-- Bucle 2: Itera sobre cada PRODUCTO dentro de esa VENTA --}}
                    @foreach ($sale->products as $index => $item)
                    <tr class="hover:bg-gray-50">

                        {{-- Estas celdas solo se dibujan en la PRIMERA fila de productos --}}
                        @if($index === 0)
                            <td class="p-2 font-semibold" rowspan="{{ $sale->products->count() }}">
                                {{-- Esto calcula el número correlativo correcto en todas las páginas --}}
                                {{ $vouchers->firstItem() + $loop->parent->iteration - 1 }}
                            </td>
                            <td class="p-2" rowspan="{{ $sale->products->count() }}">{{ $sale->client->name ?? 'N/A' }}</td>
                        @endif

                        {{-- Estas celdas se dibujan para CADA producto --}}
                        <td class="p-2 text-left">{{ $item->fertilizer->title ?? 'N/A' }}</td>
                        <td class="p-2 text-left">{{ $item->fertilizer->description ?? 'N/A' }}</td>
                        <td class="p-2">{{ $item->fertilizer->type ?? 'N/A' }}</td>
                        <td class="p-2 text-right">${{ number_format($item->price, 2) }}</td>
                        <td class="p-2 text-right">{{ $item->amount }}</td>
                        <td class="p-2 text-right">${{ number_format($item->subtotal, 2) }}</td>
                        

                        {{-- Estas celdas también se dibujan solo en la PRIMERA fila --}}
                        @if($index === 0)
                            <td class="p-2 text-right font-bold" rowspan="{{ $sale->products->count() }}">${{ number_format($sale->total, 2) }}</td>
                            <td class="p-2" rowspan="{{ $sale->products->count() }}">
                                @if ($sale->image)
                                    <a href="{{ asset($sale->image) }}" target="_blank" class="text-red-600 underline">
                                        Ver imagen
                                    </a>
                                @else
                                    Sin imagen
                                @endif
                            </td>
                            <td class="p-2" rowspan="{{ $sale->products->count() }}">
                                {{-- Asumiendo 0=Pendiente, 1=Aprobado, 2=Rechazado --}}
                                @switch($sale->state)
                                    @case(0)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-200 text-yellow-800">Pendiente</span>
                                        @break
                                    @case(1)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-200 text-green-800">Aprobado</span>
                                        @break
                                    @case(2)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-200 text-red-800">Rechazado</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="p-2" rowspan="{{ $sale->products->count() }}">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-2" rowspan="{{ $sale->products->count() }}">
                                
                                <a href="{{ route('editVoucher', $sale->id) }}" 
                                class="px-2 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded text-xs">
                                Actualizar
                                </a>
                                <a href="{{ route('deleteVoucher', $sale->id) }}" 
                                class="px-2 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded text-xs **ml-2**">
                                Eliminar
                                </a>
                            </td>
                        @endif
                    </tr>
                    @endforeach
                @empty
                <tr>
                    {{-- El colspan debe coincidir con el número de <th> --}}
                    <td colspan="14" class="text-center py-4 text-gray-600">No hay comprobantes disponibles.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 bg-gray-50 border-t">
            {{ $vouchers->links() }}
        </div>
    </div>

    @else
        <p class="text-center text-gray-600 mt-8">No hay comprobantes disponibles.</p>
    @endif
</div>
@endsection