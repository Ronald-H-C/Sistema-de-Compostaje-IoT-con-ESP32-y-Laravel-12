@extends('layouts.app') {{-- Usa tu layout principal --}}

@section('content')
<div class="container mx-auto px-4 pt-24 pb-24 max-w-3xl">
    <h1 class="text-3xl font-bold text-center mb-6">Tu Carrito de Compras</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-4 rounded mb-4">{{ session('error') }}</div>
    @endif

    @php $total = 0; @endphp

    @if(session('cart') && count(session('cart')) > 0)
        <div class="bg-white shadow-lg rounded-xl border p-6">
            @foreach(session('cart') as $id => $item)
                @php $total += $item['price'] * $item['quantity']; @endphp
                <div class="flex items-center justify-between py-4 border-b">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset($item['image']) }}" alt="{{ $item['title'] }}" class="w-16 h-16 object-cover rounded">
                        <div>
                            <p class="font-semibold">{{ $item['title'] }}</p>
                            <p class="text-sm text-gray-600">Cantidad: {{ $item['quantity'] }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold">Bs {{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                        <form action="{{ route('cart.remove') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $id }}">
                            <input type="hidden" name="idUser" value="{{ $idUser }}">
                            <button type="submit" class="text-red-500 hover:underline text-xs">Eliminar</button>
                        </form>
                    </div>
                </div>
            @endforeach

            <div class="mt-6 text-right">
                <p class="text-2xl font-bold">Total: <span class="text-primary">Bs {{ number_format($total, 2) }}</span></p>
            </div>

            <div class="mt-8">
                
                <form action="{{ route('show.Form') }}" method="POST">
                    @csrf
                    <input type="hidden" name="idClient" value="{{ auth()->id() }}">
                    <input type="hidden" name="idUser" value="{{ $idUser }}">
                    <input type="hidden" name="pay" value="qr"> {{-- O el método de pago --}}

                    <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-bold py-3 px-6 rounded-lg">
                        Confirmar y Proceder al Pago (QR)
                    </button>

                </form>
                <br>
                <form action="{{ route('cart.cancel') }}" method="POST">
                    @csrf
                <input type="hidden" name="idClient" value="{{ auth()->id() }}">
                <input type="hidden" name="idUser" value="{{ $idUser }}">
                <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-bold py-3 px-6 rounded-lg">
                    Cancelar
                </button>
                </form>
            </div>
        </div>
    @else
        <p class="text-center text-gray-600">Tu carrito está vacío.</p>
         <div class="mt-8">
           <form action="{{ route('products.userProducts', ['id' => $idUser ]) }}" method="GET">
                    @csrf
                <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-bold py-3 px-6 rounded-lg">
                    Volver
                </button>
                </form>
        </div>
    @endif
       
</div>
@endsection