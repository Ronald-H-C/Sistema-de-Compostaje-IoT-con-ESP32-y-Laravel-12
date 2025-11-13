<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Plan;
use App\Models\UserPlan;
use App\Models\PaymentVoucher;
use App\Models\PlanChangeRequest;
use PHPMailer\PHPMailer\PHPMailer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\Exception;
use Psy\CodeCleaner\FunctionReturnInWriteContextPass;

class UserPlanController extends Controller
{
    /**
     * Lista todos los planes asignados a usuarios.
     */
   public function index()
    {
        $plans = Plan::all();
        $user = Auth::user();
        if(UserPlan::where('idUser', $user->id)
                        ->where('active', '0')
                        ->where('idPlan', '1')
                        ->first()){
                            $expire = true;
                        }
                        else{
                            $expire = false;
                        }
        // Buscar el plan activo del usuario
        $activePlan = UserPlan::where('idUser', $user->id)
                        ->where('active', '1')
                        ->first();
        
        return view('user.plans.index', compact('plans', 'activePlan', 'expire'));
    }

    /**
     * Formulario para editar el plan de un usuario.
     */
    public function edit($id)
    {
        $userPlan = UserPlan::with(['user', 'plan'])->findOrFail($id);
        $planes = Plan::where('state', 1)->get(); // Solo mostrar planes activos

        return view('admin.user_plans.edit', compact('userPlan', 'planes'));
    }

    /**
     * Actualiza el plan asignado a un usuario.
     */
    public function update(Request $request, $id)
    {
        $userPlan = UserPlan::findOrFail($id);

        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'active' => 'required|in:0,1',
        ]);

        $plan = Plan::find($request->plan_id);

        $userPlan->plan_id = $request->plan_id;
        $userPlan->active = $request->active;
        $userPlan->started_at = now();
        $userPlan->expires_at = now()->addDays($plan->duration);
        $userPlan->updated_at = now();

        $userPlan->save();

        return redirect()->route('user_plans.edit', $userPlan->id)
            ->with('success', '✅ Plan actualizado correctamente.');
    }

   public function comprar($id)
{
    $plan = Plan::findOrFail($id);
    $user = auth()->user();
    $usercodeqr = User::Where('role', 'admin')->first();

    return view('user.plans.form', compact('plan', 'user', 'usercodeqr'));
}

public function procesarPago(Request $request, $id)
{
    $plan = Plan::findOrFail($id);
    $user = auth()->user();
    $vendedor = User::Where('role', 'admin')->first();
    $request->validate([
        'receipt' => 'required|image|max:2048', // JPG, PNG hasta 2MB
    ]);

    //dd(base_path('uploads/change_plans'));

  if ($request->hasFile('receipt')) {
    $fileName = time() . '_' . $request->file('receipt')->getClientOriginalName();

    // 📌 Guardar en la carpeta accesible públicamente
    $destination = $_SERVER['DOCUMENT_ROOT'] . '/uploads/change_plans';

    if (!file_exists($destination)) {
        mkdir($destination, 0777, true);
    }

    $request->file('receipt')->move($destination, $fileName);

    // Guardar en la BD la ruta relativa
    $imagePath = 'uploads/change_plans/' . $fileName;
}

    if ($desactive = PlanChangeRequest::where('idUser', $user->id)->where(['state' => 1])->first())
    {
        $desactive->state = 0;
        $desactive->save();
    }


         // Crea el registro en la tabla payment_vouchers
    PlanChangeRequest::create([
        'idUser'       => $user->id,
        'idPlan'       => $plan->id,
        'image'        => $imagePath,
        'state'        => 1, // Por ejemplo: 1 = pendiente, 2 = aprobado
        'observations' => 'Pago en revisión',
        'created_at'   => now(),
    ]);

        $toVendedor = $vendedor->reference->contact_email;
        $toCliente = $user->email;
        $comprobanteURL = 'https://compos.alwaysdata.net/'.($imagePath);


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

            if ($toVendedor) {
                $mail->addAddress($toVendedor);
                $mail->Subject = "Tienes un nuevo pago de planes";
                $mail->Body = "
                <h2>¡Tienes una nueva compra!</h2>
                 <p>El cliente <strong>{$user->name}</strong> ha subido un comprobante de pago para el <strong>{$plan->name}</strong>.</p>
                <ul>
                <li>Cliente: {$user->name}</li>
                <li>Email: {$user->email}</li>
                <li>Plan: {$plan->name}</li>
                <li>Precio: Bs" . number_format($plan->cost, 2) . "</li>
                <li>Comprobante: <a href='{$comprobanteURL}' target='_blank'>Ver imagen</a></li>
                </ul>
                <p>Verifica el pago desde tu panel de administración.</p>
            ";

                $mail->send();
            }

            // ------------------ CORREO PARA EL CLIENTE ------------------
            $mail->clearAddresses(); // Limpiar antes del segundo envío
            $mail->addAddress($toCliente);
            $mail->Subject = "Tu comprobante fue recibido";
            $mail->Body = "
                <h2>Gracias por tu compra</h2>
                <p>Hola <strong>{$user->name}</strong>,</p>
                <p>Hemos recibido tu comprobante de pago para el<strong>{$plan->name}</strong>.</p>
                <ul>
                <li>Plan: {$plan->name}</li>
                <li>Precio: Bs" . number_format($plan->cost, 2) . "</li>
                </ul>
                <p>Estamos verificando tu pago. Pronto recibirás la confirmación final.</p>
                <p><a href='{$comprobanteURL}' target='_blank'>Ver tu comprobante</a></p>
                <p>Gracias por confiar en <strong>CompostajeIoT </strong></p>
            ";
            $mail->send();
        } catch (Exception $e) {
            Log::error(" Error al enviar correos: " . $e->getMessage());
        }

        return redirect()->route('mostrar', ['plan' => $plan->id, 'user' => $user->id]);
    
    // Guarda la imagen del comprobante en storage/public/receipts
    
   
}

