<?php

namespace App\Http\Controllers;

use App\Http\Resources\MaterialResource;

use App\Models\Material;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

use Psy\CodeCleaner\FunctionReturnInWriteContextPass;


class MaterialController extends Controller
{
    public function index1(Request $request)
    {
        $materials = Material::query()
            ->when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->paginate(12);

            if(Auth::User()->role == "user")
            {
                return view('user.education.index', compact('materials'));
            }
            else{
                return view('admin.materials.index', compact('materials'));
            }
    }


    public function filtrar(Request $request)
    {
        $material = Material::query();

        if ($request->filled('search')) {
            $material->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('clasification')) {
            $material->where('clasification', $request->clasification);
        }

        if ($request->filled('aptitude')) {
            $material->where('aptitude', $request->aptitude);
        }

        if ($request->filled('type_category')) {
            $material->where('type_category', $request->type_category);
        }
        if (!$request->filled('search') && !$request->filled('clasification') && !$request->filled('aptitude') && !$request->filled('type_category')) {
            return redirect()->route('materiales.index');
        }

        $materials = $material->paginate(9);

        return view('user.education.index', compact('materials'));
    }


    public function index(Request $request)
    {
        $perPage = min($request->integer('per_page', 20), 100);

        $query = Material::query();

        // Búsqueda por texto
        if ($s = $request->string('search')->toString()) {
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('description', 'like', "%{$s}%");
            });
        }

        // Filtros opcionales por enums
        foreach (['clasification', 'aptitude', 'type_category'] as $field) {
            if ($val = $request->string($field)->toString()) {
                $query->where($field, $val);
            }
        }

        // Orden
        $sort = $request->string('sort')->toString() ?: 'created_at';
        $dir  = $request->string('dir')->toString()  ?: 'desc';
        if (!in_array($dir, ['asc', 'desc'])) $dir = 'desc';
        $query->orderBy($sort, $dir);

        return MaterialResource::collection($query->paginate($perPage));
    }

    // GET /api/materials/{id}
    public function show(Material $material)
    {
        return new MaterialResource($material);
    }

   public function store(Request $request)
{
    // Validación
    $request->validate([
        'name'          => 'required|string|max:255',
        'description'   => 'nullable|string',
        'clasification' => 'nullable|string',
        'aptitude'      => 'nullable|string',
        'type_category' => 'nullable|string',
        'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validación de imagen
    ]);

    $data = $request->all();

    // Guardar imagen si existe
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('uploads/materials'), $imageName);
        $data['image'] = 'uploads/materials/' . $imageName;
    }

    // Crear material
    Material::create($data);

    return redirect()->route('materials.index')->with('success', 'Material agregado correctamente.');
}

    // ✅ Formulario para editar
    public function edit(Material $material)
    {
        return view('admin.materials.edit', compact('material'));
    }

    // ✅ Actualizar material
    public function update(Request $request, Material $material)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'clasification' => 'nullable|string',
        'aptitude' => 'nullable|string',
        'type_category' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048', // valida imagen
    ]);

    // Datos para actualizar
    $data = $request->only(['name', 'description', 'clasification', 'aptitude', 'type_category']);

    // Manejo de imagen
    if ($request->hasFile('image')) {
        // Eliminar imagen anterior si existe
        if ($material->image && file_exists(public_path('uploads/materials/' . $material->image))) {
            unlink(public_path('uploads/materials/' . $material->image));
        }

        // Guardar nueva imagen
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('uploads/materials'), $imageName);
        $imageName = 'uploads/materials/'. $imageName;

        $data['image'] = $imageName;
    }

    // Actualizar el material
    $material->update($data);

    return redirect()->route('materials.index')->with('success', 'Material actualizado correctamente.');
}


    // ✅ Eliminar material
    public function destroy(Material $material)
    {
        $material->delete();

        return redirect()->route('admin.materials.index')->with('success', 'Material eliminado correctamente.');
    }

    public function create()
    {
        return view('admin.materials.create');
    }
}
