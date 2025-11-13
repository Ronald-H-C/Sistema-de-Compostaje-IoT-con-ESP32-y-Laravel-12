@extends('user.dashboard')

@section('content')
<div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen">

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-3xl font-bold text-gray-800">💳 Gestión de Comprobantes de Pago</h1>
        <div class="flex gap-2">
            <a href="{{ route('deleteC') }}" 
               class="flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg shadow-sm text-gray-700 font-medium transition">
                <i class="fas fa-trash-restore-alt"></i> Comprobantes Rechazados
            </a>
        </div>
    </div>

    @if($vouchers->count())
    <div class="overflow-x-auto bg-white rounded-lg shadow-lg">
    <table class="min-w-full table-auto text-sm divide-y divide-gray-200">
            <thead class="bg-green-600 text-white">
                <tr>
                    <th class="p-2 text-left">#</th>
                    <th class="p-2 text-left">Cliente</th>
                    <th class="p-2 text-left">Producto</th>
                    <th class="p-2 text-left">Descripción</th>
                    <th class="p-2 text-center">Tipo</th>
                    <th class="p-2 text-right">Precio Unit.</th>
                    <th class="p-2 text-right">Cantidad</th>
                    <th class="p-2 text-right">Subtotal</th>
                    <th class="p-2 text-right">Total Venta</th>
                    <th class="p-2 text-center">Imagen</th>
                    <th class="p-2 text-center">Estado</th>
                    <th class="p-2 text-left">Fecha de Envío</th>
                    <th class="p-2 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                
                {{-- BUCLE EXTERIOR: Itera sobre las VENTAS (ej. $vouchers es una colección de 'Sale') --}}
                @forelse($vouchers as $sale)

                    {{-- BUCLE INTERIOR: Itera sobre los PRODUCTOS de CADA VENTA --}}
                    @foreach ($sale->products as $index => $item)
                    <tr class="hover:bg-green-50 transition">
                        
                        {{-- Columnas de la VENTA (se muestran solo en la primera fila) --}}
                        @if($index === 0)
                            <td class="p-2 font-semibold" rowspan="{{ $sale->products->count() }}">
                                {{-- ESTA ES LA LÓGICA CORRELATIVA QUE PEDISTE --}}
                                {{ $vouchers->firstItem() + $loop->parent->iteration - 1 }}
                            </td>
                            <td class="p-2" rowspan="{{ $sale->products->count() }}">{{ $sale->client->name ?? 'N/A' }} {{ $sale->client->firstLastName ?? '' }}</td>
                        @endif

                        {{-- Columnas del PRODUCTO (se muestran en cada fila) --}}
                        <td class="p-2 text-left">{{ $item->fertilizer->title ?? 'N/A' }}</td>
                        <td class="p-2 text-left">{{ $item->fertilizer->description ?? 'N/A' }}</td>
                        <td class="p-2 text-center">{{ $item->fertilizer->type ?? 'N/A' }}</td>
                        <td class="p-2 text-right">Bs. {{ number_format($item->price, 2) }}</td>
                        <td class="p-2 text-right">{{ $item->amount }}</td>
                        <td class="p-2 text-right">Bs. {{ number_format($item->subtotal, 2) }}</td>
                        
                        {{-- Columnas de la VENTA (se muestran solo en la primera fila) --}}
                        @if($index === 0)
                            <td class="p-2 text-right font-bold" rowspan="{{ $sale->products->count() }}">Bs. {{ number_format($sale->total, 2) }}</td>
                            <td class="p-2 text-center" rowspan="{{ $sale->products->count() }}">
                                @if ($sale->image)
                                    <a href="{{ asset($sale->image) }}" target="_blank" class="text-red-600 hover:underline">Ver imagen</a>
                                @else
                                    <span class="text-gray-400">Sin imagen</span>
                                @endif
                            </td>
                            <td class="p-2 text-center" rowspan="{{ $sale->products->count() }}">
                                {{-- Lógica de estados corregida (0=Pendiente, 1=Aprobado, 2=Rechazado) --}}
                                @switch($sale->state)
                                    @case(0)
                                        <span class="px-2 py-1 rounded-full bg-yellow-200 text-yellow-800 text-sm font-medium">Pendiente</span>
                                        @break
                                    @case(1)
                                        <span class="px-2 py-1 rounded-full bg-green-200 text-green-800 text-sm font-medium">Aprobado</span>
                                        @break
                                    @case(2)
                                        <span class="px-2 py-1 rounded-full bg-red-200 text-red-800 text-sm font-medium">Rechazado</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="p-2" rowspan="{{ $sale->products->count() }}">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-2 text-center" rowspan="{{ $sale->products->count() }}">
                                <a href="{{ route('editVoucher', $sale->id) }}" 
                                   class="inline-flex items-center gap-2 px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white rounded-lg text-sm transition">
                                    <i class="fas fa-edit"></i> Actualizar
                                </a>
                            </td>
                        @endif
                    </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="14" class="text-center py-4 text-gray-500">No hay comprobantes disponibles.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $vouchers->links() }}
    </div>
    @else
        <p class="text-center text-gray-500 mt-10 text-lg">No hay comprobantes disponibles.</p>
    @endif

</div>
@endsection