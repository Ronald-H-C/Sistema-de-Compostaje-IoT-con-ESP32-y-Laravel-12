<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Plan;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPlanController extends Controller
{
    /**
     * Lista todos los planes asignados a usuarios.
     */
    public function index()
    {
        $userPlans = UserPlan::with(['user', 'plan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.user_plans.gestionUserPlans', compact('userPlans'));
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

        $userPlan->idPlan = $request->plan_id;
        $userPlan->active = $request->active;
        $userPlan->started_at = now();
        $userPlan->expires_at = now()->addDays($plan->duration);
        $userPlan->updated_at = now();

        $userPlan->save();

        return redirect()->route('user_plans.edit', $userPlan->id)
            ->with('success', '✅ Plan actualizado correctamente.');
    }
}
