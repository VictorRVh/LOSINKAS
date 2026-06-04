<?php

namespace App\Http\Controllers;

use App\Models\CursoExamen;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CursoExamenController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $cursoExamenes = CursoExamen::query()
            ->with(['examen', 'curso', 'notaEstudiantes'])
            ->paginate($request->integer('per_page', 15));

        return response()->json($cursoExamenes);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'examen_id' => [
                'required',
                'integer',
                'exists:examenes,id',
                // Rule::unique('curso_examen')->where(fn ($query) => $query->where('curso_id', $request->input('curso_id'))),
            ],
            'curso_id' => ['required', 'integer', 'exists:cursos,id'],
        ]);

        $cursoExamen = CursoExamen::create($validated);

        return response()->json($cursoExamen->load(['examen', 'curso', 'notaEstudiantes']), 201);
    }

    public function show(CursoExamen $cursoExamen): JsonResponse
    {
        return response()->json($cursoExamen->load(['examen', 'curso', 'notaEstudiantes']));
    }

    public function update(Request $request, CursoExamen $cursoExamen): JsonResponse
    {
        $validated = $request->validate([
            'examen_id' => [
                'sometimes',
                'integer',
                'exists:examenes,id',
                Rule::unique('curso_examen')
                    ->ignore($cursoExamen->id)
                    ->where(fn ($query) => $query->where('curso_id', $request->input('curso_id', $cursoExamen->curso_id))),
            ],
            'curso_id' => ['sometimes', 'integer', 'exists:cursos,id'],
        ]);

        $cursoExamen->fill($validated);
        $cursoExamen->save();

        return response()->json($cursoExamen->load(['examen', 'curso', 'notaEstudiantes']));
    }

    public function destroy(CursoExamen $cursoExamen): JsonResponse
    {
        $cursoExamen->delete();

        return response()->json([
            'message' => 'Curso examen eliminado correctamente.',
        ]);
    }
}