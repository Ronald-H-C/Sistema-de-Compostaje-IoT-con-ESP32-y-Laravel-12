<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Fertilizer;
use App\Models\User;
use App\Models\PaymentProduct;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; // <--- Necesario
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class PaymentController extends Controller
{
    /**
     * Show payment form for a product
     */
    public function showForm(Request $request)
    {
        $user = Auth::User();
        $userV = User::where('id', $request->idUser)->first();

        if (!$user || $user->role !== 'client') {
            return redirect()->route('registro');
        }


        $cartItems = session()->get('cart');

        // Si el carrito está vacío, redirigir
        if (!$cartItems || count($cartItems) === 0) {
            return redirect()->route('index')->with('error', 'Tu carrito está vacío.');
        }

        // Calcular el total
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Identificar al vendedor (asumiendo todos los productos son del mismo)
        // Tomamos el ID del primer producto para buscar al vendedor
        $firstItemId = array_key_first($cartItems);
        $firstProductInfo = $cartItems[$firstItemId] ?? null;
        $seller = null;
        if ($firstProductInfo) {
            // Necesitamos buscar el producto para obtener su user_id
            $productModel = \App\Models\Fertilizer::find($firstProductInfo['id']);
            if ($productModel) {
                 // Cargamos el vendedor y su referencia (para el QR)
                $seller = User::with('reference')->find($productModel->idUser);
            }
        }

        // Si no encontramos al vendedor, es un error
        if (!$seller) {
             return redirect()->route('payment.form')->with('error', 'No se pudo identificar al vendedor.');
        }

        // Pasar los datos a la vista
        return view('payments.form', [
            'cartItems' => $cartItems,
            'total' => $total,
            'seller' => $seller,
            'user' => $userV // Pasamos el objeto User completo del vendedor
        ]);

        
        return view('payments.form', compact('user'));
    }

    public function processPayment(Request $request)
    {
        
       // 1. Validar request
        $validatedData = $request->validate([
            'idClient' => 'required|exists:users,id',
            'idUser'   => 'required|exists:users,id',
            'pay'      => 'required|string',
            'receipt'  => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = User::where('id', $request->idUser)->first();

        // 2. Obtener carrito
        $cartItems = session()->get('cart');
        if (!$cartItems || count($cartItems) === 0) {
            return redirect()->route('index')->with('error', 'Tu carrito está vacío o la sesión ha expirado.');
        }

        // --- 3. Manejo de la Imagen ---
        $imagePath = null;
        if ($request->hasFile('receipt')) {
            try {
                $file = $request->file('receipt');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $destination = public_path('uploads/payment_receipts');
                if (!File::isDirectory($destination)) {
                    File::makeDirectory($destination, 0777, true, true);
                }
                $file->move($destination, $fileName);
                $imagePath = 'uploads/payment_receipts/' . $fileName;
            } catch (\Exception $e) {
                Log::error("Error al subir comprobante: " . $e->getMessage());
                return redirect()->route('payment.form')->with('error', 'Error al subir la imagen del comprobante.')
                                                            ->with('user', $user); 
            }
        } else {
             return redirect()->route('payment.form')->with('error', 'No se encontró el archivo del comprobante.')
                                                    ->with('user', $user); 
        }
        // --- Fin Manejo Imagen ---

        // 4. Iniciar transacción DB
        DB::beginTransaction();
        $sale = null;
        try {
            // 5. Calcular Total
            $totalVenta = collect($cartItems)->sum(function ($item) {
                return ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
            });

            // 6. Crear Venta
            $sale = Sale::create([
                'idClient' => $validatedData['idClient'],
                'idUser'   => $validatedData['idUser'],
                'pay'      => $validatedData['pay'],
                'total'    => $totalVenta,
                'image'    => $imagePath,
                'state'    => 0, 
                'date'     => now(),
            ]);

            // 7. Registrar Productos y Descontar Stock
            foreach ($cartItems as $id => $item) {
                $product = Fertilizer::lockForUpdate()->find($item['id']);
                if (!$product || $product->stock < $item['quantity']) {
                    throw new \Exception('Stock insuficiente para: ' . ($product->title ?? $item['title']));
                }
                PaymentProduct::create([
                    'idSale' => $sale->id, 'idFertilizer' => $item['id'],
                    'amount' => $item['quantity'], 'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity']
                ]);
                $product->decrement('stock', $item['quantity']);
            }

            // 8. ¡Confirmar Transacción DB! ✅
            DB::commit();

            // 9. Limpiar Carrito (Solo si la DB fue exitosa)
            session()->forget('cart');

            // --- 10. Enviar Email (SOLO si la transacción fue exitosa) ---
            if ($sale) {
                // Cargar relaciones necesarias para el email
                 $sale->load('client', 'products.fertilizer');
                 try {
                     $mail = new PHPMailer(true);
                     // ... (Tu configuración SMTP) ...
                     $mail->Host = 'smtp-esp32.alwaysdata.net';
                     $mail->SMTPAuth = true;
                     $mail->Username = 'esp32@alwaysdata.net';
                     $mail->Password = 'ronaldmbts123$$$'; // ¡Usar .env!
                     $mail->SMTPSecure = 'ssl';
                     $mail->Port = 465;
                     $mail->setFrom('esp32@alwaysdata.net', 'CompostajeIoT');
                     $mail->isHTML(true);
                     $mail->CharSet = 'UTF-8';

                     $toCliente = $sale->client->email;
                     $clientName = $sale->client->name ?? 'Cliente';

                     $productListHtml = "<ul>";
                     foreach ($sale->products as $item) {
                         $productName = $item->fertilizer->title ?? 'N/A';
                         $quantity = $item->amount;
                         $subtotal = number_format($item->subtotal, 2);
                         $productListHtml .= "<li>{$quantity} x {$productName} (Subtotal: Bs {$subtotal})</li>";
                     }
                     $productListHtml .= "</ul>";
                     $total = number_format($sale->total, 2);
                     $comprobanteURL = 'compos.alwaysdata.net'.'/'.($sale->image);

                     $mail->addAddress($toCliente);
                     $mail->Subject = "Hemos recibido tu comprobante - Pedido #" . $sale->id;
                     $mail->Body = "
                         <h2>Comprobante Recibido - Pedido #{$sale->id}</h2>
                         <p>Hola <strong>{$clientName}</strong>,</p>
                         <p>Hemos recibido correctamente tu comprobante de pago...</p>
                         {$productListHtml}
                         <p><strong>Total Pagado: Bs {$total}</strong></p>
                         <p>Puedes ver el comprobante <a href='{$comprobanteURL}' target='_blank'>aquí</a>.</p>
                         <p>Gracias...</p>
                     ";
                     $mail->send();

                 } catch (PHPMailerException $e) { // Capturar excepción específica de PHPMailer
                     Log::error("Error al enviar email PHPMailer para Venta  " . $mail->ErrorInfo);
                     // session()->flash('warning', '...'); // Opcional
                 } catch (\Exception $e){ // Capturar otras excepciones generales
                      Log::error("Error general al preparar email para Venta " . $e->getMessage());
                 }
             }
            // --- Fin Envío Email ---

            // --- 11. Redirección FINAL (SOLO si la transacción fue exitosa) ---
            return redirect()->route('payment.receipt', $sale) // Usamos la ruta del recibo
                             ->with('success', '¡Compra realizada con éxito! Tu comprobante ha sido enviado y será verificado pronto.');

        // --- Fin del TRY principal ---

        } catch (\Exception $e) {
            // --- CATCH para ERRORES DE BASE DE DATOS o STOCK ---
            DB::rollBack(); // Deshacer cambios en DB

            // Intentar borrar la imagen subida
            if ($imagePath && File::exists(public_path($imagePath))) {
                File::delete(public_path($imagePath));
            }


            // Devolver a la PÁGINA DE CONFIRMACIÓN
            return redirect()->route('payment.form') // <-- RUTA CORREGIDA
                             ->withInput()
                             ->with('error', 'Hubo un error al procesar tu compra: ' . $e->getMessage() . '. Por favor, inténtalo de nuevo.')
                             ->with('user', $user); 
        }
        

    } 
    

    public function mostrarRecibo(Sale $sale)
    {
        
        $sale->load(['client', 'user.reference', 'products.fertilizer.location']); 

        if ($sale->idClient !== Auth::id()) {
            abort(403, 'No tienes permiso para ver este recibo.');
        }

        return view('payments.receipt', compact('sale'));
    }


    public function descargarPDFVenta(Sale $sale) // Cambiado: Recibe Sale, nuevo nombre
    {
        // Cargar todas las relaciones necesarias para el PDF
        $sale->load(['client', 'user.reference', 'products.fertilizer']); // Carga cliente, vendedor (+referencia) y productos (+fertilizante)

        // --- Validación de Seguridad Opcional (Recomendado) ---
        // Asegurarse que el usuario logueado es el dueño de la venta
        // if ($sale->idClient !== auth()->id()) {
        //     abort(403, 'No autorizado');
        // }
        // --- Fin Validación ---

        // Generar número de recibo (usa el ID de la Venta)
        $receiptCode = 'CL-' . str_pad($sale->idClient, 4, '0', STR_PAD_LEFT) . '-REC-' . str_pad($sale->id, 5, '0', STR_PAD_LEFT);

        // Cargar vista PDF y pasar el objeto $sale
        $pdf = Pdf::loadView('payments.pdf', compact('sale', 'receiptCode')); // Cambiado: pasa $sale

        // Descargar el PDF
        return $pdf->download('Recibo-' . $receiptCode . '.pdf');
    }
}
