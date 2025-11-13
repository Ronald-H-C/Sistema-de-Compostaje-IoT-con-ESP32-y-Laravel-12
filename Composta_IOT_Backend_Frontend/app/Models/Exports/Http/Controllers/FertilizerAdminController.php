<?php

namespace App\Http\Controllers;

use App\Models\Fertilizer;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FertilizerAdminController extends Controller
{
    /**
     * Muestra todos los productos activos (vista pública o cliente).
     */
    public function index(Request $request)
    {
        $productos = Fertilizer::with(['location', 'user'])
            ->where('state', 1)
            ->paginate(10);

        return view('admin.products.gestionProducts', compact('productos'));
    }

    /**
     * Formulario para crear un nuevo producto.
     */
    public function create()
    {
        $usuarios = User::where('state', 1)->get();
        return view('admin.products.create', compact('usuarios'));
    }

    /**
     * Almacena un nuevo producto.
     */
   public function store(Request $request)
{
    $request->validate([
        'idUser' => 'required|exists:users,id',
        'title' => 'required|string|max:100',
        'description' => 'required|string',
        'type' => 'required|in:composta,humus,abono_organico,otro',
        'amount' => 'required|numeric',
        'price' => 'required|numeric',
        'image' => 'nullable|image|max:2048',
        'address' => 'required|string',
        'link_google_maps' => 'required|string'
    ]);

    $enlace = $request->link_google_maps;
    $lat = $lng = null;

    if (
        preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $enlace, $matches) ||
        preg_match('/\/@(-?\d+\.\d+),(-?\d+\.\d+)/', $enlace, $matches)
    ) {
        $lat = number_format((float)$matches[1], 8, '.', '');
        $lng = number_format((float)$matches[2], 8, '.', '');
    } else {
        return back()->with('error', 'No se pudo extraer latitud y longitud del enlace.');
    }

    DB::beginTransaction();
    try {
        $imagePath = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destination = $_SERVER['DOCUMENT_ROOT'] . '/uploads/fertilizers';

            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }

            if ($file->move($destination, $filename)) {
                $imagePath = 'uploads/fertilizers/' . $filename;
            }
        }

        $fertilizer = Fertilizer::create([
            'idUser' => $request->idUser,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'amount' => $request->amount,
            'price' => $request->price,
            'image' => $imagePath,
            'state' => 1, // Activo por defecto
            'updated_by' => auth()->id(),
        ]);

        Location::create([
            'idFertilizer' => $fertilizer->id,
            'latitud' => $lat,
            'longitud' => $lng,
            'address' => $request->address,
            'link_google_maps' => $enlace
        ]);

        DB::commit();
        return redirect()->route('products.create')->with('success', 'Producto creado correctamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error al guardar: ' . $e->getMessage());
    }
}


    /**
     * Formulario de edición de producto.
     */
    public function edit($id)
    {
        $producto = Fertilizer::with(['location', 'user'])->findOrFail($id);
        $usuarios = User::where('state', 1)->get();
        return view('admin.products.edit', compact('producto', 'usuarios'));
    }

    /**
     * Actualiza un producto.
     */
    public function update(Request $request, $id)
{
    $producto = Fertilizer::with('location')->findOrFail($id);

    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'type' => 'required|in:composta,humus,abono_organico,otro',
        'amount' => 'required|numeric|min:0',
        'price' => 'required|numeric|min:0',
        'image' => 'nullable|image|max:2048',
        'address' => 'required|string',
        'link_google_maps' => 'required|string',
        'state' => 'required|in:0,1'
    ]);

    $enlace = $request->link_google_maps;
    $lat = $lng = null;

    if (
        preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $enlace, $matches) ||
        preg_match('/\/@(-?\d+\.\d+),(-?\d+\.\d+)/', $enlace, $matches)
    ) {
        $lat = number_format((float)$matches[1], 8, '.', '');
        $lng = number_format((float)$matches[2], 8, '.', '');
    } else {
        return back()->with('error', 'No se pudo extraer latitud y longitud del enlace.');
    }

    DB::beginTransaction();
    try {
        $producto->title = $request->title;
        $producto->description = $request->description;
        $producto->type = $request->type;
        $producto->amount = $request->amount;
        $producto->price = $request->price;
        $producto->state = $request->state;

        if ($request->hasFile('image')) {
            // Eliminar la imagen anterior si existe
            if ($producto->image) {
                $oldPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $producto->image;
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Subir la nueva imagen
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destination = $_SERVER['DOCUMENT_ROOT'] . '/uploads/fertilizers';

            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }

            if ($file->move($destination, $filename)) {
                $producto->image = 'uploads/fertilizers/' . $filename;
            }
        }

        $producto->save();

        if ($producto->location) {
            $producto->location->update([
                'latitud' => $lat,
                'longitud' => $lng,
                'address' => $request->address,
                'link_google_maps' => $enlace
            ]);
        }

        DB::commit();
        return redirect()->route('products.edit', $producto->id)
            ->with('success', 'Producto actualizado correctamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error al actualizar: ' . $e->getMessage());
    }
}

    /**
     * Eliminado lógico.
     */
    public function destroy($id)
    {
        $producto = Fertilizer::findOrFail($id);
        $producto->state = 0;
        $producto->save();

        return redirect()->route('products.index')->with('success', 'Producto desactivado correctamente.');
    }

    /**
     * Lista de productos eliminados lógicamente.
     */
    public function delete()
    {
        $productos = Fertilizer::with(['location', 'user'])
            ->where('state', 0)
            ->paginate(10);

        return view('admin.products.delete', compact('productos'));
    }

    /**
     * Reactivar producto (lógica inversa del eliminado).
     */
    public function activate($id)
    {
        $producto = Fertilizer::findOrFail($id);
        $producto->state = 1;
        $producto->save();

        return redirect()->route('products.delete')->with('success', 'Producto reactivado correctamente.');
    }
}
