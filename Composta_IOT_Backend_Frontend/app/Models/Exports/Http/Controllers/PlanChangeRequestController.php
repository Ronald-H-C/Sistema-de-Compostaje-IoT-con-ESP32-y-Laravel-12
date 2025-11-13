<?php

namespace App\Http\Controllers;

use App\Models\PlanChangeRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserPlan;
use App\Models\Plan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class PlanChangeRequestController extends Controller
{
    public function index()
    {
        $change_plans = PlanChangeRequest::with(['user', 'plan'])
            ->where('state', '>', 0)
            ->paginate(10);

        return view('admin.change_plans.gestionChangePlans', compact('change_plans'));
    }

    public function edit($id)
    {
        $change_plan = PlanChangeRequest::findOrFail($id);
        $users = User::where('state', '>', 0)->get();
        $plans = Plan::where('state', '>', 0)->get();
        return view('admin.change_plans.edit', compact('change_plan', 'users', 'plans'));
    }

    public function update(Request $request, $id)
    {
        
        $change_plan = PlanChangeRequest::findOrFail($id);

        $request->validate([
            'observations' => 'nullable|string',
            'state' => 'required|in:0,1,2',
        ]);

        

        $change_plan->observations = $request->observations;
        $change_plan->state = $request->state;
        $change_plan->updated_by = Auth::id();
        $change_plan->save();

        $toCliente = $change_plan->user->email;
        $comprobanteURL = 'https://compos.alwaysdata.net/'.($change_plan->image);

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

            // ------------------ CORREO PARA EL CLIENTE ------------------
            $mail->addAddress($toCliente);
            $mail->Subject = "Tu comprobante fue revisado y efectuado";
            $mail->Body = "
                <h2>Gracias por tu compra</h2>
                <p>Hola <strong>{$change_plan->user->name}</strong>,</p>
                <p>Tu comprobante fue revisado y efectuado, el pago del plan se efectuo.<strong>{$change_plan->plan->name}</strong>.</p>
                <ul>
                <li>Cliente: {$change_plan->user->name}</li>
                <li>Email: {$change_plan->user->email}</li>
                <li>Plan: {$change_plan->plan->name}</li>
                <li>Precio: $" . number_format($change_plan->plan->cost, 2) . "</li>
                </ul>
                <p><a href='{$comprobanteURL}' target='_blank'>Ver tu comprobante</a></p>
                <p>Gracias por confiar en <strong>CompostajeIoT </strong></p>
            ";
            $mail->send();
            } catch (Exception $e) {
            Log::error(" Error al enviar correos: " . $e->getMessage());
        }


            // 🔹 Desactivar cualquier plan activo anterior
            UserPlan::where('idUser', $change_plan->idUser)
                ->where('active', 1)
                ->update(['active' => 0]);

            // 🔹 Buscar si ya existe el plan solicitado
            if ($activePlan = UserPlan::where('idUser', $change_plan->idUser)
                        ->where('idPlan', $change_plan->idPlan)
                        ->first())
            {
                $activePlan->active = 1;
                $activePlan->started_at = now();
                $activePlan->updated_at = now();
                $activePlan->expires_at = now()->addDays(30); // asegúrate de actualizar la fecha de expiración también
                $activePlan->save();
            }
            else
            {
                UserPlan::create([
                    'idUser'     => $change_plan->idUser,
                    'idPlan'     => $change_plan->idPlan,
                    'started_at' => now(),
                    'created_at' => now(),
                    'expires_at' => now()->addDays(30),
                    'active'     => 1,
                ]);
            }
        }

        if ($request->state == 0)
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

            // ------------------ CORREO PARA EL CLIENTE ------------------
            $mail->addAddress($toCliente);
            $mail->Subject = "Tu comprobante fue revisado y denegado";
            $mail->Body = "
                <h2>Gracias por tu compra</h2>
                <p>Hola <strong>{$change_plan->user->name}</strong>,</p>
                <p>Tu comprobante fue revisado y denegado, el pago del plan no se efectuo.<strong>{$change_plan->plan->name}</strong>.</p>
                <ul>
                <li>Cliente: {$change_plan->user->name}</li>
                <li>Email: {$change_plan->user->email}</li>
                <li>Plan: {$change_plan->plan->name}</li>
                <li>Precio: $" . number_format($change_plan->plan->cost, 2) . "</li>
                </ul>
                <p><a href='{$comprobanteURL}' target='_blank'>Ver tu comprobante</a></p>
                <p>Gracias por confiar en <strong>CompostajeIoT </strong></p>
            ";
            $mail->send();
            } catch (Exception $e) {
            Log::error(" Error al enviar correos: " . $e->getMessage());
        }
        
    }

        return redirect()->route('change_plans.edit', $change_plan->id)->with('success', '✅ Comprobante actualizado.');
    }


    public function delete()
    {
        $change_plans = PlanChangeRequest::with(['user', 'plan'])->where('state', 0)->paginate(10);
        return view('admin.change_plans.delete', compact('change_plans'));
    }

    public function activate($id)
    {
        $change_plan = PlanChangeRequest::findOrFail($id);
        $change_plan->state = 1;
        $change_plan->save();

        return redirect()->route('change_plans.delete')->with('success', '✅ Comprobante reactivado.');
    }
}
