<?php

namespace App\Http\Controllers;

use App\Models\Seccion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SeccionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $secciones = Seccion::query()
            ->with('grupos')
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $buscar = $request->string('buscar');

                $query->where(function ($query) use ($buscar) {
                    $query->where('nombre_seccion', 'like', "%{$buscar}%")
                        ->orWhere('descripcion', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('nombre_seccion')
            ->paginate($request->integer('per_page', 15));

        return response()->json($secciones);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre_seccion' => ['required', 'string', 'max:50', 'unique:secciones,nombre_seccion'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $seccion = Seccion::create($validated);

        return response()->json($seccion->load('grupos'), 201);
    }

    public function show(Seccion $seccion): JsonResponse
    {
        return response()->json($seccion->load('grupos'));
    }

    public function update(Request $request, Seccion $seccion): JsonResponse
    {
        $validated = $request->validate([
            'nombre_seccion' => ['sometimes', 'string', 'max:50', Rule::unique('secciones', 'nombre_seccion')->ignore($seccion->id)],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $seccion->update($validated);

        return response()->json($seccion->load('grupos'));
    }

    public function destroy(Seccion $seccion): JsonResponse
    {
        $seccion->delete();

        return response()->json([
            'message' => 'Seccion eliminada correctamente.',
        ]);
    }
}