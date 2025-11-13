<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use App\Models\User;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::where('state', 1)->paginate(10);
        return view('admin.plans.gestionPlans', ['plans' => $plans]);
    }

    public function create()
    {
        $usuarios = User::where('state', 1)->get();
        return view('admin.plans.create', ['usuarios' => $usuarios]);
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'state' => 'required|numeric|in:0,1',
            'post_limit' => 'nullable|integer|min:0',
            'created_by' => 'nullable|integer|exists:users,id',
            'updated_by' => 'nullable|integer|exists:users,id',
        ]);

        Plan::create($request->all());

        return redirect()->route('plans.create')->with('success', '✅ Plan registrado correctamente.');
    }

    public function edit($id)
    {
        $plan = Plan::findOrFail($id);
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'state' => 'required|numeric|in:0,1',
            'post_limit' => 'nullable|integer|min:0',
            'created_by' => 'nullable|integer|exists:users,id',
            'updated_by' => 'nullable|integer|exists:users,id',
        ]);

        $plan->update($request->all());

        return redirect()->route('plans.edit', $plan->id)->with('success', '✅ Plan actualizado correctamente.');
    }

    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->state = 0; // Desactivado
        $plan->save();

        return redirect()->route('plans.index')->with('success', '✅ Plan desactivado correctamente.');
    }

    public function delete()
    {
        $plans = Plan::where('state', 0)->paginate(10);
        return view('admin.plans.delete', ['plans' => $plans]);
    }

    public function activate($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->state = 1;
        $plan->save();

        return redirect()->route('plans.delete')->with('success', '✅ Plan reactivado correctamente.');
    }
}
