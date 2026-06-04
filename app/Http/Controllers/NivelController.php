<?php

namespace App\Http\Controllers;

use App\Models\Nivel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NivelController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $niveles = Nivel::query()
            ->with('gradoAreas')
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $buscar = $request->string('buscar');

                $query->where(function ($query) use ($buscar) {
                    $query->where('nombre_nivel', 'like', "%{$buscar}%")
                        ->orWhere('descripcion', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('nombre_nivel')
            ->paginate($request->integer('per_page', 15));

        return response()->json($niveles);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre_nivel' => ['required', 'string', 'max:80', 'unique:niveles,nombre_nivel'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $nivel = Nivel::create($validated);

        return response()->json($nivel->load('gradoAreas'), 201);
    }

    public function show(Nivel $nivel): JsonResponse
    {
        return response()->json($nivel->load('gradoAreas'));
    }

    public function update(Request $request, Nivel $nivel): JsonResponse
    {
        $validated = $request->validate([
            'nombre_nivel' => ['sometimes', 'string', 'max:80', Rule::unique('niveles', 'nombre_nivel')->ignore($nivel->id)],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $nivel->update($validated);

        return response()->json($nivel->load('gradoAreas'));
    }

    public function destroy(Nivel $nivel): JsonResponse
    {
        $nivel->delete();

        return response()->json([
            'message' => 'Nivel eliminado correctamente.',
        ]);
    }
}