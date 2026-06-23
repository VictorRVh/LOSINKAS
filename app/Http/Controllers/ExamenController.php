<?php

namespace App\Http\Controllers;

use App\Models\Examen;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExamenController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $examenes = Examen::query()
            ->with(['grado', 'cursoExamenes'])
            ->when($request->filled('grado_area_id'), fn($query) => $query->where('grado_area_id', $request->integer('grado_area_id')))
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $buscar = $request->string('buscar');

                $query->where(function ($query) use ($buscar) {
                    $query->where('nombre_examen', 'like', "%{$buscar}%")
                        ->orWhere('numero_examen', 'like', "%{$buscar}%")
                        ->orWhere('descripcion', 'like', "%{$buscar}%");
                });
            })
            ->orderByDesc('fecha_examen')
            ->orderBy('numero_examen')
            ->paginate($request->integer('per_page', 15));

        return response()->json($examenes);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre_examen' => ['required', 'string', 'max:150'],
            'numero_examen' => ['required', 'integer', 'min:1'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'fecha_examen' => ['nullable', 'date'],
            'clave_respuestas' => ['nullable'],
            'grado_area_id' => ['required', 'integer', 'exists:grado_areas,id'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $examen = Examen::create($validated);

        return response()->json($examen->load(['grado', 'cursoExamenes']), 201);
    }

    public function show(Examen $examen): JsonResponse
    {
        return response()->json($examen->load(['grado', 'cursoExamenes']));
    }

    public function update(Request $request, Examen $examen): JsonResponse
    {
        $validated = $request->validate([
            'nombre_examen' => ['sometimes', 'string', 'max:150'],
            'numero_examen' => ['sometimes', 'integer', 'min:1'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'fecha_examen' => ['nullable', 'date'],
            'clave_respuestas' => ['nullable'],
            'grado_area_id' => ['sometimes', 'integer', 'exists:grado_areas,id'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $examen->update($validated);

        return response()->json($examen->load(['grado', 'cursoExamenes']));
    }

    public function destroy(Examen $examen): JsonResponse
    {
        $examen->delete();

        return response()->json([
            'message' => 'Examen eliminado correctamente.',
        ]);
    }
}
