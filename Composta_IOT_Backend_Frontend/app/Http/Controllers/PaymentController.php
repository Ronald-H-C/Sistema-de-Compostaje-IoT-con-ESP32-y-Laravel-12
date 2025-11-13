<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Fertilizer;
use App\Models\User;
use App\Models\PaymentProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Log;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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
            // Cargamos 'user' (vendedor) también
            $sale->load('client', 'user', 'products.fertilizer'); 
            
            try {
               $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp-compos.alwaysdata.net';
                $mail->SMTPAuth = true;
                $mail->Username = 'compos@alwaysdata.net';
                $mail->Password = 'compostajeiot&2025';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;
                $mail->setFrom('compos@alwaysdata.net', 'CompostajeIoT');
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8'; // Buena práctica para tildes y caracteres

                // --- 10a. Preparar Email para el CLIENTE ---

                // Variables que usaremos para AMBOS correos
                $productListHtml = "<ul>";
                foreach ($sale->products as $item) {
                    $productName = $item->fertilizer->title ?? 'N/A';
                    $quantity = $item->amount;
                    $subtotal = number_format($item->subtotal, 2);
                    $productListHtml .= "<li>{$quantity} x {$productName} (Subtotal: Bs {$subtotal})</li>";
                }
                $productListHtml .= "</ul>";
                
                $total = number_format($sale->total, 2);
                
                // Esta es la única URL correcta
                $comprobanteURL = 'https://compos.alwaysdata.net/' . $sale->image; 

                $toCliente = $sale->client->email;
                
                // BORRAMOS TODO EL CÓDIGO REPETIDO DE AQUÍ
                if($toCliente)
                {
                    $clientName = $sale->client->name ?? 'Cliente';
                    
                    // Ahora usamos las variables correctas que creamos fuera
                    $mail->addAddress($toCliente);
                    $mail->Subject = "Hemos recibido tu comprobante";
                    $mail->Body = "
                        <h2>Comprobante Recibido</h2>
                        <p>Hola <strong>{$clientName}</strong>,</p>
                        <p>Hemos recibido correctamente tu comprobante de pago...</p>
                        {$productListHtml}
                        <p><strong>Total Pagado: Bs {$total}</strong></p>
                        <p>Puedes ver el comprobante <a href='{$comprobanteURL}' target='_blank'>aquí</a>.</p>
                        <p>Gracias...</p>
                    ";
                    $mail->send();
                }

                // --- 10b. Preparar Email para el VENDEDOR ---

                $toVendedor = $sale->user->email; // Email del vendedor
                
                if ($toVendedor) 
                {
                    // Info del vendedor y cliente para el cuerpo del email
                    $vendedorName = $sale->user->name ?? 'Vendedor';
                    $clientName = $sale->client->name ?? 'Cliente';
                    $clientEmail = $sale->client->email ?? 'N/A';

                    $mail->clearAddresses(); // ¡Importante! Limpiar destinatario anterior
                    $mail->addAddress($toVendedor);
                    $mail->Subject = "¡Nueva Venta! Pedido, pendiente de verificación";
                    $mail->Body = "
                        <h2>¡Has recibido una nueva venta!</h2>
                        <p>Hola <strong>{$vendedorName}</strong>,</p>
                        <p>El cliente <strong>{$clientName}</strong> (Email: {$clientEmail}) ha realizado una compra y ha subido su comprobante de pago.</p>
                        
                        <p><strong>Detalles del Pedido:</strong></p>
                        {$productListHtml} 
                        
                        <p><strong>Total (según comprobante): Bs {$total}</strong></p>
                        
                        <p><strong>Comprobante de Pago:</strong></p>
                        <p><a href='{$comprobanteURL}' target='_blank'>Ver imagen del comprobante</a></p>
                        
                        <p>Por favor, ingresa a tu panel de administración para verificar el pago y aprobar esta venta.</p>
                    ";

                    $mail->send();
                }

            } catch (\Exception $e) {
                // Esto atrapa TODOS LOS DEMÁS errores (clase no encontrada, error "on null", etc.)
                Log::error("Error de lógica al generar email: " . $e->getMessage());
                session()->flash('warning', 'Tu compra fue exitosa, pero hubo un problema al generar el email.');
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


    public function descargarPDFVenta(Sale $sale) 
    {
        // Cargar todas las relaciones necesarias para el PDF
        // Esta vista usa: client, user.reference, y products.fertilizer
        $sale->load(['client', 'user.reference', 'products.fertilizer']); 

        // Generar número de recibo (usa el ID de la Venta)
        // Usamos client->id ya que lo estamos cargando.
        $receiptCode = 'CL-' . str_pad($sale->client->id ?? $sale->idClient, 4, '0', STR_PAD_LEFT) . '-REC-' . str_pad($sale->id, 5, '0', STR_PAD_LEFT);

        // Obtener el total. La vista usa $sale->total.
        $total = $sale->total ?? 0;

        // Convertir el total a letras (Bolivianos)
        $montoLiteral = $this->convertirNumeroALetras($total, [
            'singular' => 'BOLIVIANO',
            'plural'   => 'BOLIVIANOS',
        ]);

        // Cargar vista PDF y pasar todas las variables
        $pdf = Pdf::loadView('payments.pdf', compact('sale', 'receiptCode', 'montoLiteral')); 

        // Descargar el PDF
        return $pdf->download('Recibo-' . $receiptCode . '.pdf');
    }

    public function shop() 
    {

        $sales = Sale::with([
        'client',           
        'user',               
        'products.fertilizer' 
    ])
    ->where('idClient', Auth::User()->id)
    ->where('state', '<', 2) 
    ->latest()              
    ->get();
        return view('salesClient', compact('sales'));
    }

    private function convertirNumeroALetras($num, $moneda = [])
    {
        $moneda = array_merge([
            'singular' => 'PESO',
            'plural' => 'PESOS',
        ], $moneda);

        $enteros = floor($num);
        $centavos = round(($num - $enteros) * 100);
        if ($centavos >= 100) {
            $enteros++;
            $centavos = 0;
        }

        $letrasMoneda = '';
        if ($enteros == 0) {
            $letrasMoneda = 'CERO';
        } elseif ($enteros == 1) {
            $letrasMoneda = $this->Millones($enteros);
        } else {
            $letrasMoneda = $this->Millones($enteros);
        }

        $letrasCentavos = '00/100';
        if ($centavos > 0) {
            $letrasCentavos = str_pad($centavos, 2, '0', STR_PAD_LEFT) . '/100';
        }
        
        $monedaNombre = ($enteros == 1) ? $moneda['singular'] : $moneda['plural'];

        return trim("{$letrasMoneda} {$monedaNombre} {$letrasCentavos}");
    }

    private function Unidades($num)
    {
        switch ($num) {
            case 1: return 'UN';
            case 2: return 'DOS';
            case 3: return 'TRES';
            case 4: return 'CUATRO';
            case 5: return 'CINCO';
            case 6: return 'SEIS';
            case 7: return 'SIETE';
            case 8: return 'OCHO';
            case 9: return 'NUEVE';
        }
        return '';
    }

    private function Decenas($num)
    {
        $decena = floor($num / 10);
        $unidad = $num % 10;

        switch ($decena) {
            case 1:
                switch ($unidad) {
                    case 0: return 'DIEZ';
                    case 1: return 'ONCE';
                    case 2: return 'DOCE';
                    case 3: return 'TRECE';
                    case 4: return 'CATORCE';
                    case 5: return 'QUINCE';
                    default: return 'DIECI' . $this->Unidades($unidad);
                }
            case 2:
                return $unidad == 0 ? 'VEINTE' : 'VEINTI' . $this->Unidades($unidad);
            case 3: return 'TREINTA' . ($unidad > 0 ? ' Y ' . $this->Unidades($unidad) : '');
            case 4: return 'CUARENTA' . ($unidad > 0 ? ' Y ' . $this->Unidades($unidad) : '');
            case 5: return 'CINCUENTA' . ($unidad > 0 ? ' Y ' . $this->Unidades($unidad) : '');
            case 6: return 'SESENTA' . ($unidad > 0 ? ' Y ' . $this->Unidades($unidad) : '');
            case 7: return 'SETENTA' . ($unidad > 0 ? ' Y ' . $this->Unidades($unidad) : '');
            case 8: return 'OCHENTA' . ($unidad > 0 ? ' Y ' . $this->Unidades($unidad) : '');
            case 9: return 'NOVENTA' . ($unidad > 0 ? ' Y ' . $this->Unidades($unidad) : '');
            case 0: return $this->Unidades($unidad);
        }
    }

    private function Centenas($num)
    {
        $centenas = floor($num / 100);
        $decenas = $num % 100;

        switch ($centenas) {
            case 1:
                if ($decenas > 0) return 'CIENTO ' . $this->Decenas($decenas);
                return 'CIEN';
            case 2: return 'DOSCIENTOS ' . $this->Decenas($decenas);
            case 3: return 'TRESCIENTOS ' . $this->Decenas($decenas);
            case 4: return 'CUATROCIENTOS ' . $this->Decenas($decenas);
            case 5: return 'QUINIENTOS ' . $this->Decenas($decenas);
            case 6: return 'SEISCIENTOS ' . $this->Decenas($decenas);
            case 7: return 'SETECIENTOS ' . $this->Decenas($decenas);
            case 8: return 'OCHOCIENTOS ' . $this->Decenas($decenas);
            case 9: return 'NOVECIENTOS ' . $this->Decenas($decenas);
        }
        return $this->Decenas($decenas);
    }

    private function Seccion($num, $divisor, $strSingular, $strPlural)
    {
        $cientos = floor($num / $divisor);
        // $resto = $num % $divisor; // Esta línea se ignora, el 'resto' se maneja en la función que llama.
        $letras = '';

        if ($cientos > 0) {
            if ($cientos > 1) {
                $letras = $this->Centenas($cientos) . ' ' . $strPlural;
            } else {
                $letras = $strSingular;
            }
        }
        
        // El bloque "if ($resto > 0)" que causaba el error se ha eliminado.

        return $letras;
    }

    private function Miles($num)
    {
        $divisor = 1000;
        $cientos = floor($num / $divisor);
        $resto = $num % $divisor;

        $strMiles = $this->Seccion($num, $divisor, 'UN MIL', 'MIL');
        $strCentenas = $this->Centenas($resto);

        if ($strMiles == '') return $strCentenas;
        return trim($strMiles . ' ' . $strCentenas);
    }

    private function Millones($num)
    {
        $divisor = 1000000;
        $cientos = floor($num / $divisor);
        $resto = $num % $divisor;

        $strMillones = $this->Seccion($num, $divisor, 'UN MILLÓN', 'MILLONES');
        $strMiles = $this->Miles($resto);

        if ($strMillones == '') return $strMiles;
        return trim($strMillones . ' ' . $strMiles);
    }
}
