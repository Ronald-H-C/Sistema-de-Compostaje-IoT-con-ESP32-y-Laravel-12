<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductoAbono;
use App\Models\Fertilizer;
use App\Models\ContactMessage;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $query = Fertilizer::with(['location', 'user'])
            ->where('state', 1) // 1 = disponible (según tu definición en la tabla)
            ->where('featured', 1)
            ->whereHas('location', function ($q) {
                $q->whereNotNull('latitude')->whereNotNull('longitude');
            });

        if ($request->filled('title')) {
            $query->where('title', 'LIKE', '%' . $request->title . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('precio_max')) {
            $query->where('price', '<=', (float) $request->precio_max);
        }

        $productos = $query->paginate(9)->appends($request->all());

        return view('index', [
            'productos' => $productos,
            'filtroTitulo' => $request->title,
            'filtroTipo' => $request->type,
            'filtroPrecioMax' => $request->precio_max
        ]);
    }

    public function proyecto()
    {
        return view('project.project');
    }

    public function nosotros()
    {
        return view('about_us.about_us');
    }

    public function contactanos()
    {
        return view('contact_us.contact_us');
    }
    public function productsByUser($id)
    {
        $usuario = \App\Models\User::with('reference')->findOrFail($id);

        $productos = Fertilizer::with(['location', 'user'])
            ->where('idUser', $id)
            ->where('state', 1)
            ->paginate(9); // ¡NO uses ->get() aquí!

        return view('products.userProducts', [
            'usuario' => $usuario,
            'productos' => $productos
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'asunto' => 'required|string|max:150',
            'mensaje' => 'required|string',
        ]);

        ContactMessage::create([
            'full_name' => $request->nombre,
            'email' => $request->email,
            'subject' => $request->asunto,
            'message' => $request->mensaje,
            'state' => 1,
        ]);

        return redirect()->route('contactanos')->with('success', '✅ Tu mensaje ha sido enviado correctamente.');
    }
}
