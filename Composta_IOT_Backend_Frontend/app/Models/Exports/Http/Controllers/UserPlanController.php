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

    return view('user.plans.form', compact('plan', 'user'));
}

public function procesarPago(Request $request, $id)
{
    $plan = Plan::findOrFail($id);
    $user = auth()->user();

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

        $toVendedor = 'erikedilespindola@gmail.com';
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
                <li>Precio: $" . number_format($plan->cost, 2) . "</li>
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
                <li>Precio: $" . number_format($plan->cost, 2) . "</li>
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
        $pago = PlanChangeRequest::with([
            'user',
            'plan' // Cargar el dueño del producto // Cargar la ubicación si es necesario
        ])
            ->findOrFail($id);

        // Generar número de recibo único visual
        $receiptCode = 'CL-' . str_pad($pago->idclient, 4, '0', STR_PAD_LEFT) . '-REC-' . str_pad($pago->id, 5, '0', STR_PAD_LEFT);

        // Cargar vista y generar PDF
        $pdf = Pdf::loadView('user.plans.pdf', compact('pago', 'receiptCode'));

        return $pdf->download($receiptCode . '.pdf');
    }

}

