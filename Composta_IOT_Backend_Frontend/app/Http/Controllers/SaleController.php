<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use App\Models\Fertilizer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
{
    // Obtiene el ID del usuario autenticado
    $userId = auth()->id(); 

    $sales = Sale::with([
        'client',             // Carga la relación client()
        'user',               // Carga la relación user()
        'products.fertilizer' // <-- ¡CORREGIDO! Carga 'products' y anida 'fertilizer'
    ])
    ->where('idUser', $userId)
    ->where('state', 1) // Filtra las ventas por el usuario que las registró
    ->latest()                 // Ordena por 'created_at' descendente (más nuevas primero)
    ->get();

    return view('user.sales.index', compact('sales'));
}

    public function create()
    {
        // Obtener usuarios con rol de cliente
        $clients = User::where('role', 'client')->get();
        return view('sales.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $sale = Sale::create([
            'idUser' => auth()->id(),
            'idClient' => $request->idClient,
            'pay' => $request->pay,
            'total' => $request->total,
            'state' => 1,
            'updated_by' => auth()->id(),
        ]);

        // Aquí podrías guardar detalles si los recibes

        return redirect()->route('sales.index')->with('success', 'Venta registrada correctamente');
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Venta eliminada');
    }
/////////////////////////////////////////
    public function add(Request $request)
    {
        // Validamos que 'products' sea un array
        $request->validate([
            'products' => 'required|array',
        ]);

        $productsToAdd = $request->products;
        $cart = session()->get('cart', []);
        $addedCount = 0;

        foreach ($productsToAdd as $productId => $data) {
            
            // 1. Revisamos si el checkbox fue marcado
            if (isset($data['selected'])) {
                
                $product = Fertilizer::find($productId);
                $quantity = (int) $data['quantity'];

                // 2. Doble validación de stock
                if ($product->stock < $quantity) {
                    return redirect()->back()->with('error', 'No hay suficiente stock para ' . $product->title);
                }

                // 3. Añadir o actualizar en el carrito
                if (isset($cart[$productId])) {
                    $cart[$productId]['quantity'] += $quantity;
                } else {
                    $cart[$productId] = [
                        "id" => $product->id,
                        "title" => $product->title,
                        "quantity" => $quantity,
                        "price" => $product->price,
                        "image" => $product->image
                    ];
                }
                $addedCount++;
            }
        }

        // Si no seleccionó nada
        if ($addedCount === 0) {
            return redirect()->back()->with('error', 'No seleccionaste ningún producto para añadir.');
        }

        session()->put('cart', $cart);
        $idUser = $request->idUser;
        // ¡Este es el mensaje que querías!
        $successMessage = "¡Se añadieron {$addedCount} productos al carrito!";

        return view('products.index')
            ->with('idUser', $idUser)
            ->with('success', $successMessage);
            
    }




    public function index1(Request $request)
    {
        $cartItems = session()->get('cart', []);
        $idUser = $request->id;

        return view('products.index', compact('cartItems', 'idUser'));
        
    }

    /**
     * Elimina un producto del carrito.
     */
    public function remove(Request $request)
    {
        $request->validate(['id' => 'required|exists:fertilizers,id']);

        $cart = session()->get('cart', []);
        $idUser = $request->idUser;

        

        if (isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
        }
        

        return redirect()->route('cart.index', ['id' => $idUser])->with('success', 'Producto eliminado del carrito.');
    }

    public function cancel(Request $request)
    {
        // 1. Olvidamos (eliminamos) la variable 'cart' completa de la sesión.
        $request->session()->forget('cart');
        $idUser = $request->idUser;
        // 2. Redirigimos de vuelta al índice del carrito (que ahora estará vacío).
        return redirect()->route('products.userProducts', ['id' => $idUser])
                 ->with('success', 'El carrito ha sido vaciado.');
    }
}
