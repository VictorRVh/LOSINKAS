<?php

namespace App\Http\Controllers;

use App\Models\AjusteExamen;
use App\Models\Curso;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AjusteExamenController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $ajusteExamenes = AjusteExamen::query()
            ->with(['curso'])
            ->orderBy('orden')
            ->paginate($request->integer('per_page', 15));

        return response()->json($ajusteExamenes);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'curso_id' => ['required', 'integer', 'exists:cursos,id'],
            'nro_preguntas' => ['required', 'integer', 'min:1'],
            'peso' => ['required', 'numeric', 'min:0'],
            'orden' => ['required', 'integer', 'min:1'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $curso = Curso::findOrFail($validated['curso_id']);
        unset($validated['curso_id']);

        $ajusteExamen = new AjusteExamen($validated);
        $ajusteExamen->curso()->associate($curso);
        $ajusteExamen->save();

        return response()->json($ajusteExamen->load(['curso']), 201);
    }

    public function show(AjusteExamen $ajusteExamen): JsonResponse
    {
        return response()->json($ajusteExamen->load(['curso']));
    }

    public function update(Request $request, AjusteExamen $ajusteExamen): JsonResponse
    {
        $validated = $request->validate([
            'curso_id' => ['sometimes', 'integer', 'exists:cursos,id'],
            'nro_preguntas' => ['sometimes', 'integer', 'min:1'],
            'peso' => ['sometimes', 'numeric', 'min:0'],
            'orden' => ['sometimes', 'integer', 'min:1'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        if (isset($validated['curso_id'])) {
            $ajusteExamen->curso()->associate(Curso::findOrFail($validated['curso_id']));
            unset($validated['curso_id']);
        }

        $ajusteExamen->fill($validated);
        $ajusteExamen->save();

        return response()->json($ajusteExamen->load(['curso']));
    }

    public function destroy(AjusteExamen $ajusteExamen): JsonResponse
    {
        $ajusteExamen->delete();

        return response()->json([
            'message' => 'Ajuste de examen eliminado correctamente.',
        ]);
    }
}