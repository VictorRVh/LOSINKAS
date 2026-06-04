<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\GradoArea;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $cursos = Curso::query()
            ->with(['gradoArea', 'ajusteExamenes', 'cursoExamenes', 'grupo'])
            ->orderBy('nombre_curso')
            ->paginate($request->integer('per_page', 15));

        return response()->json($cursos);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'grado_area_id' => ['required', 'integer', 'exists:grado_areas,id'],
            'nombre_curso' => ['required', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $gradoArea = GradoArea::findOrFail($validated['grado_area_id']);
        unset($validated['grado_area_id']);

        $curso = new Curso($validated);
        $curso->gradoArea()->associate($gradoArea);
        $curso->save();

        return response()->json($curso->load(['gradoArea', 'ajusteExamenes', 'cursoExamenes', 'grupo']), 201);
    }

    public function show(Curso $curso): JsonResponse
    {
        return response()->json($curso->load(['gradoArea', 'ajusteExamenes', 'cursoExamenes', 'grupo']));
    }

    public function update(Request $request, Curso $curso): JsonResponse
    {
        $validated = $request->validate([
            'grado_area_id' => ['sometimes', 'integer', 'exists:grado_areas,id'],
            'nombre_curso' => ['sometimes', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        if (isset($validated['grado_area_id'])) {
            $curso->gradoArea()->associate(GradoArea::findOrFail($validated['grado_area_id']));
            unset($validated['grado_area_id']);
        }

        $curso->fill($validated);
        $curso->save();

        return response()->json($curso->load(['gradoArea', 'ajusteExamenes', 'cursoExamenes', 'grupo']));
    }

    public function destroy(Curso $curso): JsonResponse
    {
        $curso->delete();

        return response()->json([
            'message' => 'Curso eliminado correctamente.',
        ]);
    }
}