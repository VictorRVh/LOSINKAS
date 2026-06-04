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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'grado_area_id' => ['required', 'integer', 'exists:grado_areas,id'],
            'nombre_curso' => ['required', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $curso = Curso::create([
            'grado_area_id' => $validated['grado_area_id'],
            'nombre_curso' => $validated['nombre_curso'],
            'descripcion' => $validated['descripcion'] ?? null,
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()
            ->route('grado-areas.cursos', $curso->grado_area_id)
            ->with('success', 'Curso creado correctamente');
    }

    public function show(Curso $curso): JsonResponse
    {
        return response()->json($curso->load(['gradoArea', 'ajusteExamenes', 'cursoExamenes', 'grupo']));
    }

    public function update(Request $request, Curso $curso)
    {
        $validated = $request->validate([
            'nombre_curso' => ['required', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $curso->update([
            'nombre_curso' => $validated['nombre_curso'],
            'descripcion' => $validated['descripcion'] ?? null,
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()
            ->route('grado-areas.cursos', $curso->grado_area_id)
            ->with('success', 'Curso actualizado correctamente');
    }

    public function destroy(Curso $curso)
    {
        $gradoAreaId = $curso->grado_area_id;

        $curso->delete();

        return redirect()
            ->route('grado-areas.cursos', $gradoAreaId)
            ->with('success', 'Curso eliminado correctamente');
    }

    public function byGradoArea(GradoArea $gradoArea)
    {
        $cursos = Curso::where('grado_area_id', $gradoArea->id)
            ->orderBy('nombre_curso')
            ->get();

        return view(
            'niveles.gradoAreas.cursos.cursosview',
            compact('gradoArea', 'cursos')
        );
    }
}
