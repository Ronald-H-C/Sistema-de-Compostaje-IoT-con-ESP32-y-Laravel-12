<?php

namespace App\Http\Controllers;

use App\Models\Fertilizer;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use App\Models\Location;

class FertilizerController extends Controller
{
    public function index()
    {
        $userV = Auth::User();
        $fertilizers = Fertilizer::with(['location', 'user'])
            ->where('state', 1)
            ->where('idUser', $userV->id)
            ->paginate(10); // Esto reemplaza LIMIT y OFFSET automáticamente

        return view('user.vistaProductos', compact('fertilizers', 'userV'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::User();
        return view('admin.create', ['userV' => $user]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'address' => 'required|string',
            'link' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048', // 2 MB máximo
        ]);

        $enlace = $request->link;
        $lat = $lng = null;

        if (
            preg_match('/[?&]q=(-?\d+\.\d+),(-?\d+\.\d+)/', $enlace, $matches) ||   // ?q=lat,lng
            preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $enlace, $matches) ||       // @lat,lng
            preg_match('/(-?\d+\.\d+),(-?\d+\.\d+)/', $enlace, $matches)           // cualquier lat,lng
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
                $image = $request->file('image');

                // Redimensionar y convertir a JPG
                $resized = Image::make($image)
                    ->resize(800, 800, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->encode('jpg', 85);

                // Nombre único
                $filename = uniqid() . '.jpg';

                // Ruta relativa (para BD)
                $imagePath = 'uploads/fertilizers/' . $filename;

                // Ruta absoluta en public/
                $resized->save(public_path($imagePath));
            }

            // Crear Fertilizer
            $fertilizer = Fertilizer::create([
                'idUser' => Auth::user()->id,
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
                'amount' => $request->amount,
                'stock' => $request->stock,
                'price' => $request->price,
                'image' => $imagePath
            ]);

            // Crear Location
            Location::create([
                'idFertilizer' => $fertilizer->id,
                'latitude' => $lat,
                'longitude' => $lng,
                'address' => $request->address,
                'link_google_maps' => $enlace
            ]);

            DB::commit();
            return redirect()->route('vista')->with('success', 'Producto creado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('vista')->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        //
    }


    public function update(Request $request, $id)
{
    // 1. Validar primero con las reglas corregidas
    $validatedData = $request->validate([
        'title' => 'required|string|max:100',
        'description' => 'nullable|string',
        'type' => 'required|string',
        'amount' => 'required|numeric|min:0',
        'address' => 'required|string|max:255', // CORREGIDO
        'link' => 'required|string|url',      // CORREGIDO
        'stock' => 'required|numeric|min:0',
        'price' => 'required|numeric|min:0',
        'image' => 'nullable|image|max:2048', 
    ]);

    try {
        // 2. Iniciar una transacción
        DB::beginTransaction();

        $fertilizer = Fertilizer::findOrFail($id);
        
        // 3. Usar firstOrFail() para encontrar la ubicación
        $location = Location::where('idFertilizer', $fertilizer->id)->firstOrFail();

        // 4. Extraer Lat/Lng (usando el dato validado)
        $enlace = $validatedData['link'];
        $lat = $lng = null;

        if (
            preg_match('/[?&]q=(-?\d+\.\d+),(-?\d+\.\d+)/', $enlace, $matches) ||
            preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $enlace, $matches) ||
            preg_match('/(-?\d+\.\d+),(-?\d+\.\d+)/', $enlace, $matches)
        ) {
            $lat = number_format((float)$matches[1], 8, '.', '');
            $lng = number_format((float)$matches[2], 8, '.', '');
        } else {
            // Si la regex falla, no podemos continuar.
            DB::rollBack(); // Revertir la transacción
            return back()->with('error', 'No se pudo extraer latitud y longitud del enlace.');
        }

        // 5. Manejar la imagen (tu lógica estaba bien)
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior
            if ($fertilizer->image && File::exists(public_path($fertilizer->image))) {
                unlink(public_path($fertilizer->image));
            }

            $image = $request->file('image');
            $resized = Image::make($image)
                ->resize(800, 800, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('jpg', 85);

            $filename = uniqid() . '.jpg';
            $imagePath = 'uploads/fertilizers/' . $filename;
            $resized->save(public_path($imagePath));

            $fertilizer->image = $imagePath;
        }

        // 6. Actualizar campos usando los datos validados
        $fertilizer->title = $validatedData['title'];
        $fertilizer->description = $validatedData['description'];
        $fertilizer->type = $validatedData['type'];
        $fertilizer->amount = $validatedData['amount'];
        $fertilizer->stock = $validatedData['stock'];
        $fertilizer->price = $validatedData['price'];

        $location->address = $validatedData['address'];
        $location->link_google_maps = $validatedData['link']; // CORREGIDO
        $location->latitude = $lat;
        $location->longitude = $lng;

        // 7. Guardar ambos modelos
        $fertilizer->save();
        $location->save();

        // 8. Si todo salió bien, confirmar la transacción
        DB::commit();

        return redirect()->route('vista')->with('success', 'Producto actualizado correctamente.');

    } catch (\Exception $e) {
        // 9. Si algo falló (validación, firstOrFail, guardado), revertir
        DB::rollBack();
        
        // Es útil registrar el error real para depuración
        // \Log::error($e->getMessage()); 

        return redirect()->route('vista')->with('error', 'Error al actualizar. ' . $e->getMessage());
    }
}

    public function destroy($id)
    {
        $fertilizer = Fertilizer::findOrFail($id);

        // Eliminar imagen física si existe
        // if ($fertilizer->image && File::exists(public_path($fertilizer->image))) {
        //     unlink(public_path($fertilizer->image));
        // }

        $fertilizer->update([
            'state' => 0,
            'featured' => 0,
        ]);

        return redirect()->route('vista')->with('success', 'Producto eliminado exitosamente.');
    }



    public function starw($id)
    {
        $user = Auth::user();
        $plan = UserPlan::where('idUser', $user->id)
            ->where('active', 1)
            ->firstOrFail();

        $post = match ($plan->idPlan) {
            1 => 1,
            2 => 3,
            3 => 6,
            default => 0,
        };

        $fertilizer = Fertilizer::findOrFail($id);

        $rest = Fertilizer::where('idUser', $user->id)
            ->where('featured', 1)
            ->count();

        if ($fertilizer->featured == 1) {
            // Si ya está destacado, desmarcarlo sin límite
            $fertilizer->featured = 0;
            $fertilizer->save();
            return redirect()->route('vista')->with('success', 'Producto desmarcado correctamente.');
        } else {
            // Si quiere marcarlo, verifica que no exceda límite
            if ($rest < $post) {
                $fertilizer->featured = 1;
                $fertilizer->save();
                return redirect()->route('vista')->with('success', 'Producto marcado como destacado correctamente.');
            } else {
                return redirect()->route('vista')->with('error', 'Adquiera un plan mejorado para poder marcar más productos destacados.');
            }
        }
    }
}
