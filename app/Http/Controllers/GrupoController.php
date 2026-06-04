<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GrupoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $grupos = Grupo::query()
            ->with(['periodo', 'curso', 'seccion', 'matriculas', 'estudiantes', 'notaEstudiantes'])
            ->when($request->filled('periodo_id'), fn ($query) => $query->where('periodo_id', $request->integer('periodo_id')))
            ->when($request->filled('curso_id'), fn ($query) => $query->where('curso_id', $request->integer('curso_id')))
            ->when($request->filled('seccion_id'), fn ($query) => $query->where('seccion_id', $request->integer('seccion_id')))
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $buscar = $request->string('buscar');

                $query->where('nombre_grupo', 'like', "%{$buscar}%");
            })
            ->orderBy('nombre_grupo')
            ->paginate($request->integer('per_page', 15));

        return response()->json($grupos);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'periodo_id' => ['required', 'integer', 'exists:periodos,id'],
            'curso_id' => ['required', 'integer', 'exists:cursos,id'],
            'seccion_id' => [
                'required',
                'integer',
                'exists:secciones,id',
                Rule::unique('grupos', 'seccion_id')
                    ->where(fn ($query) => $query
                        ->where('periodo_id', $request->input('periodo_id'))
                        ->where('curso_id', $request->input('curso_id'))),
            ],
            'nombre_grupo' => ['required', 'string', 'max:120'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $grupo = Grupo::create($validated);

        return response()->json($grupo->load(['periodo', 'curso', 'seccion', 'matriculas', 'estudiantes', 'notaEstudiantes']), 201);
    }

    public function show(Grupo $grupo): JsonResponse
    {
        return response()->json($grupo->load(['periodo', 'curso', 'seccion', 'matriculas', 'estudiantes', 'notaEstudiantes']));
    }

    public function update(Request $request, Grupo $grupo): JsonResponse
    {
        $validated = $request->validate([
            'periodo_id' => ['sometimes', 'integer', 'exists:periodos,id'],
            'curso_id' => ['sometimes', 'integer', 'exists:cursos,id'],
            'seccion_id' => [
                'sometimes',
                'integer',
                'exists:secciones,id',
                Rule::unique('grupos', 'seccion_id')
                    ->ignore($grupo->id)
                    ->where(fn ($query) => $query
                        ->where('periodo_id', $request->input('periodo_id', $grupo->periodo_id))
                        ->where('curso_id', $request->input('curso_id', $grupo->curso_id))),
            ],
            'nombre_grupo' => ['sometimes', 'string', 'max:120'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $grupo->update($validated);

        return response()->json($grupo->load(['periodo', 'curso', 'seccion', 'matriculas', 'estudiantes', 'notaEstudiantes']));
    }

    public function destroy(Grupo $grupo): JsonResponse
    {
        $grupo->delete();

        return response()->json([
            'message' => 'Grupo eliminado correctamente.',
        ]);
    }
}