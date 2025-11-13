<?php

namespace App\Http\Controllers;

use App\Models\Fertilizer;
use App\Models\PaymentVoucher;
use App\Models\PaymentProduct;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sale;
use App\Models\Detail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class PaymentUserVoucherController extends Controller
{
    public function index()
    {
        $user = Auth::User();
        $vouchers = Sale::with(['client', 'products'])
            ->where('idUser', $user->id)
            ->where('state', '=', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(10);


        return view('user.voucher.index', compact('vouchers'));
    }

    public function edit($id)
    {
        $voucher = Sale::findOrFail($id);
        $users = User::where('state', '>', 0)->get();
        $products = Fertilizer::where('state', '>', 0)->get();
        return view('user.voucher.edit', compact('voucher', 'users', 'products'));
    }

    public function update(Request $request, $id)
    {
        $voucher = Sale::findOrFail($id);
        $voucher->loadMissing('products');

        $request->validate([
            'state' => 'required|in:0,1,2',
        ]);

        $voucher->state = $request->state;
        $voucher->save();


        $toCliente = $voucher->client->email;
        $comprobanteURL = 'https://compos.alwaysdata.net/'.($voucher->image);

        if($voucher->updated_by === null)
        {
            if ($request->state == 1)
        {
            try {
    // ------------------ CORREO PARA EL VENDEDOR ------------------
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp-esp32.alwaysdata.net';
                $mail->SMTPAuth = true;
                $mail->Username = 'esp32@alwaysdata.net';
                $mail->Password = 'ronaldmbts123$$$';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;
                $mail->setFrom('esp32@alwaysdata.net', 'CompostajeIoT');
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8'; // Buena práctica para tildes y caracteres

                // ------------------ CORREO PARA EL CLIENTE ------------------
                
                // --- 1. Construimos la lista de productos ---
                // Esta variable guardará el HTML de la lista
                $productListHtml = "<ul>";
                
                // Iteramos sobre la colección $voucher->products
                foreach ($voucher->products as $item) {
                    $productName = $item->fertilizer->title ?? 'Producto no disponible';
                    $quantity = $item->amount;
                    $subtotal = number_format($item->subtotal, 2);

                    // Añadimos cada producto como un <li>
                    $productListHtml .= "<li>{$quantity} x {$productName} (Subtotal: \${$subtotal})</li>";
                }
                
                $productListHtml .= "</ul>";
                
                // --- 2. Obtenemos el total ---
                $total = number_format($voucher->total, 2);

                // --- 3. Armamos el cuerpo del email ---
                $mail->addAddress($toCliente);
                $mail->Subject = "Tu comprobante fue revisado y aceptado";
                $mail->Body = "
                    <h2>Gracias por tu compra</h2>
                    <p>Hola <strong>{$voucher->client->name}</strong>,</p>
                    <p>Tu comprobante fue revisado y aceptado, tu compra esta efectuada. Puede pasar a la ubicacion a recoger su pedido o contactarse con nosotros.</p>
                    
                    <p><strong>Resumen de tu compra:</strong></p>
                    
                    {$productListHtml} 
                    
                    <p><strong>Total Pagado: \${$total}</strong></p>
                    
                    <p><a href='{$comprobanteURL}' target='_blank'>Ver tu comprobante</a></p>
                    <p>Gracias por confiar en <strong>CompostajeIoT</strong></p>
                ";
                
                $mail->send();

                $voucher->updated_by = Auth::id();
                $voucher->save();

            } catch (Exception $e) {
                Log::error(" Error al enviar correos: " . $e->getMessage());
            }

            
        }

        if ($request->state == 2)
        {
             try {
    // ------------------ CORREO PARA EL VENDEDOR ------------------
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp-esp32.alwaysdata.net';
                $mail->SMTPAuth = true;
                $mail->Username = 'esp32@alwaysdata.net';
                $mail->Password = 'ronaldmbts123$$$';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;
                $mail->setFrom('esp32@alwaysdata.net', 'CompostajeIoT');
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8'; // Buena práctica para tildes y caracteres

                // ------------------ CORREO PARA EL CLIENTE ------------------
                
                // --- 1. Construimos la lista de productos ---
                // Esta variable guardará el HTML de la lista
                $productListHtml = "<ul>";
                
                // Iteramos sobre la colección $voucher->products
                foreach ($voucher->products as $item) {
                    $productName = $item->fertilizer->title ?? 'Producto no disponible';
                    $quantity = $item->amount;
                    $subtotal = number_format($item->subtotal, 2);

                    // Añadimos cada producto como un <li>
                    $productListHtml .= "<li>{$quantity} x {$productName} (Subtotal: \${$subtotal})</li>";
                }
                
                $productListHtml .= "</ul>";
                
                // --- 2. Obtenemos el total ---
                $total = number_format($voucher->total, 2);

                // --- 3. Armamos el cuerpo del email ---
                $mail->addAddress($toCliente);
                $mail->Subject = "Tu comprobante fue revisado y denegado";
                $mail->Body = "
                    
                    <p>Hola <strong>{$voucher->client->name}</strong>,</p>
                   <p>Tu comprobante fue revisado y denegado, tu compra no se efectuo.</p>
                    
                    <p><strong>Resumen de tu compra:</strong></p>
                    
                    {$productListHtml} 
                    
                    <p><strong>Total Pagado: \${$total}</strong></p>
                    
                    <p><a href='{$comprobanteURL}' target='_blank'>Ver tu comprobante</a></p>
                    <p>Gracias por confiar en <strong>CompostajeIoT</strong></p>
                ";
                
                $mail->send();

                foreach ($voucher->products as $item) {

                    $fertilizerId = $item->idFertilizer; 
                    $product = Fertilizer::find($fertilizerId); 
                    
                    if ($product) {
                        $product->increment('stock', $item->amount);
                        echo "Stock restaurado para: " . $product->title . "\n";
                    }
                }

                $voucher->state = 2; 
                $voucher->updated_by = Auth::id();
                $voucher->save();

            } catch (Exception $e) {
                Log::error(" Error al enviar correos: " . $e->getMessage());
            }

    }
        }
        
        return redirect()->route('editVoucher', $voucher->id)->with('success', '✅ Comprobante actualizado.');
    }


    public function delete()
    {
        $vouchers = Sale::with(['client', 'products'])
            ->where('state', 2)
            ->where('idUser', Auth::id())
            ->paginate(10);

        return view('user.voucher.delete', compact('vouchers'));
    }

}