public function mostrarRecibo($plan, $user)
    {
        $pago = PlanChangeRequest::with([
            'user',
            'plan' // Cargar el dueño del product// Cargar la ubicación si es necesario
        ])
            ->where('idUser', $user)
            ->where('idPlan', $plan)
            ->latest()
            ->firstOrFail();

        return view('user.plans.receipt', compact('pago'));
    }

    public function descargarPDF($id)
    {
        $pago = UserPlan::with([
            'user',
            'plan' // Cargar el dueño del producto // Cargar la ubicación si es necesario
        ])
            ->findOrFail($id);

        // Generar número de recibo único visual
        $receiptCode = 'CL-' . str_pad($pago->idclient, 4, '0', STR_PAD_LEFT) . '-REC-' . str_pad($pago->id, 5, '0', STR_PAD_LEFT);

        // --- INICIO: CÓDIGO AÑADIDO PARA LITERAL ---

        // 1. Definimos la moneda (para Bolivia)
        $moneda = [
            'singular' => 'BOLIVIANO',
            'plural' => 'BOLIVIANOS',
        ];

        // 2. Obtenemos el monto del plan
        $monto = $pago->plan->cost;

        // 3. Llamamos a la función helper para convertir el número
        $pago_literal = $this->convertirNumeroALetras($monto, $moneda);

        // --- FIN: CÓDIGO AÑADIDO ---


        // 4. Cargar vista y generar PDF (pasamos la nueva variable $pago_literal)
        $pdf = Pdf::loadView('user.plans.pdf', compact('pago', 'receiptCode', 'pago_literal'));

        return $pdf->download($receiptCode . '.pdf');
    }


    // -------------------------------------------------------------------
    // --- INICIO: FUNCIONES HELPER PARA CONVERTIR NÚMERO A LITERAL ---
    // --- (Copia estas funciones dentro de tu clase de Controlador) ---
    // -------------------------------------------------------------------

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

    public function showAdqPlans()
    {
        $espera = PlanChangeRequest::with(['user', 'plan'])
            ->where('state', 1)
            ->where('idUser', Auth::id()) // Forma más limpia de obtener el ID
            ->first();

        $change_plans = UserPlan::with(['user', 'plan'])
            ->where('idUser', Auth::id()) // Forma más limpia de obtener el ID
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.plans.viewPlans', compact('change_plans', 'espera'));
    }

}

